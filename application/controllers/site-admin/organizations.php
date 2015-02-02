<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organizations extends MX_Controller {

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */	
        $this->load->library("my_session");
		$this->load->library("my_helper");
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
            $crud->set_table(TBL_ORGANIZATIONS);
            $crud->set_subject('Organizations');

            $crud->columns('organizationCode', 'organizationName', 'organizationEmail', 'organizationPhone', 'organizationWebsite')
                ->display_as('organizationCode', 'Code')
                ->display_as('organizationName', 'Name')
                ->display_as('organizationEmail', 'Email')
                ->display_as('organizationPhone', 'Phone No.')
                ->display_as('organizationWebSite', 'Web Address');

            $crud->add_fields('organizationCode', 'organizationName','organizationAddress', 'organizationEmail', 'organizationPhone', 'organizationWebSite', 'organizationFax', 'active', 'organizationRoundSeal', 'organizationLogo', 'creatorId', 'createDate');
            $crud->edit_fields('organizationCode', 'organizationName','organizationAddress', 'organizationEmail', 'organizationPhone', 'organizationWebSite', 'organizationFax', 'active', 'organizationRoundSeal', 'organizationLogo', 'editorId', 'editDate');
            $crud->required_fields(array('organizationName','organizationEmail','organizationAddress','organizationPhone','organizationCode'));
            $crud->unique_fields('organizationCode', 'organizationName', 'organizationEmail');
            $crud->set_rules("organizationEmail", "Email", "trim|valid_email");
            //$crud->set_rules('organizationPhone', 'Phone No.', 'numeric');

            $crud->display_as('organizationAddress','Address')
                ->display_as('organizationFax','Fax')
                ->display_as('organizationRoundSeal','Round Seal')
                ->display_as('organizationLogo','Logo');

            $crud->unset_texteditor('organizationAddress');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId)
                ->field_type('createDate', 'hidden', $time)
                ->field_type('editorId', 'hidden', $this->my_session->userId)
                ->field_type('editDate', 'hidden', $time)
                ->set_field_upload('organizationLogo','assets/uploads/logo')
                ->set_field_upload('organizationRoundSeal','assets/uploads/seal')
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
            $output->pageTitle = "Organizations";
            $output->base_url = base_url();
            
            $output->body_template = "organizations_view.php";
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

    function validate_before_upload($files_to_upload, $field_info){
        foreach($files_to_upload as $value) {
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
        }
        $allowed_formats = array("jpg","gif", "png");
        if(in_array($ext,$allowed_formats)) return true;
        else return 'Wrong file format';
    }
}