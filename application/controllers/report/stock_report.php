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
        $this->load->model(REPORT_MODELS.'stock_report_model', 'model');
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

            $output['headers']= $this->model->get_headers();
            $rows= $this->model->get_data();
            $output['data']= $rows;
            $output['css'] = "";
            $output['js'] = "";
            $output['table_js']= base_url(REPORT_ASSETS.'dataTable.config.js');
            $output['pageTitle'] = "Stock Information";
            $output['base_url'] = base_url();
            $output['body_template'] = "stock_report_view.php";
            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************************************************************************************/
    function get_excel(){
        $this->load->library('excel');

        $this->excel->getProperties()->setCreator($this->my_session->userName);
        $this->excel->setActiveSheetIndex(0);
        $sheet= $this->excel->getSheet(0);

        $sheet->setTitle('Stock Report');
        $style = array(
            'font' => array('bold' => true, 'size'=>11),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:L4')->applyFromArray($style);
        $sheet->setCellValue('A1', 'Current Stock Report');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->mergeCells('A1:L1');
        $sheet->getRowDimension('1')->setRowHeight(50);

        $sheet->setCellValue('H2', 'Current Stock');
        $sheet->mergeCells('H2:L2');

        $sheet->setCellValue('H3', 'Stock');
        $sheet->mergeCells('H3:I3');

        $sheet->setCellValue('K3', 'Damage');
        $sheet->mergeCells('K3:L3');

        $this->excel->set_table($sheet, $this->model->get_headers(), $this->model->get_data(), $sheet->getHighestRow());
        $this->excel->set_column_width_auto($sheet);
        $this->excel->set_all_borders($sheet);

        $filename='stock_report.xlsx';
        $this->excel->set_headers($filename);

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
?>