<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/18/15
 * Time: 1:07 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bill_Report extends MX_Controller {
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model(REPORT_MODELS.'bill_report_model', 'model');
        $this->load->library('filter_form');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Report'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $this->filter_form->set_filter_fields($this->model->get_filters());
            $output['filter_form']= $this->filter_form->get_filter_form();
            $output['headers']= $this->model->get_headers();
            $rows= $this->model->get_data();
            $output['data']= $rows;
            $output['css'] = "";
            $output['js'] = "";
            $output['table_js']= base_url(REPORT_ASSETS.'dataTable.config.js');
            $output['pageTitle'] = "Bill Report";
            $output['base_url'] = base_url();
            $output['body_template'] = "bill_report_view.php";
            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************************************************************************************/
    function ajax_get_data(){
        echo json_encode($this->model->get_data($_POST));
        exit;
    }

    function get_excel(){
        $this->load->library('excel');

        $post= (isset($_POST) && count($_POST)) ? $_POST : array();

        $this->excel->getProperties()->setCreator($this->my_session->userName);
        $this->excel->setActiveSheetIndex(0);
        $sheet= $this->excel->getSheet(0);
        $sheet->setTitle('Bill Report');

        $endColumn= chr(ord('A') + count($this->model->get_headers())-1);
        $style = array(
            'font' => array('bold' => true),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );

        $sheet->setCellValue('A1', 'Bill Report');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A1')->applyFromArray($style);
        $sheet->mergeCells('A1:'.$endColumn.'1');
        $sheet->getRowDimension('1')->setRowHeight(30);

        $this->excel->set_table($sheet, $this->model->get_headers(), $this->model->get_data($post), 2);
        $this->excel->set_column_width_auto($sheet);
        $this->excel->set_all_borders($sheet);

        $filename='Bill_report.xlsx';
        $this->excel->set_headers($filename);

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
?>