<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/10/15
 * Time: 4:24 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_Report extends MX_Controller {
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Report'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $this->load->model(REPORT_MODELS.'stock_report_model', 'model');
            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $output['headerFields']= array(
                'SL',
                'Company Name',
                'Category',
                'Item Name',
                'Item Code',
                'Item Details',
                'Mini. Req.',
                'New',
                'Repaired',
                'Issued',
                'R. Damage',
                'P. Damage'
            );

            $rows= $this->model->get_data();

            $output['data']= $rows;
            $output['css'] = "";
            $output['js'] = "";
            $output['pageTitle'] = "Stock Information";
            $output['base_url'] = base_url();
            $output['body_template'] = "stock_report_view.php";

            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
?>