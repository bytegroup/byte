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
       // $this->my_session->logged_in=false;
    }

    function index()
    {        
        /** initialization **/
        $data['css'] = "";        
        $data['js'] = "";        
        $data['base_url'] = base_url();
        $data['css_files'] = array();
        $data['js_files'] = array();


        if($this->my_session->logged_in)
        {            
            $this->load->model("users_model");
            $result = $this->users_model->getUserInfoById($this->my_session->userId);
            if(!$result)
            {
                redirect(base_url().ADMIN_FOLDER.'home/logout');
                die();
            }
            
            $data['uinfo'] = $result->row();

            $data['pageTitle'] = "Home";
            $data['body_template'] = "home.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$data);            
        }
        else
        {
            //$this->load->model("organizations_model");
            //$data['orgList'] = $this->organizations_model->getOrganizationNames();
            $data['pageTitle'] = "Login";            
            $this->load->view("login.php",$data);
        }       
        
    }
    
    function login()
    {
        $username = $this->input->post("username", true);
        $password = $this->input->post("password", true);
        //$organizationId = $this->input->post("organizationId", true);
        //$authWord = $this->input->post("authentication",true);
        
       /* if($authWord != $this->my_session->getLoginAuthWord())
        {
            $this->my_session->setLoginError();
            redirect(base_url());
        }
        else
        {
            $this->my_session->checkLogin($username,$password);
        }*/
        if($this->my_session->log_in($username,$password))
        {
            redirect(base_url());
        }
        else
        {
            redirect(base_url());
        }
        
    }
    
    function logout()
    {
        $this->my_session->log_out();
        redirect(base_url().ADMIN_FOLDER."home");
    }
    
    function forgot_password()
    {
        if($this->my_session->logged_in)
        {            
            redirect(base_url().ADMIN_FOLDER);
            die();
        }
        else
        {
            $action = $this->input->post("action",true);
            if($action != "retrieve_password")
            {
                redirect(base_url().ADMIN_FOLDER);
                die();
            }
            $username = $this->input->post("username",true);
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clear');
            
            if ($this->form_validation->run() == FALSE)
            {
                $data['message'] = "<font color='red'>".validation_errors()."Please go back and try again.</font>";
            }
            else
            {
                $this->load->model("users_model");
                $result = $this->users_model->getUserInfo($username);
                if($result)
                {
                    $result = $result->row();
                    
                    $this->load->helper("string");
                    $newpass = random_string('alpha',8);
                    
                    $data = array("password" => md5($newpass)); 
                    $result_update = $this->users_model->updateUserInfo($data, $result->userId);
                    
                    if($result_update)
                    {
                        $this->load->library("my_mailer");
                        
                        $userinfo['userName'] = $username;
                        $userinfo['password'] = $newpass;
                        $userinfo['firstName'] = $result->firstName;
                        $userinfo['lastName'] = $result->lastName;
                        $userinfo['email'] = $result->email;
                        $userinfo['base_url'] = base_url();
                        
                        $this->my_mailer->forgot_password_mail($userinfo);
                        //$data['message'] = $msg;
                        $data['message'] = "Your password has been successfully changed. Please check your email for the new password.";                           
                    }
                    else
                    {
                        $data['message'] = "There was a problem. Please try again later.";   
                    }                    
                }
                else
                {
                    $data['message'] = "The Username entered was not found in our database.<br />Please go back and try again.";
                }                
            }
            /** initialization **/
            $data['css'] = "";        
            $data['js'] = "";        
            $data['base_url'] = base_url();

            $data['pageTitle'] = "Forgot Password";
            
            $this->load->view("forgot_password.php",$data);
        }
    }
    
       
    function ssettings()
    {
        /** initialization **/
        $data['css'] = "";        
        $data['js'] = "";        
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['pageTitle'] = "Settings";

        $this->load->view("settings.php",$data);
    }
    
    function mail_students()
    {
        $this->my_session->checkSession();
        $this->load->library("my_functions");
        /** initialization **/
        $data['css'] = "";        
        $data['css'] .= $this->my_functions->addCss("chosen.css");
        $data['js'] = ""; 
        $data['js'] = $this->my_functions->addJs("tiny_mce/tiny_mce.js"); 
        $data['js'] .= $this->my_functions->addJs("chosen/chosen.jquery.min.js");    
        
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['pageTitle'] = "Mailer";
        
        $data['body_template'] = "mail_users.php";
        $this->load->view("site_template.php",$data);
    }
    
    function send_mail()
    {
        $this->my_session->checkSession();
        
        $massType = $this->input->post("massType",true);
        $message = $this->input->post("message");
        $subject = $this->input->post("subject",true);
        $emails = $this->input->post("students",true);
        //var_dump($_POST);
        $this->load->library("form_validation");
        $this->form_validation->CI =& $this;
        
        $this->form_validation->set_rules("message","Message","required");
        $this->form_validation->set_rules("subject","Subject","trim|xss_clean|required");
        
        if($this->form_validation->run() == FALSE):
            $json['success'] = false;
            $json['msg'] = validation_errors();
            echo json_encode($json);
            die();
        endif;
        
        $emailAddresses = null;
        
        if($massType == "class")
        {
            $this->load->model("student_model");
            $result = $this->student_model->getAllStudents();
            if($result):
                
                foreach($result->result as $row):
                    if(filter_var($row->email, FILTER_VALIDATE_EMAIL))
                    {
                        if($emailAddresses == null):
                            $emailAddresses = "";
                            $emailAddresses .= $row->email;
                        else:    
                            $emailAddresses .= ",".$row->email;
                        endif;
                    }    
                endforeach;
            endif;
        }
        elseif($massType == "all")
        {
            if(is_array($emails)):
            foreach($emails as $key => $val):
                if(filter_var($val, FILTER_VALIDATE_EMAIL))
                {
                    if($emailAddresses == null):
                        $emailAddresses = "";
                        $emailAddresses .= $val;
                    else:    
                        $emailAddresses .= ",".$val;
                    endif;
                }
            endforeach;
            else:
                $json['success'] = false;
                $json['msg'] = "Please select a recipient.";
                echo json_encode($json);
                die();
            endif;
        }
                
        
        
        $this->load->library("my_mailer");
        $result = $this->my_mailer->sendMail($subject,$message,$emailAddresses);
        if(!$result):
            $json['success'] = false;
            $json['msg'] = "The Mail was not sent. Please try again later.";
            echo json_encode($json);
            die();
        endif;
        
        $json['success'] = true;
        $json['msg'] = "Mail has been sent successfully.";
        echo json_encode($json);
        die();
    }
    
}

?>
