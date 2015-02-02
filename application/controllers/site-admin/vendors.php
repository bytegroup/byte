<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendors extends MX_Controller {
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
            $crud->set_table(TBL_VENDORS);
            $crud->set_subject('Vendor');

            $crud->columns('vendorsName', 'vendorsEmail', 'vendorsPhone', 'vendorsBusinessType');
            $crud->display_as('vendorsName','Name')
                ->display_as('vendorsAddress','Address')
                ->display_as('vendorsEmail','Email')
                ->display_as('vendorsBusinessType','Business Type')
                ->display_as('vendorsFax','Fax')
                ->display_as('vendorsWebSite','Website')
                ->display_as('vendorsPhone','Phone No.');

            $crud->add_fields('vendorsName', 'vendorsEmail', 'vendorsWebSite', 'vendorsPhone', 'vendorsFax', 'vendorsBusinessType','vendorsAddress', 'active', 'creatorId', 'createDate');
            $crud->edit_fields('vendorsName', 'vendorsEmail', 'vendorsWebSite', 'vendorsPhone', 'vendorsFax', 'vendorsBusinessType', 'vendorsAddress', 'active', 'editorId', 'editDate');
            $crud->required_fields(array('vendorsName', 'vendorsEmail', 'vendorsPhone', 'vendorsBusinessType'));
            $crud->unique_fields('vendorsName', 'vendorsEmail');
            $crud->set_rules("vendorsEmail", "Email", "trim|valid_email");
            //$crud->set_rules('vendorsPhone','Phone No.','trim|numeric');
            $crud->unset_texteditor('vendorsAddress');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_add_field('active',array($this,'add_field_callback_active'));
            $crud->callback_edit_field('active',array($this,'edit_field_callback_active'));

            //$crud->add_action('Contacts', '', '','ui-icon-image',array($this,'setContactsURL'));
            $crud->add_action('Contacts', '', base_url().ADMIN_FOLDER.'vendorContacts/index/','ui-icon-contact');

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
            $output->pageTitle = "Vendors";
            $output->base_url = base_url();
            $output->body_template = "vendors_view.php";
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
    /*function setContactsURL($primary_key, $value){
        if($value->adminId)
            return base_url().ADMIN_FOLDER.'contacts/index/'.$primary_key.'/edit/'.$value->adminId;
        else return base_url().ADMIN_FOLDER.'contacts/index/'.$primary_key.'/add';
    }*/
}