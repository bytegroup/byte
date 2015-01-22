<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/17/14
 * Time: 8:16 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition_Items extends MX_Controller{

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library("my_functions");
        $this->load->model("requisition_model");

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index($id){
        $this->id=$id;
        if(!$id){
            redirect(base_url().IT_MODULE_FOLDER.'requisition');
            die();
        }
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_REQUISITIONS_DETAIL);
            $crud->set_relation('itemMasterId', TBL_ITEMS_MASTER, '{itemName}');
            $crud->set_relation('categoryId', TBL_CATEGORIES, '{categoryName}');
            $crud->where('requisitionId', $id);
            $crud->set_subject('Requisition Item');

            $crud->columns('requisitionId', 'itemMasterId', 'orderedQuantity', 'receivedQuantity');
            $crud->display_as('requisitionId', 'Requisition Title')
                ->display_as('itemMasterId', 'Item')
                ->display_as('orderedQuantity', 'Ordered Quantity')
                ->display_as('categoryName', 'Category')
                ->display_as('receivedQuantity', 'Received Quantity');
            $crud->callback_column('requisitionId', array($this, 'callback_column_requisitionId'));
            $crud->callback_column('itemMasterId', array($this, 'callback_column_itemMasterId'));

            $crud->fields('requisitionId', 'categoryId', 'itemMasterId', 'orderedQuantity');
            $crud->required_fields(array('requisitionId','categoryId', 'itemMasterId', 'orderedQuantity'));
            $crud->field_type('requisitionId', 'hidden', $id);

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
            $output->pageTitle = "Requisition Items";
            $output->base_url = base_url();
            $output->backToRequisitionList=base_url().IT_MODULE_FOLDER.'requisition';

            $output->body_template = "requisition_items_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_column_requisitionId($key, $row){
        $this->db->select("requisitionTitle")
            ->from(TBL_REQUISITIONS)
            ->where('requisitionId', $row->requisitionId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->requisitionTitle;
    }
    function callback_column_itemMasterId($key, $row){
        $this->db->select("itemName")
            ->from(TBL_ITEMS_MASTER)
            ->where('itemMasterId', $row->itemMasterId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->itemName;
    }

    /*****************************/
    /***  ajax call functions  ***/
    /*****************************/
    function ajax_get_itemMaster($sourceId){
        if ($sourceId <= 0) {
            echo json_encode(null);
            exit;
        }
        $this->db->select("itemMasterId, itemName")
            ->from(TBL_ITEMS_MASTER)
            ->where('itemMasterId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->itemMasterId, "property" => $row->itemName);
        endforeach;
        echo json_encode($array);
        exit;
    }
}
?>