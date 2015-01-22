<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of home
 *
 * @author USER
 */
Class Home extends MX_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library('my_session');
    }
    
    function index(){
		/** initialization **/
	    $data['css'] = "";
        $data['js'] = "";        
        $data['base_url'] = base_url();
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        if($this->my_session->logged_in) {
            $this->load->model("users_model");
            $result = $this->users_model->getUserInfoById($this->my_session->userId);
            if(!$result) {
                redirect(base_url().ADMIN_FOLDER.'home/logout');
                die();
            }
            $data['uinfo'] = $result->row();
            //var_dump($this->my_session->permissions);
            $data['permissions']= $this->my_session->get_modules($this->my_session->permissions);

            $data['pageTitle'] = "Back-Office Admin Panel";
    	    $data['body_template'] = "vcard.php";
            $this->load->view("vcard_layout.php",$data);
        }
        else {
            //$this->load->model("organizations_model");
            //$data['orgList'] = $this->organizations_model->getOrganizationNames();
            $data['pageTitle'] = "User Login";
            $this->load->view("login.php",$data);
        }       
    }

    function login(){
        $username = $this->input->post("username", true);
        $password = $this->input->post("password", true);
        //$organizationId = $this->input->post("organizationId", true);

        if($this->my_session->log_in($username,$password)) {
            redirect(base_url());
        }
        else {
            redirect(base_url());
        }
    }
}
?>