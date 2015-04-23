<?php
/**
 * Created by PhpStorm.
 * User: Mizanur Rahman
 * Date: 16-04-15
 * Time: PM 11.09
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Transfer extends MX_Controller {
    var $stockId=0;
    var $companyId= 0;
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model('item_transfer_model', 'transferModel');
        $this->load->model('it_inventory_model', 'itModel');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index($stockId=0){
        if(!$stockId){
            redirect(base_url().IT_MODULE_FOLDER.'stock');
            die();
        }
        $this->stockId = $stockId;
        $this->companyId = $this->transferModel->get_company_id_by_stock_id($stockId);
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_ITEM_TRANSFER);
            $crud->set_relation("transferTo", TBL_COMPANIES, '{companyName}');
            $crud->set_relation("transferConfirmBy", TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->where('stockId', $stockId);
            $crud->set_subject('Transfer');

            $crud->columns('itemTransferDate', 'transferFrom','transferTo','transferConfirmDate');
            $crud->display_as('itemTransferDate','Transfer Date')
                ->display_as('transferFrom','Transfer From')
                ->display_as('transferTo','Transfer To')
                ->display_as('transferConfirmDate', 'Confirm Date')
                ->display_as('transferConfirmBy', 'Confirmed By')
                ->display_as('transferDescription', 'Description')
                ->display_as('transferQuantity', 'Transfer Quantity');

            $crud->add_fields('company', 'transferFrom', 'transferTo', 'itemTransferDate', 'transferDescription', 'stockQuantity', 'transferQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('company', 'transferTo', 'itemTransferDate', 'transferDescription', 'stockQuantity', 'transferQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('company', 'transferTo', 'itemTransferDate', 'transferDescription', 'stockQuantity', 'transferQuantity', 'items');
            $crud->required_fields(array('transferTo', 'itemTransferDate'));
            $crud->unset_texteditor('transferDescription');
            $crud->field_type('transferFrom', 'hidden', $this->companyId);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('company', array($this, 'callback_field_company'));
            $crud->callback_read_field('company', array($this, 'callback_read_field_company'));
            $crud->callback_field('stockQuantity', array($this, 'callback_field_stockQuantity'));
            $crud->callback_read_field('stockQuantity', array($this, 'callback_read_field_stockQuantity'));
            //$crud->callback_field('items', array($this, 'callback_field_items'));

            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->stockId=$stockId;
            $output->issueHeader= $this->transferModel->get_issue_item_header($stockId);

            $output->pageTitle = "Item Transfer";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "item_transfer_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_company($row, $key){
        return $this->transferModel->get_company_name_by_stock_id($this->stockId);
    }
    function callback_read_field_company($row, $key){
        return $this->transferModel->get_company_name_by_stock_id($this->stockId);
    }
    function callback_field_stockQuantity($row, $key){
        return $this->transferModel->get_stock_quantity($this->stockId);
    }
    function callback_read_field_stockQuantity($row, $key){
        return $this->transferModel->get_stock_quantity($this->stockId);
    }

    /*****************************/
    /***  ajax call functions  ***/
    /*****************************/

    /******************************************************************************/
}
?>