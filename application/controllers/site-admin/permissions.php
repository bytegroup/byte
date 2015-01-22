<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/5/14
 * Time: 12:43 PM
 */

class Permissions extends MX_Controller {
    var $id=0;
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['canSetAdminPermissions'])){
            die("not allowed");
        }
    }

    function index($id=0){
        $this->id=$id;
        if(!$id){
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        }
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_ADMINS)
                ->set_relation("adminId", TBL_USERS, "{firstName} {middleName} {lastName}")
                ->set_subject('Permissions');

            $crud->unset_list()->unset_delete()->unset_read()->unset_export()->unset_print()->unset_back_to_list();

            $crud->required_fields(array('userName', 'password'))
                ->field_type('createDate', 'hidden', $time)
                ->field_type('creatorId','hidden', $this->my_session->userId)
                ->field_type('editDate', 'hidden', $time)
                ->field_type('editorId', 'hidden', $this->my_session->userId)
                ->callback_field('permissions', array($this,'callback_permission_checkboxes'));
            $crud->unique_fields('userName');

            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url().ADMIN_FOLDER.'users";'
                .'</script>'
                .'<div style="display:none">'
                );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url().ADMIN_FOLDER.'users";'
                .'</script>'
                .'<div style="display:none">'
            );

            $crud->callback_after_insert(array($this, 'updateUserTable'));

            $output = $crud->render();
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Set User Permissions";
            $output->base_url = base_url();
            $output->cancel=base_url().ADMIN_FOLDER.'users';

            $output->body_template = "permissions_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions  ***/
    /*****************************/
    function updateUserTable($post_array, $primary_key){
        $this->db->where('userId', $this->id);
        $this->db->update(TBL_USERS, array('adminId'=>$primary_key));
    }

    function getFullName($name, $row){
        if(isset($row->middleName)) return $row->firstName." ".$row->middleName." ".$row->lastName;
        else return $row->firstName." ".$row->lastName;
    }

    function callback_permission_checkboxes($chkBoxes){
        $this->db->select('permissions')
            ->from(TBL_USERS)
            ->join(TBL_ADMINS, TBL_USERS.'.adminId = '.TBL_ADMINS.'.adminId')
            ->where(TBL_USERS.'.userId', $this->id);
        $query=$this->db->get();
        $result=$query->row();
        $this->load->library('permissions_library');
        $checked= $query->num_rows() ? $this->permissions_library->convert_permission_to_array($result->permissions): null;
        $boxes = $this->permissions_library->getPermissionCheckboxes($checked);
        return $boxes;
    }

    /******************************************************************************************/
}