<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of profile
 *
 * @author USER
 */
Class Profile extends CI_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library('my_session');
        
        $this->my_session->checkSession();
    }
    
    function index()
    {
        $id = $this->my_session->userId;     
        $data = array();
        $this->load->model('users_model');
        $result = $this->users_model->getUserInfoById($id);
        if(!$result){
            redirect(base_url().ADMIN_FOLDER);
            die();
        }
        $data['uinfo'] = $result->row_array();
        
        $this->load->library("my_functions");
        /** initialization **/        
        $data['css'] = "";
                
        $data['js'] = "";  
        
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        $data['pageTitle'] = "Profile Information";
        $data['base_url'] = base_url();
        
        $data['body_template'] = 'profile.php';
		
		$req = $_SERVER['HTTP_REFERER'];
		$reqFrom = substr($req,strlen($req)-15,15);
		if($reqFrom=="ocl-backoffice/"){
			$this->load->view("vcard_layout.php",$data);  	
		}
		else{
			$this->load->view(MAIN_TEMPLATE_FILE,$data);  
		}
    }
    
    function edit_profile()
    {
        $action = "";
        if(array_key_exists("action", $_POST)){
            $action = $_POST['action'];
        }
        if($action == "edit_profile"){
            if(isset($_POST['action'])){ 
                
                $this->load->library("my_functions");
                
                $userid = $this->my_session->userId;
                $email = $this->input->post("email", true);
                $phone = $this->input->post("phone", true);
                
                
                $this->load->library("form_validation");
                                
                $this->form_validation->set_rules('phone', 'Phone number', 'trim|required|xss_clean|required');
                $this->form_validation->set_rules('email', 'Email', 'callback_checkEmail');
                
                if ($this->form_validation->run() == FALSE)
                {
                    $errors['errors'] = validation_errors();
                    $errors['errorState'] = true;
                    echo json_encode($errors);
                }
                else
                {                                      
                    $time = time();

                    $userdata = array('email'=> $email,'phone'=> $phone);                    
                    $this->load->model('users_model');
                    $value = $this->users_model->updateUserInfo($userdata,$userid);
                    if($value){
                        $success['success'] = true;
                        $success['msg'] = "Your Information has been updated.";                        
                    }else{
                        $success['success'] = false;
                        $success['msg'] = "Your Information could not be updated. Please try again later.";                        
                    }
                    echo json_encode($success);
                }                
            }
        }else{
            die();
        }            
    }
    
       
    function checkEmail($str){
        
        $id = $this->my_session->userId;
        $old_name = "";
        $result = null;
        
        if(!filter_var($str, FILTER_VALIDATE_EMAIL))
        {
            $this->form_validation->set_message("checkEmail","The %s format address is not valid.");
            return false;
        }
        
        if(!empty($id) && is_numeric($id))
        {
            $this->db->where("userId", $id);            
            $result = $this->db->get(TBL_USERS);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->email;
                }
                
                $this->db->where_not_in("email", array($old_name));
                $this->db->where("email", $str);
                //$this->db->where("userId", $id);
                $num_rows = $this->db->get(TBL_USERS)->num_rows();
                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkEmail","The %s already Exist. Please try a different email address.");
                    return false;
                }
            }
        }   
    }
    
    
    function edit_password()
    {
        $action = "";
        if(array_key_exists("action", $_POST)){
            $action = $_POST['action'];
        }
        if($action == "edit_password"){
            if(isset($_POST['action'])){ 
                
                $this->load->library("my_functions");
                
                $userid = (int)$this->my_session->userId;
                $oldpass = $this->input->post("oldpassword", true);
                $newpass = $this->input->post("newpassword", true);
                $confpass = $this->input->post("confirmpassword", true);
                
                $this->load->library("form_validation");
                                
                $this->form_validation->set_rules('oldpassword', 'Old Password', 'trim|xssclean|required|callback_checkOldPass');
                $this->form_validation->set_rules('newpassword', 'New Password', 'trim|xssclean|required|matches[confirmpassword]');                
                $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|xssclean|required');
                
                if ($this->form_validation->run() == FALSE)
                {
                    $errors['errors'] = validation_errors();
                    $errors['errorState'] = true;
                    echo json_encode($errors);
                    die();
                }
                if($this->form_validation->run() == TRUE){
                    if($oldpass == $newpass){
                        $success['success'] = true;
                        $success['msg'] = "Your password has been updated.";  
                        echo json_encode($success);
                        die();
                    }
                }
                
                
                $data = array("password" => md5($newpass));
                $this->db->where('userId',$userid);
                $this->db->update(TBL_USERS, $data);
                
                if($this->db->affected_rows() > 0){
                    $success['success'] = true;
                    $success['msg'] = "Your password has been updated.";              
                }else{
                    $success['success'] = false;
                    $success['msg'] = "Your password could not be updated. Please try again later.";                        
                }
                echo json_encode($success);
            }
        }    
    }
    
    function checkOldPass($str)
    {
        $userid = (int)$this->my_session->userId;
        
        $this->db->where("password",  md5($str));
        $result = $this->db->get(TBL_USERS);
        
        if($result->num_rows() > 0)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('checkOldPass', 'The %s you entered is not correct.');
            return FALSE;
        }    
    }
}

?>
