<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 12:31 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agreement_List extends MX_Controller {
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
            $crud->set_table(TBL_SERVICE_AGREEMENTS);
            $crud->set_relation("vendorsId", TBL_VENDORS, 'vendorsName');
            $crud->set_subject('Service Agreement');

            $crud->columns('serviceAgreementName', 'serviceAgreementType', 'serviceAgreementAmount', 'serviceAgreementStartDate', 'serviceAgreementEndDate');
            $crud->display_as('serviceAgreementName','Service Name')
                ->display_as('serviceAgreementType','Service Type')
                ->display_as('serviceAgreementAmount','Service Amount')
                ->display_as('serviceAgreementStartDate', 'Start Date')
                ->display_as('serviceAgreementEndDate', 'End Date');

            $crud->add_fields('serviceAgreementName', 'serviceAgreementType', 'serviceAgreementAmount', 'serviceAgreementStartDate', 'serviceAgreementEndDate', 'vendorsId', 'creatorId', 'createDate');
            $crud->edit_fields('serviceAgreementName', 'serviceAgreementType', 'serviceAgreementAmount', 'serviceAgreementStartDate', 'serviceAgreementEndDate', 'vendorsId', 'editorId', 'editDate');
            $crud->set_read_fields('serviceAgreementName', 'serviceAgreementType', 'serviceAgreementAmount', 'serviceAgreementStartDate', 'serviceAgreementEndDate', 'vendorsId');
            $crud->required_fields(array('serviceAgreementName', 'serviceAgreementType'));
            $crud->unique_field_in_period('serviceAgreementName/serviceAgreementStartDate/serviceAgreementEndDate');
            $crud->field_type('serviceAgreementType', 'enum', array('Normal', 'Weekly', 'Quarterly', 'Monthly', 'Yearly'));
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
            $output->pageTitle = "Service Agreement List";
            $output->base_url = base_url();
            $output->body_template = "agreement_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** callback functions ***/
    /*****************************/

}