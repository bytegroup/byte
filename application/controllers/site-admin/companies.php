<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies extends MX_Controller {

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
            $crud->set_table(TBL_COMPANIES);
            $crud->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}');
            $crud->set_subject('Companies');

            $crud->columns('companyCode','companyName','companyPhone','companyEmail','organizationId');
            $crud->display_as('companyName','Company')
                ->display_as('organizationId','Organization')
                ->display_as('companyAddress','Address')
                ->display_as('companyEmail','Email')
                ->display_as('companyDescription','Description')
                ->display_as('companyCode','Code')
                ->display_as('companyFax','Fax')
                ->display_as('companyWebSite','Web Address')
                ->display_as('companyRoundSeal','Round Seal')
                ->display_as('companyLogo','Logo')
                ->display_as('companyPhone','Phone');

            $crud->add_fields('organizationId', 'companyName', 'companyCode', 'companyEmail', 'companyPhone', 'companyWebSite','companyFax', 'companyAddress','companyDescription', 'active','companyRoundSeal','companyLogo', 'creatorId', 'createDate');
            $crud->edit_fields('organizationId', 'companyName', 'companyCode', 'companyEmail', 'companyPhone', 'companyWebSite','companyFax', 'companyAddress','companyDescription', 'active','companyRoundSeal','companyLogo', 'editorId', 'editDate');

            $crud->required_fields(array('companyName','organizationId','companyEmail'));
            $crud->unique_fields('CompanyName', 'companyEmail', 'companyCode');
            $crud->set_rules("companyEmail", "Email", "trim|valid_email");
            //$crud->set_rules('companyPhone','Phone No.','trim|xss_clean|numeric');
            $crud->unset_texteditor('companyAddress')->unset_texteditor('companyDescription');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time)
                ->set_field_upload('companyLogo','assets/uploads/logo')
                ->set_field_upload('companyRoundSeal','assets/uploads/seal')
                ->callback_before_upload(array($this,'validate_before_upload'))
                ->callback_add_field('active',array($this,'add_field_callback_active'))
                ->callback_edit_field('active',array($this,'edit_field_callback_active'));

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
            $output->pageTitle = "Companies";
            $output->base_url = base_url();
            
            $output->body_template = "companies_view.php";
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
        if($active=='Inactive')
            return ' <input type="radio" name="active" value="Active" /> Active
                 <input type="radio" name="active" value="Inactive" checked="true"/> Inactive';
        else
            return ' <input type="radio" name="active" value="Active" checked="true"/> Active
                 <input type="radio" name="active" value="Inactive" /> Inactive';
    }
}