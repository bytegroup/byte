<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/13/15
 * Time: 12:22 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permanent_Damaged extends MX_Controller {
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

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DAMAGE_DETAIL);
            $crud->set_relation('stockDetailId', TBL_STOCK_DETAIL, '{productCode}');
            $crud->where('damageType', 'Permanent-Damage');
            $crud->set_subject('Permanent Damage');

            $crud->columns('check', 'stockDetailId', 'itemName', 'category', 'stockNumber');
            $crud->callback_column('stockNumber', array($this, 'callback_column_stockNumber'));
            $crud->callback_column('itemName', array($this, 'callback_column_itemName'));
            $crud->callback_column('category', array($this, 'callback_column_category'));
            $crud->callback_column('check', array($this, 'callback_column_check'));
            $crud->display_as('stockNumber','Stock No.')
                ->display_as('stockDetailId', 'Product Code')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('itemName', 'Item Name');

            $crud->edit_fields('stockDetailId', 'itemName', 'damageQuantity', 'damageType');
            $crud->field_type('stockDetailId', 'readonly');
            $crud->field_type('damageQuantity', 'readonly');
            $crud->field_type('damageType', 'enum', array('Repairable-Damage', 'Permanent-Damage'));
            $crud->field_type('creatorId', 'hidden');
            $crud->field_type('createDate', 'hidden');
            $crud->field_type('editorId', 'hidden');
            $crud->field_type('editDate', 'hidden');
            $crud->callback_field('itemName', array($this, 'callback_field_itemName'));

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

            $crud->unset_add()->unset_edit()->unset_read()->unset_delete();
            //$crud->add_action('Repair', '', '', 'ui-icon-wrench', array($this, 'callback_action_repair'));

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Permanently Damaged List";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $output->body_template = "permanent_damaged_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/
    function callback_column_stockNumber($value, $row){
        return $this->get_stock_number($row->damageId);
    }
    function callback_column_itemName($value, $row){
        return $this->get_item_name($row->damageId);
    }
    function callback_column_category($value, $row){
        return $this->get_category_name($row->damageId);
    }
    function callback_column_check($value, $row){
        //var_dump($value);
        //var_dump($row);
        $check= !$row->active ? 'checked disabled':'';
        $html ='';
        $html .= '<input type="checkbox" name="damageDetailIds[]" '.$check.' value="'.$row->damageDetailId.'"/>';

        return $html;
    }
    function callback_field_itemName($row, $key){
        return $this->get_item_name_by_damageDetailId($key);
    }
    function callback_action_repair($key, $row){
        //$repairId= $this->get_repair_id($key);
        if($this->is_repairable($key))
            //return base_url(IT_MODULE_FOLDER.'repair/index/'.$key);
            //if(!$repairId)
            return base_url(IT_MODULE_FOLDER.'repair/index/'.$key).'/add';
        //else return base_url(IT_MODULE_FOLDER.'repair/index/'.$key).'/edit/'.$repairId;
        else return '#';
    }

    /*********************************************************************************************/
    function get_stock_number($damageId){
        if(!$damageId) return '';
        $this->db->select('s.stockNumber');
        $this->db->from(TBL_DAMAGE.' as d ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=d.stockId');
        $this->db->where('d.damageId', $damageId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        return $db->result()[0]->stockNumber;
    }
    function get_item_name($damageId){
        if(!$damageId) return '';
        $this->db->select('i.itemName');
        $this->db->from(TBL_DAMAGE.' as d ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=d.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->where('d.damageId', $damageId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        return $db->result()[0]->itemName;
    }
    function get_category_name($damageId){
        if(!$damageId) return '';
        $this->db->select('c.categoryName');
        $this->db->from(TBL_DAMAGE.' as d ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=d.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->where('d.damageId', $damageId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        return $db->result()[0]->categoryName;
    }
    function get_item_name_by_damageDetailId($damageDetailId){
        if(!$damageDetailId) return '';
        $this->db->select('i.itemName');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_DAMAGE.' as d ', 'd.damageId=dd.damageId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=d.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->where('dd.damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        return $db->result()[0]->itemName;
    }
    function is_repairable($damageDetailId){
        if(!$damageDetailId) return false;
        $this->db->select('damageType');
        $this->db->from(TBL_DAMAGE_DETAIL);
        $this->db->where('damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows()) return false;
        if($db->result()[0]->damageType==='Repairable-Damage') return true;
        return false;
    }
    function get_repair_id($damageDetailId){
        if(!$damageDetailId) return false;
        $this->db->select('repairId');
        $this->db->from(TBL_REPAIR);
        $this->db->where('damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows()) return 0;
        return $db->result()[0]->repairId;
    }
}