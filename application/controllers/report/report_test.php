<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 4:37 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_Test extends MX_Controller {
    function __construct(){
        parent::__construct();

        $this->load->database();
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
            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $this->load->model(REPORT_MODELS.'report_model', 'model');

            $rows= $this->model->get_data(TBL_REQUISITIONS, '*');
            //var_dump($rows);

            $output['data']= $rows;
            $output['css'] = "";
            $output['js'] = "";
            $output['pageTitle'] = "Stock Information";
            $output['base_url'] = base_url();
            $output['body_template'] = "report_test_view.php";

            $this->load->view(REPORT_LAYOUT, $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    function get_report(){
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->library('cezpdf');
        $this->cezpdf->ezText('PDF REPORT OF LOGIN TABLE', 12, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-10);
        $report = $this->db->select("userId 'User Id', firstName 'First Name', middleName 'Middle Name', lastName 'Last Name'")
            ->from(TBL_USERS)->get();
        $new_report = $this->dbutil->csv_from_result($report);
        write_file('csv_file.xml',$new_report);
    }
    function get_PDF(){
        $this->load->library('cezpdf');
        $this->load->helper('pdf_helper');
        prep_pdf();
        $this->db->select('userId, firstName, middleName, lastName');
        $this->db->from(TBL_USERS);
        $getData = $this->db->get();
        if($getData->num_rows() > 0)
            $data['detail']= $getData->result_array();
        else
            $data['detail']= null;

        $titlecolumn = array(
            'userId'=>'SL No.',
            'firstName'=>'First Name',
            'middleName'=>'Middle Name',
            'lastName'=>'Last Name'
        );
        $this->cezpdf->ezTable($data['detail'],$titlecolumn,'Supplier data');
        $this->cezpdf->ezStream();
    }
}
?>