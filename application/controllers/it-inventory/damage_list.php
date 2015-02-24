<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 2:11 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_List extends MX_Controller {
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
            $crud->set_subject('Damage');

            $crud->field_type('creatorId', 'hidden');
            $crud->field_type('createDate', 'hidden');
            $crud->field_type('editorId', 'hidden');
            $crud->field_type('editDate', 'hidden');

            $crud->columns('stockNumber', 'stockDetailId', 'itemName', 'damageType');
            $crud->callback_column('stockNumber', array($this, 'callback_column_stockNumber'));
            $crud->callback_column('itemName', array($this, 'callback_column_itemName'));
            $crud->display_as('stockNumber','Stock No.')
                ->display_as('stockDetailId', 'Product Code')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('itemName', 'Item Name');

            $crud->unset_add()->unset_edit()->unset_read();

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
            $crud->add_action('Repair', '', '', 'ui-icon-wrench', array($this, 'callback_action_repair'));
            //$crud->add_action('Sell', '', IT_MODULE_FOLDER.'sell/index', 'ui-icon-calculator');
            //$crud->add_action('Garbage', '', IT_MODULE_FOLDER.'garbage/index', 'ui-icon-trash');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Damage List";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $output->body_template = "damage_list_view.php";
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
    function callback_action_repair($key, $row){
        if($this->is_repairable($key)){
            return base_url(IT_MODULE_FOLDER.'repair/index').'/'.$key;
        }
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
}