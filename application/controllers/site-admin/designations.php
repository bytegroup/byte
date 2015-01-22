<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Designations extends MX_Controller {
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */	
        $this->load->library("my_session");
        $this->my_session->checkSession();     
        
        if(!isset($this->my_session->permissions['HEADER_Admin'])){
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
            $crud->set_table(TBL_DESIGNATIONS);
            $crud->set_subject('Designations');
            $crud->columns('designationName','designationGrade', 'designationDescription');
            $crud->display_as('designationName','Designation Name')
                ->display_as('designationGrade','Grade')
                ->display_as('designationRank', 'Rank')
                ->display_as('designationDescription', 'Description');

            $crud->add_fields('designationName', 'designationGrade', 'designationRank', 'designationDescription','active', 'creatorId', 'createDate');
            $crud->edit_fields('designationName', 'designationGrade', 'designationRank', 'designationDescription','active','editorId', 'editDate');

            $crud->required_fields(array('designationName','designationGrade'));
            $crud->unique_fields('designationName');
            $crud->unset_texteditor('designationDescription');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_add_field('active',array($this,'add_field_callback_active'));
            $crud->callback_edit_field('active',array($this,'edit_field_callback_active'));

            if(!isset($this->my_session->permissions['canAdminAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canAdminEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canAdminDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canAdminView'])){
                $crud->unset_read();
            }
            
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Designations";
            $output->base_url = base_url();
            
            $output->body_template = "gc_admin_setup_index.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function add_field_callback_active(){
        return ' <input type="radio" name="active" value="Active" checked="true"/> Active
        <input type="radio" name="active" value="Inactive" /> Inactive';
    }
    function edit_field_callback_active($active){
        return ' <input type="radio" name="active" value="Active" checked="true"/> Active
        <input type="radio" name="active" value="Inactive" /> Inactive';
    }
}