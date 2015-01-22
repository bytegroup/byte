<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage extends MX_Controller {
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

   function index($stockId){
       if(!$stockId){
           redirect(base_url().IT_MODULE_FOLDER.'stock');
           die();
       }
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);
            
            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DAMAGE);
            $crud->set_relation("itemMasterId", TBL_ITEMS_MASTER, '{itemName}');
            $crud->where('stockId', $stockId);
            $crud->set_subject('Damage');

            $crud->field_type('creatorId', 'hidden');
            $crud->field_type('createDate', 'hidden');
            $crud->field_type('editorId', 'hidden');
            $crud->field_type('editDate', 'hidden');

            $crud->columns('itemMasterId','damageType','damageQuantity','damageDate');
            $crud->display_as('itemMasterId','Product')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('checkedById', 'Checked By')
                ->display_as('damageDetails', 'Details')
                ->display_as('damageRemarks', 'Remarks');

            $crud->add_fields('stockId', 'damageType', 'damageDate', 'damageQuantity', 'checkedById', 'damageDetails', 'damageRemarks', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'damageType', 'damageDate', 'damageQuantity', 'checkedById', 'damageDetails', 'damageRemarks', 'editorId', 'editDate');
            $crud->unset_texteditor('damageDetails', 'damageRemarks');
            $crud->field_type('stockId', 'hidden', $stockId);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);

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
            $crud->add_action('Repair', "", IT_MODULE_FOLDER.'repair/index', 'ui-icon-wrench');

            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Damaged Products";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $output->body_template = "damage_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}