<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/27/15
 * Time: 7:11 PM
 */
?>
<?php
require_once APPPATH."third_party/PHPExcel/PHPExcel.php";

class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
        $this->getProperties()
            ->setCreator('Mamun')
            ->setTitle('PHPExcel Demo')
            ->setDescription('A demo to show how to use PHPExcel to manipulate an Excel file')
            ->setSubject('PHP Excel manipulation')
            ->setKeywords('excel php office phpexcel lakers')
            ->setCategory('programming')
        ;
    }

    public function set_table($objSheet, $headers, $rows, $startRow, $startColumn='A'){
        $style = array(
            'font' => array('bold' => true),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $numOfColumns= sizeof($headers);
        $endColumn= chr(ord($startColumn) + $numOfColumns-1);
        $objSheet->fromArray($headers, ' ', $startColumn.$startRow);
        $objSheet->getStyle($startColumn.$startRow.':'.$endColumn.$startRow)->applyFromArray($style);
        $objSheet->fromArray($rows, ' ', $startColumn.($startRow+1));
    }

    public function set_column_width_auto($objSheet, $startColumn='A', $numOfColumns=null){

        if($numOfColumns==null){
            $endColumn= $objSheet->getHighestColumn();
        }
        else {
            $endColumn= chr(ord($startColumn) + $numOfColumns-1);
        }
        foreach(range($startColumn, $endColumn) as $columnID) {
            $objSheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
    public function set_all_borders($objSheet, $startColumn='A', $startRow=1, $numOfColumns=null, $numOfRows=null){
        if($numOfColumns==null){
            $endColumn=$objSheet->getHighestColumn();
        }
        else {
            $endColumn= $endColumn= chr(ord($startColumn) + $numOfColumns-1);
        }
        if($numOfRows==null){
            $endRow=$objSheet->getHighestRow();
        }
        else{
            $endRow= $startRow + $numOfRows;
        }
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $objSheet->getStyle($startColumn.$startRow.':'.$endColumn.$endRow)
            ->applyFromArray($styleArray);
    }
    public function set_headers($fileName){
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
    }
}
?>