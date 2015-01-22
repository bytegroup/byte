<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of session
 * Manages User Session Data
 * @author user
 */

Class My_session {
    const sessionTimeOut = 7200; //2 hours inactivity will lead to logout.
    
    var $logged_in;
    var $userName;
    var $sessionId;
    var $cookieId;
    var $sessionToken;
    var $lastActivity;
    var $time;
    var $userInfo = array();
    var $permissions = array();
    var $userId = null;
    var $userGroupId = null;
    var $userOrganizationId = null;
	
    function __construct() {        
		session_save_path(ABS_SERVER_PATH.SITE_FOLDER."sessions_files");
        ini_set('session.gc_probability', 1);
        
        $this->time = time();
        session_start();
        
        /** get CI instances for ci library usage **/
        $CI =& get_instance();        
        
        $checklogin = $this->check_login();
        
    }
    
    private function check_login(){
        
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['session_userName']) 
           && $_SESSION['session_userName'] != "Guest" )
        {   
            if(!$this->checkSessionValidity($_SESSION['session_lastActivity'], $this->time))
            {                
                $this->unsetSessionVars();
                return false;
                
            }
            
            $this->setSessionVars();            
            return true;
        }
        else
        {            
            $this->unsetSessionVars();                        
            return false;
        }
        
        return false;
    }
    
    private function checkSessionValidity($lastactivity,$timeNow)
    {
        $sessiontime = $timeNow - $lastactivity;        
        if($sessiontime > My_session::sessionTimeOut):             
            return false;
        endif;
            return true;
    }
    
     private function setSessionVars($userinfo = null){
        
        if($userinfo != null):
            $this->permissions = $_SESSION['permissions'] = $this->createPermissionArray($userinfo['permissions']);        
            $this->userName = $_SESSION['session_userName'] = $userinfo['userName'];
            $this->userId = $_SESSION['session_userId'] = $userinfo['userId'];
            //$this->userGroupId = $_SESSION['session_userGroupId'] = $userinfo['groupId'];
			//$this->userOrganizationId = $_SESSION['session_organizationId'] = $userinfo['groupId'];
        else:
            $this->permissions = $_SESSION['permissions'];// = (isset($_SESSION['permisssions']))? $_SESSION['permissions']:"";
            $this->userName = $_SESSION['session_userName'];
            $this->userId = $_SESSION['session_userId'];
            //$this->userGroupId = $_SESSION['session_userGroupId'];
			//$this->userOrganizationId = $_SESSION['session_organizationId'];
        endif;
        
        $this->logged_in = $_SESSION['logged_in'] = TRUE;        
        //$this->sessionToken = $_SESSION['session_Token'] = md5(uniqid());
        $this->lastActivity = $_SESSION['session_lastActivity'] = $this->time;
        
    }
    
    private function unsetSessionVars(){
      $this->logged_in = $_SESSION['logged_in'] = FALSE;
      $this->userName = $_SESSION['session_userName'] = "Guest";
      $this->permissions = null;
      $this->userId = null;
      $this->userGroupId = null;
      //$this->sessionToken = null;
      $this->lastActivity = null;
      
      unset($_SESSION['session_organizationId']);
      unset($_SESSION['session_userGroupId']);
      unset($_SESSION['session_userId']);
      unset($_SESSION['session_userName']);
      unset($_SESSION['userInfo']);
      unset($_SESSION['permissions']);
      //unset($_SESSION['session_Token']);
      unset($_SESSION['session_lastActivity']);
    }
    
    private function createPermissionArray($parray)
    {
        $pstring = explode(";",$parray);
        
        foreach($pstring as $key => $val)
        {
            $_SESSION['permissions'][$val] = $val;
        }
        //array_pop($_SESSION['permissions']);
        return $_SESSION['permissions'];   
    }
    
    public function log_in($user_name, $password, $organizationId = null){
        $CI =& get_instance();     
        $CI->load->model("users_model");
        $result = $CI->users_model->checkUser($user_name, $password, $organizationId);
        
        if(!$result)
        {
            $this->setLoginError();
            return false;
        }
        else
        {   
            $this->unSetLoginError();
            $row = $result->row_array();
			$this->setSessionVars($row);
            return true;
        }
        
        return false;
    }
    
    public function log_out()
    {   
        $this->unsetSessionVars();      

        session_unset();
        session_destroy();
    }
    
    public function checkSession($page = null){
        $CI =& get_instance(); 
        if($page == null){
            $page = $CI->config->item('base_url').ADMIN_FOLDER."home";
        }
        if(!$this->logged_in){
            redirect($page);
            die();
        }
    }
    
    public function setLoginError()
    {
        $_SESSION['login_error'] = "Incorrect Login Details";
    }
    
    public function getLoginError()
    {
        if(array_key_exists("login_error", $_SESSION)){
            return $_SESSION['login_error'];
        }
        return "";
    } 
    
    public function unSetLoginError()
    {
        if(array_key_exists("login_error", $_SESSION))
        {
            unset($_SESSION['login_error']);
        }        
    }
    function get_modules($permissions){
        $modules=array();
        foreach($permissions as $key=>$val){
            if(substr($key, 0,6)=='HEADER'){
                $modules[end(explode('_',$key))]=str_replace("-", " ", end(explode('_',$key)));
            }
        }
        return $modules;
    }
}

?>