<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/13/15
 * Time: 8:04 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition_for_Damage extends MX_Controller {
    function __construct(){
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index(){
        var_dump(json_decode($_POST['damagedItems']));
    }

}