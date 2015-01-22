<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/2/15
 * Time: 12:46 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_List extends MX_Controller {
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
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_REPAIR);
            $crud->set_relation("repairTypeId", TBL_REPAIR_TYPE, '{repairTypeName}');
            $crud->set_relation("vendorsId", TBL_VENDORS, '{vendorsName}');
            $crud->set_subject('Repair');

            $crud->columns('repairName','repairTypeId','repairDate','vendorsId', 'repairQuantity');
            $crud->display_as('repairName','Name')
                ->display_as('repairDate','Date')
                ->display_as('repairTypeId','Repair Type')
                ->display_as('vendorsId','Vendor')
                ->display_as('repairVendorsId','Repair vendor')
                ->display_as('repairQuantity','Quantity');

            $crud->unset_add()->unset_edit();

            $crud->add_fields('repairName', 'repairTypeId', 'vendorsId', 'repairQuantity', 'repairDate', 'repairDetails', 'repairRemarks', 'creatorId', 'createDate');
            $crud->edit_fields('repairName', 'repairTypeId', 'vendorsId', 'repairQuantity', 'repairDate', 'repairDetails', 'repairRemarks', 'editorId', 'editDate');
            $crud->unset_texteditor('repairDetails', 'repairRemarks');
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

            $crud->add_action('Stock', "", IT_MODULE_FOLDER.'repair/complete_Repair', 'ui-icon-plus');
            $crud->add_action('Damage', "", IT_MODULE_FOLDER.'repair/complete_Repair', 'ui-icon-minus');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Repair";
            $output->base_url = base_url();
            $output->backToDamageList= base_url(IT_MODULE_FOLDER.'repair_list');
            $output->body_template = "repair_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}