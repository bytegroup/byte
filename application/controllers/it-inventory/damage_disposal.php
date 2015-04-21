<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/20/15
 * Time: 7:17 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_Disposal extends MX_Controller {
    var $damagedItems= [];
    function __construct(){
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            if($crud->getState()==='add'){
                if(!isset($_POST['damagedItems']) || !$_POST['damagedItems']){
                    redirect(base_url(IT_MODULE_FOLDER.'permanent_damaged'));
                    die();
                } else $this->damagedItems= json_decode($_POST['damagedItems']);
            }else {$this->damagedItems=null;}

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DISPOSAL);
            $crud->set_relation('handoverTo', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_relation('checkedBy', TBL_USERS, '{firstName} {middleName} {lastName}');
            /*$crud->where('damageType', 'Permanent-Damage');*/
            $crud->set_subject('Disposal of Damage');

            $crud->columns('handoverTo', 'checkedBy', 'disposalDate');
            $crud->display_as('handoverTo','Handover To')
                ->display_as('checkedBy', 'Checked By')
                ->display_as('disposalDate','Disposal Date');

            $crud->add_fields('handoverTo', 'checkedBy', 'disposalDate', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('handoverTo', 'checkedBy', 'disposalDate', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('handoverTo', 'checkedBy', 'disposalDate', 'items');
            $crud->required_fields('handoverTo', 'checkedBy', 'disposalDate');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('items', array($this, 'callback_field_items'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_disposal'));
            $crud->callback_after_update(array($this, 'callback_after_update_disposal'));

            if(!isset($this->my_session->permissions['canIT-InventoryAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryView'])){
                $crud->unset_read();
            }

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Disposed Product List";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'damage_disposal');
            $output->body_template = "damage_disposal_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/

    function callback_field_items($row, $key){
        $damageIds= [];
        if(!$key){
            foreach($this->damagedItems as $damageId=>$code){$damageIds[]=$damageId;}
            $damageItemsDetails= $this->item_details($damageIds);
        }else $damageItemsDetails= $this->get_disposed_items($key);

        return $this->get_html_for_items($damageItemsDetails);
    }
    function callback_read_field_items($row, $key){
        $itemDetails= $this->get_disposed_items($key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Tracking No.</li><li>Item Name</li><li>Category</li><li>Quantity</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach ($itemDetails as $damageId => $item) {
            $html .= '<li><ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>' . $item['code'] . '</li>';
            $html .= '<li>' . $item['name'] . '</li>';
            $html .= '<li>' . $item['category'] . '</li>';
            $html .= '<li>'.$item['qty'].'</li>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';

        return $html;
    }
    function callback_after_insert_disposal($post, $key){
        $items= $post['damageDetailIds'];

        foreach($items as $item){
            $this->db->insert(TBL_DISPOSAL_DETAILS, array('disposalId'=>$key, 'damageDetailId'=>$item));
            $this->db->update(TBL_DAMAGE_DETAIL, array('active'=>false), array('damageDetailId'=>$item));
        }
    }
    function callback_after_update_disposal($post, $key){
        $preItems= $this->get_disposed_items($key);
        $damagedItems= $post['damageDetailIds'];
        foreach($preItems as $id=>$item){
            if(!in_array($id, $damagedItems)){
                $this->db->delete(TBL_DISPOSAL_DETAILS, array('disposalId'=>$key, 'damageDetailId'=>$id));
                $this->db->update(TBL_DAMAGE_DETAIL, array('active'=>true), array('damageDetailId'=>$id));
            }
        }
    }

    /*********************************************************************************************/
    function item_details($damIds=[]){
        $this->db->select('dd.damageDetailId, dd.damageQuantity, sd.productCode, im.itemName, cat.categoryName, com.companyId');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as com ', 'com.companyId=s.companyId');
        $this->db->where_in('dd.damageDetailId', $damIds);
        $db=$this->db->get();
        if(!$db->num_rows())return [];
        $array= [];
        foreach($db->result() as $row ){
            $array[$row->damageDetailId]= [
                'code'      => $row->productCode,
                'name'      => $row->itemName,
                'category'  => $row->categoryName,
                'companyId' => $row->companyId,
                'qty'       => $row->damageQuantity
            ];
        }
        return $array;
    }
    function get_disposed_items($disposalId){
        $this->db->select('dd.damageDetailId, dd.damageQuantity, sd.productCode, im.itemName, cat.categoryName, com.companyId');
        $this->db->from(TBL_DISPOSAL_DETAILS.' as dDetails');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=dDetails.damageDetailId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as com ', 'com.companyId=s.companyId');
        $this->db->where_in('dDetails.disposalId', $disposalId);
        $db=$this->db->get();
        if(!$db->num_rows())return [];
        $array= [];
        foreach($db->result() as $row ){
            $array[$row->damageDetailId]= [
                'code'      => $row->productCode,
                'name'      => $row->itemName,
                'category'  => $row->categoryName,
                'companyId' => $row->companyId,
                'qty'       => $row->damageQuantity
            ];
        }
        return $array;
    }

    function get_html_for_items($itemDetails){
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Tracking No.</li><li>Item Name</li><li>Category</li><li>Quantity</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach ($itemDetails as $damageId => $item) {
            $html .= '<li><ul>';
            $html .= '<li><a class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" title="Remove from list">X</a></li>';
            $html .= '<li>' . $item['code'] . '</li>';
            $html .= '<li>' . $item['name'] . '</li>';
            $html .= '<li>' . $item['category'] . '</li>';
            $html .= '<li>'.$item['qty'].'';
            $html .= '<input type="hidden" name="damageDetailIds[]" value="' . $damageId . '"/>';
            $html .= '<input type="hidden" name="companyId['.$damageId.']" value="'.$item['companyId'].'"/></li>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';

        return $html;
    }
}