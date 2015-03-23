<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/23/15
 * Time: 3:48 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_Head extends MX_Controller{
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index(){
        try {
            $this->load->library('grocery_CRUD');
            //$this->load->library('gc_extended_unique_validation');

            $crud = new grocery_CRUD($this);
            //$crud = new GC_Extended_unique_validation($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_BUDGET_HEAD);
            $crud->set_subject('Budget Head');

            $crud->columns('budgetHead', 'budgetType', 'budgetPurpose');
            $crud->display_as('budgetHead', 'Budget Head')
                ->display_as('budgetType', 'Budget Type')
                ->display_as('budgetPurpose', 'Budget Purpose');

            $crud->add_fields('budgetHead', 'budgetType', 'budgetPurpose', 'creatorId', 'createDate');
            $crud->edit_fields('budgetHead', 'budgetType', 'budgetPurpose', 'editorId', 'editDate');
            $crud->set_read_fields('budgetHead', 'budgetType', 'budgetPurpose');
            $crud->required_fields(array('budgetHead', 'budgetType'));
            $crud->unique_fields('budgetHead');
            $crud->unset_texteditor('budgetPurpose');
            $crud->field_type('budgetType', 'dropdown', array('Capital'=>'Capital', 'Revenue'=>'Revenue'));
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);

            if (!isset($this->my_session->permissions['canIT-InventoryAdd'])) {
                $crud->unset_add();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryEdit'])) {
                $crud->unset_edit();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryDelete'])) {
                $crud->unset_delete();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryView'])) {
                $crud->unset_read();
            }

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Budget Heads";
            $output->base_url = base_url();

            $output->body_template = "budget_head_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/

    /*********************************************************************************/
}
?>