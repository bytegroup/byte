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
            $this->load->library('gc_extension_for_unique_in_period');
            //$crud = new grocery_CRUD($this);
            $crud = new GC_extension_for_unique_in_period($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_REPAIR_TYPE);
            $crud->set_relation("categoryId", TBL_CATEGORIES, '{categoryName}');
            $crud->set_subject('Repair Service');

            $crud->columns('categoryId', 'serviceType', 'serviceRate', 'serviceStartDate', 'serviceEndDate');
            $crud->display_as('serviceType','Service Name')
                ->display_as('serviceTypeDescription','Description')
                ->display_as('categoryId','Category')
                ->display_as('serviceStartDate', 'Start Date')
                ->display_as('serviceEndDate', 'End Date')
                ->display_as('serviceRate', 'Service Rate')
                ->display_as('serviceWarranty', 'Service Warranty (days)');

            $crud->add_fields('categoryId', 'serviceType', 'serviceTypeDescription', 'serviceRate', 'serviceWarranty', 'serviceStartDate', 'serviceEndDate', 'creatorId', 'createDate');
            $crud->edit_fields('categoryId', 'serviceType', 'serviceTypeDescription', 'serviceRate', 'serviceWarranty', 'serviceStartDate', 'serviceEndDate', 'editorId', 'editDate');
            $crud->set_read_fields('categoryId', 'serviceType', 'serviceTypeDescription', 'serviceRate','serviceWarranty', 'serviceStartDate', 'serviceEndDate');
            $crud->required_fields(array('categoryId', 'serviceType', 'serviceRate', 'serviceStartDate', 'serviceEndDate'));
            $crud->unique_field_in_period('serviceType/serviceStartDate/serviceEndDate');
            $crud->unset_texteditor('serviceTypeDescription');
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
    /*** callback functions ***/
    /*****************************/
    
}