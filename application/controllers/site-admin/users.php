<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 11/26/14
 * Time: 7:17 PM
 */

class Users extends MX_Controller {

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Admin'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            //$this->load->library('grocery_CRUD');
            $this->load->library('grocery_CRUD');
            //$this->load->library('grocery_CRUD_extended');

            /* This is only for the autocompletion */
            $crud = new grocery_CRUD($this);
            //$crud = new grocery_CRUD_extended($this);

            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_USERS)
                ->set_relation("adminId", TBL_ADMINS, "{userName}")
                ->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}')
                ->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}')
                ->set_relation("designationId", TBL_DESIGNATIONS, '{designationName}')
                ->set_subject('User');

            $crud->columns('adminId','fullName','organizationId','departmentId','designationId')
                ->display_as('adminId','User ID')
                ->display_as('fullName', 'Full_Name')
                ->display_as('organizationId', 'Org. Name')
                ->display_as('departmentId', 'Dept. Name')
                ->display_as('designationId', 'desig. Name')
                ->callback_column('fullName', array($this, 'getFullName'))
                ->callback_column('permissions', array($this, 'getPermissions'));

            $crud->unset_add()->unset_edit()->unset_delete()->unset_read();

            if(isset($this->my_session->permissions['canSetAdminPermissions'])) {
                $crud->add_action('Permissions', '', '','ui-icon-key',array($this,'setPermissionURL'));
            }

            /*if(!isset($this->my_session->permissions['canAdminAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canAdminEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canAdminDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canAdminView'])){
                $crud->unset_read();
            }*/

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Admin User Panel";
            $output->base_url = base_url();
            $output->cancel=base_url().ADMIN_FOLDER.'users';

            $output->body_template = "users_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/

    /**
     * @param $primary_key
     * @param $data
     * @return string
     */
    function getPermissions($primary_key, $data){
        $permissions='';
        $this->db->select('permissions');
        $this->db->from(TBL_ADMINS);
        $this->db->where('adminId', $data->adminId);
        $result=$this->db->get();
        if($result->num_rows()>0){
            $rows=$result->result();
            foreach ($rows as $row) {
                $permissions.=$row->permissions;
            }
            return $permissions;
        }
        return "";
    }

    /**
     * @param $primary_key
     * @param $value
     * @return string
     */
    function setPermissionURL($primary_key, $value){
        if($value->adminId)
            return base_url().ADMIN_FOLDER.'permissions/index/'.$primary_key.'/edit/'.$value->adminId;
        else return base_url().ADMIN_FOLDER.'permissions/index/'.$primary_key.'/add';
    }

    /**
     * @param $name
     * @param $row
     * @return string
     */
    function getFullName($name, $row){
        if(isset($row->middleName)) return $row->firstName." ".$row->middleName." ".$row->lastName;
        else return $row->firstName." ".$row->lastName;
    }
}
?>