<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/31/14
 * Time: 2:35 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receive_Items extends MX_Controller{
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

    function index($receiveId=0){
        if(!$receiveId){
            redirect(base_url().IT_MODULE_FOLDER.'stock');
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
            $crud->set_table(TBL_RECEIVES_DETAIL);
            $crud->where('receiveId', $receiveId);
            $crud->set_subject('Received Item');

            $crud->display_as('quotationId', 'Quotation');
            $crud->display_as('approveBy', 'Approved By');
            $crud->display_as('approveDate', 'Approve Date');
            $crud->display_as('approvalAttachment', 'Attachment');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";

            $output->js = "";
            $output->pageTitle = "Received Items";
            $output->base_url = base_url();


            $output->body_template = "received_items_view.php";
            $output->body_template = "received_items_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
?>