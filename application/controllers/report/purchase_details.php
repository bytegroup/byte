<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/9/15
 * Time: 3:59 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_Details extends MX_Controller {
    var $receiveId= 0;
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model(REPORT_MODELS.'purchase_details_model', 'model');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Report'])){
            die("not allowed");
        }
    }

    function index($recId){
        if(!$recId){
            redirect(base_url(REPORT_FOLDER.'purchase_list'));
            die();
        }
        $this->receiveId= $recId;
        try{
            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $output['metaTitle']= $this->model->get_meta_data_title();
            $output['metadata']= $this->model->get_meta_data($recId);
            $output['headers']= $this->model->get_headers();
            $rows= $this->model->get_data($recId);
            $output['data']= $rows;
            $output['receiveId']=$recId;
            $output['css'] = "";
            $output['js'] = "";
            $output['table_js']= base_url(REPORT_ASSETS.'dataTable.config.js');
            $output['backToList']= base_url(REPORT_FOLDER.'purchase_list');
            $output['pageTitle'] = "Purchase Details";
            $output['base_url'] = base_url();
            $output['body_template'] = "purchase_details_view.php";
            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************************************************************************************/
    function get_excel($recId){
        if(!$recId){
            redirect(base_url(REPORT_FOLDER.'purchase_list'));
            die();
        }
        $this->load->library('excel');

        $this->excel->getProperties()->setCreator($this->my_session->userName);
        $this->excel->setActiveSheetIndex(0);
        $sheet= $this->excel->getSheet(0);
        $sheet->setTitle('Purchase Details');

        $endColumn= chr(ord('A') + count($this->model->get_headers())-1);
        $style = array(
            'font' => array('bold' => true),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );

        $sheet->setCellValue('A1', 'Purchase Details');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A1')->applyFromArray($style);
        $sheet->mergeCells('A1:'.$endColumn.'1');
        $sheet->getRowDimension('1')->setRowHeight(30);

        $this->excel->set_meta_data($sheet, $this->model->get_meta_data($recId), 2, 'A', 3, (count($this->model->get_headers())-3));
        $this->excel->set_table($sheet, $this->model->get_headers(), $this->model->get_data($recId), $sheet->getHighestRow()+2);
        $this->excel->set_column_width_auto($sheet);
        $this->excel->set_all_borders($sheet);

        $filename='Purchase_details.xlsx';
        $this->excel->set_headers($filename);

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
?>