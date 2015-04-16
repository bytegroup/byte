<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/13/15
 * Time: 8:04 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition_for_Damage extends MX_Controller {
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
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_REQUISITIONS);
            $crud->where('requisitionType', 'Damage-Sell');
            $crud->set_subject('Requisition For Damage Sell');

            $crud->columns('requisitionTitle', 'requisitionNumber', 'requisitionCreateDate');
            $crud->display_as('requisitionTitle', 'Requisition Title')
                ->display_as('requisitionNumber', 'Requisition No.')
                ->display_as('requisitionCreateDate', 'Requisition Date')
                ->display_as('requisitionDescription', 'Description');

            $crud->add_fields('requisitionTitle', 'requisitionCreateDate', 'requisitionDescription', 'requisitionType', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('requisitionTitle', 'requisitionCreateDate', 'requisitionDescription', 'items', 'editorId', 'editDate');
            $crud->required_fields('requisitionTitle', 'requisitionCreateDate');
            $crud->unset_texteditor('requisitionDescription');
            $crud->field_type('requisitionNumber', 'readonly');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->field_type('requisitionType', 'hidden', 'Damage-Sell');
            $crud->callback_add_field('items', array($this, 'callback_add_field_items'));
            $crud->callback_edit_field('items', array($this, 'callback_edit_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_requisition'));

            if (!isset($this->my_session->permissions['canIT-InventoryAdd'])) {
                $crud->unset_add();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryEdit'])) {
                $crud->unset_edit();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryView'])) {
                $crud->unset_read();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryDelete'])) {
                $crud->unset_delete();
            }

            //$crud->unset_add()->unset_delete()->unset_list()->unset_export()->unset_print();
            //$crud->unset_back_to_list();


           /* $crud->set_lang_string(
                'update_success_message',
                'Item added to stock successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'receive/index').'/'.$quotId.'"'
                .'</script>'
                .'<div style="display:none">'
            );*/

           /* $crud->set_lang_string('form_update_changes','Add To Stock');
            $crud->set_lang_string('form_edit', 'Add');*/

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Requisition For Damaged Product Sell";
            $output->base_url = base_url();
            //$output->backToReceive = base_url(IT_MODULE_FOLDER.'/receive/index').'/'. $quotId;
            $output->body_template = "requisition_for_damage_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);
        }catch (Exception $e){
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/

    function callback_add_field_items($row, $key){
        $damageIds= [];
        foreach($this->damagedItems as $damageId=>$code){$damageIds[]=$damageId;}
        $damageItemsDetails= $this->item_details($damageIds);
        return $this->get_html_for_items($damageItemsDetails);

    }
    function callback_edit_field_items($row, $key){
        return $this->get_html_for_items($this->item_details_requisitionId($key));
    }
    function callback_after_insert_requisition($post, $key){
        $damageDetailIds= $post['damageDetailIds'];
        foreach($damageDetailIds as $damageDetailId){
            $this->db->insert(
                TBL_DAMAGE_SOLD,
                array(
                    'requisitionId' => $key,
                    'damageDetailId'=> $damageDetailId
                )
            );
            $this->db->update(TBL_DAMAGE_DETAIL, array('active'=>false), array('damageDetailId'=>$damageDetailId));
        }
    }

    /*********************************************************************************/
    function item_details($damIds=[]){
        $this->db->select('dd.damageDetailId, sd.productCode, im.itemName, cat.categoryName, com.companyId');
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
                'companyId' => $row->companyId
            ];
        }
        return $array;
    }
    function item_details_requisitionId($reqId){
        $this->db->select('dd.damageDetailId, sd.productCode, im.itemName, cat.categoryName, com.companyId');
        $this->db->from(TBL_REQUISITIONS.' as r ');
        $this->db->join(TBL_DAMAGE_SOLD.' as ds ', 'ds.requisitionId=r.requisitionId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=ds.damageDetailId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as com ', 'com.companyId=s.companyId');
        $this->db->where('r.requisitionId', $reqId);
        $this->db->where('r.requisitionType', 'Damage-Sell');
        $db=$this->db->get();
        if(!$db->num_rows())return [];
        $array= [];
        foreach($db->result() as $row ){
            $array[$row->damageDetailId]= [
                'code'      => $row->productCode,
                'name'      => $row->itemName,
                'category'  => $row->categoryName,
                'companyId' => $row->companyId
            ];
        }
        return $array;
    }
    function get_html_for_items($itemDetails){
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Tracking No.</li><li>Item Name</li><li>Category</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach ($itemDetails as $damageId => $item) {
            $html .= '<li><ul>';
            $html .= '<li>' . $item['code'] . '</small></li>';
            $html .= '<li>' . $item['name'] . '</li>';
            $html .= '<li>' . $item['category'] . '</li>';
            $html .= '<input type="hidden" name="damageDetailIds[]" value="' . $damageId . '"/>';
            $html .= '<input type="hidden" name="companyId['.$damageId.']" value="'.$item['companyId'].'"/>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';

        return $html;
    }
}