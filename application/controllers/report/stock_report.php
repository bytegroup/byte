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

    /*****************************************************************************************************/
    function get_excel(){
        //load our new PHPExcel library
        $this->load->library('excel');
//activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
//name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
//set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
//change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
//make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
//set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $filename='just_some_random_name.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    function get_excel2(){


        $this->load->library('excel');

        $objPHPExcel= $this->excel;
// Set document properties
        $objPHPExcel->getProperties()->setCreator("Thouhedul islam")
            ->setLastModifiedBy("Thouhedul islam")
            ->setTitle("PHPExcel Tutorial from tisuchi.com")
            ->setSubject("PHPExcel Tutorial from tisuchi.com")
            ->setDescription("This is the tutorial for PHP Excel from tisuchi.com")
            ->setKeywords("office PHPExcel php")
            ->setCategory("Tutorial Result");


// Add Data in your file

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Visit ')
            ->setCellValue('B1', 'tisuchi.com')
            ->setCellValue('C1', 'for interesting')
            ->setCellValue('D1', 'tutorail');

        $objPHPExcel->getActiveSheet()->setCellValue('A8',"Posted in \n tisuchi.com");
        $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);



// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('tisuchi.com');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        //$callEndTime = microtime(true);
        //$callTime = $callEndTime - $callStartTime;

        echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, 'http://localhost/php')) , EOL;

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        $callEndTime = microtime(true);
        //$callTime = $callEndTime - $callStartTime;


// Echo done
        echo " Done writing files" , EOL;
    }
}
?>