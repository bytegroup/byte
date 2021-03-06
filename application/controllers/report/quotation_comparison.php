<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/17/15
 * Time: 12:45 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation_Comparison extends MX_Controller {
    var $requisitionId=0;
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model(REPORT_MODELS.'quotation_comparison_model', 'model');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Report'])){
            die("not allowed");
        }
    }

    function index($reqId){
        if(!$reqId){
            redirect(base_url(REPORT_FOLDER.'requisition_list'));
            die();
        }
        $this->requisitionId= $reqId;
        try{
            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $this->model->set_quotations($reqId);
            $output['metaTitle']= $this->model->get_meta_data_title();
            $output['metadata']= $this->model->get_meta_data($reqId);
            $output['vendors']= $this->model->get_quotations();
            $output['headers']= $this->model->get_headers();
            $output['data']= $this->model->get_data($reqId);
            $output['requisitionId']= $reqId;
            $output['backToList'] = base_url(REPORT_FOLDER.'requisition_list');
            $output['css'] = "";
            $output['js'] = "";
            $output['table_js']= base_url(REPORT_ASSETS.'dataTable.config.js');
            $output['pageTitle'] = "Quotation Comparison Statement";
            $output['base_url'] = base_url();
            $output['body_template'] = "quotation_comparison_view.php";
            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************************************************************************************/
    function get_excel($reqId){
        if(!$reqId){
            redirect(base_url(REPORT_FOLDER.'requisition_list'));
            die();
        }
        $this->load->library('excel');
        $this->model->set_quotations($reqId);
        $vendors= $this->model->get_quotations();

        $this->excel->getProperties()->setCreator($this->my_session->userName);
        $this->excel->setActiveSheetIndex(0);
        $sheet= $this->excel->getSheet(0);
        $sheet->setTitle('Quotation Comparison Statement');

        $endColumn= chr(ord('A') + count($this->model->get_headers())-1);
        $style = array(
            'font' => array('bold' => true),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );

        $sheet->setCellValue('A1', 'Vendor Wise Quotation Comparison Statement');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A1')->applyFromArray($style);
        $sheet->mergeCells('A1:'.$endColumn.'1');
        $sheet->getRowDimension('1')->setRowHeight(30);

        $this->excel->set_meta_data($sheet, $this->model->get_meta_data($reqId), 2, 'A', 3, count($this->model->get_headers())-3);
        $startRow= $sheet->getHighestRow()+2;
        $endColumn= chr(ord('A') + 3);
        $sheet->setCellValue('A'.$startRow, '');
        $sheet->getStyle('A'.$startRow)->applyFromArray($style);
        $sheet->mergeCells('A'.$startRow.':'.$endColumn.$startRow);
        foreach($vendors as $vendor){
            $startColumn= chr(ord($endColumn) + 1);
            $endColumn= chr(ord($startColumn) + 8);
            $sheet->setCellValue($startColumn.$startRow, $vendor);
            $sheet->getStyle($startColumn.$startRow)->applyFromArray($style);
            $sheet->mergeCells($startColumn.$startRow.':'.$endColumn.$startRow);
        }

        $this->excel->set_table($sheet, $this->model->get_headers(), $this->model->get_data($reqId), $sheet->getHighestRow()+1);
        $this->excel->set_column_width_auto($sheet);
        $this->excel->set_all_borders($sheet);

        $filename='Quotation_comparison.xlsx';
        $this->excel->set_headers($filename);

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
?>