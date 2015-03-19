<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Master extends MX_Controller {

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
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
            $crud->set_table(TBL_ITEMS_MASTER);
            $crud->set_relation("unitId", TBL_UNITS, '{unitName}');
            $crud->set_relation("categoryId", TBL_CATEGORIES, '{categoryName}');
            $crud->set_subject('Item Master');

            $crud->columns('itemName','itemCode','unitId','categoryId', 'itemType');
            $crud->display_as('itemName','Name')
                ->display_as('unitId','Unit')
                ->display_as('categoryId','Category')
                ->display_as('itemCode','Code')
                ->display_as('minimumQuantity', 'Minimum Quantity')
                ->display_as('itemDescription','Description')
                ->display_as('serialNumber','Serial No.')
                ->display_as('itemType','Item Type');

            $crud->add_fields('categoryId', 'itemName', 'itemCode', 'minimumQuantity', 'itemType', 'unitId', 'itemDescription', 'active', 'creatorId', 'createDate');
            $crud->edit_fields('categoryId', 'itemName', 'itemCode', 'minimumQuantity', 'itemType', 'unitId', 'itemDescription', 'active', 'editorId', 'editDate');
            $crud->set_read_fields('categoryId', 'itemName', 'itemCode', 'minimumQuantity', 'itemType', 'unitId', 'itemDescription', 'active');
            $crud->required_fields(array('categoryId', 'itemName', 'itemCode', 'unitId', 'itemType'));
            $crud->unique_fields('itemName', 'itemCode');
            $crud->unset_texteditor('itemDescription');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_add_field('itemType',array($this,'add_field_callback_itemType'));
            $crud->callback_edit_field('itemType',array($this,'edit_field_callback_itemType'));
            $crud->callback_add_field('active',array($this,'add_field_callback_active'));
            $crud->callback_edit_field('active',array($this,'edit_field_callback_active'));

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
            $output->pageTitle = "Item Master List";
			//die($output->base_url);
            $output->base_url = base_url();
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "item_master_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back validations ***/
    /*****************************/
    function add_field_callback_itemType(){
        return '<input type="radio" name="itemType" value="Countable"/> Countable
        <input type="radio" name="itemType" value="Uncountable" /> Uncountable';
    }
    function edit_field_callback_itemType($itemType){
        if($itemType == 'Uncountable')
            return '<input type="radio" name="itemType" value="Countable"/> Countable
                    <input type="radio" name="itemType" value="Uncountable" checked="true" /> Uncountable';

        else if($itemType == 'Countable')
            return '<input type="radio" name="itemType" value="Countable" checked="true"/> Countable
                    <input type="radio" name="itemType" value="Uncountable" /> Uncountable';

        else return '<input type="radio" name="itemType" value="Countable"/> Countable
                    <input type="radio" name="itemType" value="Uncountable" /> Uncountable';
    }
    function add_field_callback_active(){
        return ' <input type="radio" name="active" value="Active" checked="true"/> Active
        <input type="radio" name="active" value="Inactive" /> Inactive';
    }
    function edit_field_callback_active($active){
        if($active=='Inactive')
            return ' <input type="radio" name="active" value="Active" /> Active
                 <input type="radio" name="active" value="Inactive" checked="true"/> Inactive';
        else
            return ' <input type="radio" name="active" value="Active" checked="true"/> Active
                 <input type="radio" name="active" value="Inactive" /> Inactive';
    }
}
?>