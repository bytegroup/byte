<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends MX_Controller {

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
            $this->load->helper('form');
            $this->load->library('form_validation');

            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_BUDGET);
            $crud->set_relation("companyId", TBL_COMPANIES, '{companyName}');
            $crud->set_subject('Budget');
            
            $crud->columns('budgetTitle', 'budgetHead', 'budgetAmount', 'budgetYear', 'budgetUtilization');
            $crud->display_as('budgetTitle','Budget Title')
                 ->display_as('companyId','Budget For')
                 ->display_as('budgetType','Budget Type')
                 ->display_as('budgetHead','Budget Head')
                ->display_as('budgetYear', 'Budget Year')
                ->display_as('budgetQuantity', 'Budget Quantity')
                ->display_as('budgetAmount', 'Budget Amount')
                ->display_as('budgetDescription', 'Budget Purpose')
                ->display_as('budgetUtilization', 'Utilization');

            $crud->add_fields('companyId', 'budgetTitle', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription', 'creatorId', 'createDate');
            $crud->edit_fields('companyId', 'budgetTitle', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription', 'editorId', 'editDate');
            $crud->set_read_fields('companyId', 'budgetTitle', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription');
            $crud->required_fields(array('companyId', 'budgetTitle', 'budgetHead', 'budgetYear', 'budgetType', 'budgetAmount'));
            //$crud->unique_fields('budgetHead');
            //$crud->set_rules('budgetHead', 'Budget Head', 'callback_validation_budgetHead');

            $crud->set_rules('budgetHead', 'Username','is_unique[ocl_budget.budgetHead]');

            $crud->unset_texteditor('budgetDescription');
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
            $output->pageTitle = "Budget List";
            $output->base_url = base_url();
            
            $output->body_template = "budget_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/

    public function username_check($str){
        $this->form_validation->set_message('username_check', 'The username already exists');
        return FALSE;

    }
}

function callback_validation_budgetHead($value){
    $this->form_validation->set_message('budgetHead', 'The field should be 1234');
    return FALSE;
}
?>