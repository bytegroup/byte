<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_Type extends MX_Controller {
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
            $crud->set_table(TBL_REPAIR_TYPE);
            $crud->set_relation("categoryId", TBL_CATEGORIES, '{categoryName}');
            $crud->set_subject('Repair Type');

            $crud->columns('repairTypeName', 'repairTypeDescription');
            $crud->display_as('repairTypeName','Name')
                ->display_as('repairTypeDescription','Description')
                ->display_as('categoryId','Product Category');

            $crud->add_fields('repairTypeName', 'repairTypeDescription', 'creatorId', 'createDate');
            $crud->edit_fields('repairTypeName', 'repairTypeDescription', 'editorId', 'editDate');
            $crud->required_fields(array('repairTypeName'));
            $crud->unset_texteditor('repairTypeDescription');
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
            
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Repair Type";
            $output->base_url = base_url();
            $output->body_template = "repair_type_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back validations ***/
    /*****************************/
    
}