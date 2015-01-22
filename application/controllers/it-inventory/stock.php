<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends MX_Controller {
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
            $crud->set_table(TBL_STOCK);
            $crud->set_relation("itemMasterId", TBL_ITEMS_MASTER, '{itemName}');
            $crud->set_subject('Stock');

            $crud->display_as('stockNumber', 'Stock No.');
            $crud->display_as('itemMasterId', 'Item');
            $crud->display_as('stockQuantity', 'Stock Quantity');
            $crud->display_as('issueQuantity', 'Issued Quantity');

            $crud->columns('stockNumber', 'itemMasterId', 'stockQuantity', 'issueQuantity');

            $crud->unset_add()->unset_edit()->unset_delete()->unset_read();

            $crud->add_action('Issue', "", IT_MODULE_FOLDER.'issue/index', 'ui-icon-person');
            $crud->add_action('Damage', "", IT_MODULE_FOLDER.'damage/index', 'ui-icon-tag');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Stock List";
            $output->base_url = base_url();
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "stock_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
?>