<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends MX_Controller
{

    function __construct()
    {
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

    function index()
    {
        try {
            $this->load->library('grocery_CRUD');
            $this->load->library('gc_extended_unique_validation');

            //$crud = new grocery_CRUD($this);
            $crud = new GC_Extended_unique_validation($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_BUDGET);
            $crud->set_relation("companyId", TBL_COMPANIES, '{companyName}');
            $crud->set_subject('Budget');

            $crud->columns('budgetHead', 'budgetAmount', 'budgetYear', 'budgetUtilization');
            $crud->display_as('budgetTitle', 'Budget Title')
                ->display_as('companyId', 'Budget For')
                ->display_as('budgetType', 'Budget Type')
                ->display_as('budgetHead', 'Budget Head')
                ->display_as('budgetYear', 'Budget Year')
                ->display_as('budgetQuantity', 'Budget Quantity')
                ->display_as('budgetAmount', 'Budget Amount')
                ->display_as('budgetDescription', 'Budget Purpose')
                ->display_as('budgetUtilization', 'Utilization');

            $crud->add_fields('companyId', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription', 'creatorId', 'createDate');
            $crud->edit_fields('companyId', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription', 'editorId', 'editDate');
            $crud->set_read_fields('companyId', 'budgetHead', 'budgetYear', 'budgetType', 'budgetQuantity', 'budgetAmount', 'budgetDescription');
            $crud->required_fields(array('companyId', 'budgetHead', 'budgetYear', 'budgetType', 'budgetAmount'));
            $crud->unique_field_in_group('budgetHead/companyId');
            $crud->unset_texteditor('budgetDescription');
            $crud->field_type('budgetYear', 'dropdown', $this->year_generator(2010, 20));
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
            $output->pageTitle = "Budget List";
            $output->base_url = base_url();

            $output->body_template = "budget_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/

    /*********************************************************************************/
    /**
     * @param $start
     * @param $noOfYear
     * @return array
     */
    function year_generator($start, $noOfYear){
        $year= array();
        for($i=0; $i<=$noOfYear; $i++){$year[$start + $i]=$start+$i;}
        return $year;
    }
}
?>