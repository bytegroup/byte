<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Units extends MX_Controller {

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
            $crud->set_table(TBL_UNITS);
            $crud->set_subject('Unit');

            $crud->columns('unitName', 'unitCode', 'unitDescription');
            $crud->display_as('unitName', 'Name')
                ->display_as('unitCode', 'Code')
                ->display_as('unitDescription', 'Description');

            $crud->add_fields('unitName', 'unitCode','unitDescription', 'active', 'creatorId', 'createDate');
            $crud->edit_fields('unitName', 'unitCode','unitDescription', 'active', 'editorId', 'editDate');
            $crud->required_fields(array('unitName', 'unitCode'));
            $crud->unique_fields('unitName', 'unitCode');
            $crud->unset_texteditor('unitDescription');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
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
            $output->pageTitle = "Units";
			//die($output->base_url);
            $output->base_url = base_url();
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "units_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back validations ***/
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