<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 7:22 PM
 */
?>
<?php

class Generate extends CI_Controller{
    function Generate(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }


    function create(){
        $this->load->library('cezpdf');

        $this->cezpdf->ezText('PDF REPORT OF LOGIN TABLE', 12, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-10);
        $i = 1;
        $content = "";

        $fname = "";
        $query = $this->db->query('SELECT * FROM '.TBL_USERS);
        $num = $query->num_fields();
        $farr = array();

        while ($i <= $num) {
            $test = $i;
            $value = $this->input->post($test);

            if ($value != '') {
                $fname = $fname . " " . $value;
                array_push($farr, $value);
            }
            $i++;
        }

        $fname = trim($fname);

        $fname = str_replace(' ', ',', $fname);
        $this->db->select($fname);
        $query = $this->db->get('table_name');
        $result = $query->result();

        foreach ($farr as $j) {

            $content = strtoupper($j) . "\n\n";
            foreach ($result as $res) {
                $content = $content . $res->$j . "\n";
            }

            $this->cezpdf->ezText($content, 10);

            $this->cezpdf->ezStream();
        }

    }
}