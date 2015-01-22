<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *
 * @author USER
 */
Class User_group extends CI_Controller {
    function __construct() {        
        parent::__construct();                
        $this->load->library("my_session");
        $this->my_session->checkSession();        
        
        if(!isset($this->my_session->permissions['canViewUserGroup'])){
            die("not allowed");
        }
        
        $this->load->library('grocery_CRUD');
    }
    
    function index()
    {       
        try{
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD();
            $crud->unset_jquery();

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_USER_GROUP);            
            $crud->set_subject('User Group');
            
            $crud->required_fields(array('groupName','userLevel'));
            
            $crud->set_rules('groupName', 'Group Name', 'trim|required|xss_clean|callback_checkGroupName');  
            $crud->set_rules('userLevel', 'User Level', 'trim|xss_clean|required');            
            
            $crud->columns('groupName','userLevel');
            $crud->display_as('groupName','Group Name')
                    ->display_as("userLevel", "User Level");
            
            $time = time();
            $crud->add_fields('groupName','userLevel','creationDtTm','updateDtTm');
            $crud->edit_fields('groupName','userLevel','updateDtTm');
            
            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);           
            
            $crud->callback_add_field('userLevel', array($this, 'add_field_callback_level'));
            $crud->callback_edit_field('userLevel', array($this, 'add_field_callback_level_selected'));
            
            if(!isset($this->my_session->permissions['canAddUserGroup'])){                
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canEditUserGroup'])){                
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canDeleteUserGroup'])){                
                $crud->unset_delete();
            }
            $crud->unset_delete();
                        
            if(isset($this->my_session->permissions['canSetUserGroupPermission'])){
                $crud->add_action("Set Permission", "", base_url().ADMIN_FOLDER.'user_group/set_permission/');
            }
            
            $output = $crud->render();
            
            /** initialization **/
            $output->css = "";        
            $output->js = "";        
            $output->pageTitle = "User Groups";
            $output->base_url = base_url();
            $output->body_template = "user_group_index.php";
            
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    function add_field_callback_level(){
        $this->load->library("dropdown");
        $dropdown = new Dropdown();
        $dlist = $dropdown->printUserLevel();
        $o = '<select name="userLevel">'.$dlist.'</select>';
        return $o;

    }
    
    function add_field_callback_level_selected(){
        $id = (int)$this->uri->segment(5);
        
        $result = $this->db->where("groupId",$id)->get(TBL_USER_GROUP);
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            
            $this->load->library("dropdown");
            $dropdown = new Dropdown();
            $dlist = $dropdown->printUserLevel($row['userLevel']);
            $o = '<select name="userLevel">'.$dlist.'</select>';
            return $o;
        }
        else
        {
            redirect(base_url().ADMIN_FOLDER."users");
        }
        
    }
    
    /*****************************/
    /**** permission settings ****/
    /*****************************/

    /**
     * @param $ugid - user group ID
     */
    function set_permission($ugid)
    {
        //$ugid = $this->uri->segment(4);
        $this->load->model("ug_model");        
        $result = $this->ug_model->getUGinfo($ugid);
        
        $data['uginfo'] = $result->row();
        
        $this->load->library("user_group_permission");
        
        $ug_permission = $this->user_group_permission->convert_permission_to_array($data['uginfo']->permissions);
        
        $data['permission_checkboxes'] = $this->user_group_permission->getPermissionCheckBoxes($ug_permission);
        
        /** initialization **/
        $data['css'] = "";              
        
        $data['js'] = "";                
        
        $data['pageTitle'] = "User Group Permissions";
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['cancel_update']=base_url().ADMIN_FOLDER.'user_group';
        
        $data['body_template'] = "ug_permission_view.php";
        
        $this->load->view(MAIN_TEMPLATE_FILE,$data);
    }
    
    function update_permission()
    {
        $action = $this->input->post("action", true);
        if($action == "edit_permissions"):
            $ugid = $this->input->post("ugid", true);
            $permission = $this->input->post("permissions", true);
            
            $this->load->library("user_group_permission");
        
            $permission_string = $this->user_group_permission->convert_permission_to_string($permission);
            
            $this->load->model("ug_model");        
            $result = $this->ug_model->updatePermission($ugid,$permission_string);
            
            $this->load->library("menu_helper");
            $this->menu_helper->makeUserGroupMenu($ugid);
            
            if($this->input->is_ajax_request()):
                $json['success'] = true;
                $json['msg'] = "Permissions Updated.";
                echo json_encode($json);
            else:
                redirect(base_url().ADMIN_FOLDER."user_group");
            endif;
            
        endif;
        
    }
    
    
}

?>
