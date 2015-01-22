<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/4/14
 * Time: 5:57 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

        if(!isset($this->my_session->permissions['canViewUser'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            //$this->load->library('grocery_CRUD');
            $this->load->library('grocery_CRUD');
            $this->load->library('ajax_grocery_CRUD');

            /* This is only for the autocompletion */
            //$crud = new grocery_CRUD($this);
            $crud = new ajax_grocery_CRUD();

            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_model('custom_query_model');
            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_ADMINS);
            $crud->basic_model->set_query_str(
                "SELECT a.*, u.userId, u.firstName, u.middleName, u.lastName, o.organizationName, d.departmentName, de.designationName"
                ." FROM ocl_admins a "
                ."LEFT JOIN ocl_users u ON a.userId = u.userId "
                ."LEFT JOIN ocl_organizations o ON o.organizationId = u.organizationId "
                ."LEFT JOIN ocl_departments d ON d.departmentId = u.departmentId "
                ."LEFT JOIN ocl_designations de on de.designationId= u.designationId"
            );
            $crud->set_subject('User')
                ->set_relation_dependency('departmentId','organizationId','organizationId');
            /*$crud->set_relation("userId", TBL_USERS, '{firstName} {middleName} {lastName}')
                ->set_relation('organizationId',TBL_USERS,'{organizationName}')
                ->set_relation_n_n("Organization",TBL_USERS, TBL_ORGANIZATIONS,'organizationId','organizationId', '{organizationName}')
                ->set_relation_n_n("Department",TBL_USERS, TBL_DEPARTMENTS,'departmentId1','departmentId', '{departmentName}')
                //->set_relation_n_n("Designation",TBL_USERS, TBL_DESIGNATIONS,'designationId','designationId', '{designationName}')
                //->set_relation_dependency('departmentId','organizationId','organizationId')
                //->set_relation_dependency('userId','departmentId','departmentId')
                ->set_subject('User');*/

            $crud->columns('userName','fullName','designationName', 'departmentName', 'organizationName');
            //$crud->callback_column('fullName', array($this, 'getFullName'));
            $crud->callback_column('fullName', array($this, 'getFullName'));
            $crud->display_as('fullName','Name')
                ->display_as('userId','Full Name')
                ->display_as('userName', 'User Name')
                ->display_as('organizationName','Organization')
                ->display_as('departmentName','Department')
                ->display_as("designationName", "Designation");

            $crud->unset_read();
            //$crud->fields('userName','password','organizationName','departmentName','designationName','userId','createDate', 'creatorId');
            $crud->fields('userName','password','userId','selectUser','createDate', 'creatorId');
            $crud->required_fields(array('userName', 'password', 'userId'));

            $crud->unset_texteditor('permissions');
            $crud->field_type('userId','hidden','');
            $crud->field_type('selectUser','readonly','');
            $crud->field_type('editDate','hidden', $time);
            $crud->field_type('editorId','hidden', $this->my_session->userId);
            $crud->field_type('createDate','hidden', $time);
            $crud->field_type('creatorId','hidden', $this->my_session->userId);
            $crud->field_type('ip','hidden',$_SERVER['REMOTE_ADDR']);
            //$crud->field_type('organizationId', 'dropdown', array());
            //$crud->field_type('userId', 'dropdown', array());
            //$crud->field_type('userRole','multiselect', array( "Admin"  => "Admin", "User" => "User"));
            //$crud->field_type('userRole','hidden', 'Stuff');
            //$crud->callback_add_field('selectUser',array($this,'add_field_callback_selectUser'));
            //$crud->callback_add_field('active',array($this,'add_field_callback_active'));
            //$crud->callback_edit_field('active',array($this,'add_field_callback_active'));
            //$crud->callback_add_field('userRole',array($this,'add_field_callback_userRole'));
            //$crud->callback_edit_field('userRole',array($this,'add_field_callback_userRole'));
            //$crud->callback_edit_field('fullName',array($this,'edit_field_callback_fullName'));
            //$crud->callback_before_upload(array($this,'test_callback_before_upload'));

            $crud->unique_fields('userName', 'userId');
            $crud->set_rules('userName','User Name','trim|min_length[3]|xss_clean');
            //$crud->set_rules('email','Email','trim|valid_email');

            /*if(isset($this->my_session->permissions['canEditUser'])){
                $crud->add_action("Details",'',  base_url().ADMIN_FOLDER.'stuff/testDetails/');
            }*/

            //$crud->callback_before_insert(array($this,'convertPassword'));
            $this->my_session->permissions['canAddUser'];
            if(!isset($this->my_session->permissions['canAddUser'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canEditUser'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canDeleteUser'])){
                $crud->unset_delete();
            }
            $crud->add_action("Details",'',  base_url().ADMIN_FOLDER.'users/test_list/');
            $crud->callback_column('departmentId', array($this, '_callback_popTest'));

            $output = $crud->render();

            $dd_data = array(
                'dd_state' =>  $crud->getState(),
                'dd_dropdowns' => array('departmentId', 'selectUser'),
                //'dd_url' => array('', base_url().ADMIN_FOLDER.'users/get_departments/'),
                'dd_url' => array('', base_url().ADMIN_FOLDER.'users/get_users/'),
                'dd_ajax_loader' => base_url().'ajax-loader.gif'
            );
            $output->dd_state = $dd_data['dd_state'];
            $output->dd_dropdowns = $dd_data['dd_dropdowns'];
            $output->dd_url = $dd_data['dd_url'];
            $output->dd_ajax_loader = $dd_data['dd_ajax_loader'];

            $output->pop_fieldName= 'userId';

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Manage User";
            //die($output->base_url);
            $output->base_url = base_url();
            $output->cancel=base_url().ADMIN_FOLDER.'users';

            $output->body_template = "users_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    //FANCYBOX TEST
    public function _callback_popTest($value, $row)
    {
        return "<a href='http://www.grocerycrud.com/assets/themes/default/images/logo.png' class='fancybox'>$value</a>";
    }

    function getFullName($name, $row){
        if(isset($row->middleName)) return $row->firstName." ".$row->middleName." ".$row->lastName;
        else return $row->firstName." ".$row->lastName;
    }

    function test_callback_before_upload($files_to_upload, $field_info){
        foreach($files_to_upload as $value) {
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
        }
        $allowed_formats = array("jpg","gif", "png");
        if(in_array($ext,$allowed_formats)) return true;
        else return 'Wrong file format';
    }

    //GET JSON OF STATES
    function get_departments(){
        $organizationId = $this->uri->segment(4);
        $this->db->select("*")
            ->from(TBL_DEPARTMENTS)
            ->where('organizationId', $organizationId);
        $db = $this->db->get();

        $array = array();
        foreach($db->result() as $row):
            $array[] = array("value" => $row->departmentId, "property" => $row->departmentName);
        endforeach;

        echo json_encode($array);
        exit;
    }
    function get_users($id){
        //$organizationId = $this->uri->segment(4);
        $departmentId=$this->uri->segment(4);
        /*$this->db->select("u.userId, u.firstName, u.middleName, u.lastName")
            ->select("des.designationName")
            ->from( TBL_USERS." as u")
            ->from(TBL_ORGANIZATIONS." as o")
            ->from(TBL_DEPARTMENTS." as d")
            ->from(TBL_DESIGNATIONS." as des")
            ->where("u.departmentId",2)
            ->where("o.organizationId","u.organizationId")
            ->where( "d.departmentId", "u.departmentId")
            ->where("des.designationId", "u.designationId");
        $db = $this->db->get();*/

        $db= $this->db->query(
            "select u.userId, u.firstName, u.middleName, u.lastName, de.designationName "
            ." from ".TBL_USERS." u, "
            .TBL_ORGANIZATIONS." o, "
            .TBL_DEPARTMENTS." d, "
            .TBL_DESIGNATIONS." de "
            ." where u.departmentId= ".$id." AND o.organizationId=u.organizationId AND d.departmentId=u.departmentId AND de.designationId=u.designationId"
        );
        $array = array();
        foreach($db->result() as $row):
            $fullName= $row->middleName ?
                $row->firstName." ".$row->middleName." ".$row->lastName
                : $row->firstName." ".$row->lastName;
            $array[] = array("value" => $row->userId, "property" => $fullName."  >>  ". $row->designationName);
        endforeach;

        echo json_encode($array);
        exit;
    }

    /*****************************/
    /*** call back validations ***/
    /*****************************/
    function add_field_callback_active($active){
        if($active=='Inactive')
            return ' <input type="radio" name="active" value="Active" /> Active
                 <input type="radio" name="active" value="Inactive" checked="true"/> Inactive';
        else
            return ' <input type="radio" name="active" value="Active" checked="true"/> Active
                 <input type="radio" name="active" value="Inactive" /> Inactive';
    }

    function add_field_callback_userRole($userRole){
        return ' <input type="checkbox" name="userRole[]" value="Admin" /> Admin
                 <input type="checkbox" name="userRole[]" value="User" /> User';
    }
    function edit_field_callback_fullName($value, $userId){
        $this->db->select('*')
            ->from(TBL_USERS)
            ->where('userId', $userId);
        $db = $this->db->get();
        $user=array();
        foreach($db->result() as $row):
            $user = array("firstName" => $row->firstName, "middleName" => $row->middleName, "lastName" => $row->lastName);
        endforeach;
        return '<input type="text" name="fullName" readonly value="'.$user['firstName'].'" />';

    }
    function add_field_callback_selectUser(){

    }

    function convertPassword($post_array){
        $post_array['password'] = md5($post_array['password']);
        return $post_array;
    }

    public function test_list() {
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_USERS);
            $crud->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}')
                ->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}')
                ->set_relation("designationId", TBL_DESIGNATIONS, '{designationName}')
                ->set_subject('Stuffs List');
            $crud->unset_add()
                ->unset_edit()
                ->unset_read()
                ->unset_delete();
            $crud->columns('userId','fullName','designationName', 'departmentName', 'organizationName');
            $crud->callback_column('fullName', array($this, 'getFullName'));

            $crud->add_action('Select', '#');

            $output = $crud->render();
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Manage User";
            //die($output->base_url);
            $output->base_url = base_url();

            $output->body_template = "test_list_view.php";
            $this->load->view('popup_layout.php',$output);
            //$this->load->view(MAIN_TEMPLATE_FILE,$output);
        } catch(Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }


    function checkUserName($str){

        $id = (int)$this->uri->segment(5);
        $old_name = "";
        $result = null;

        if(!empty($id) && is_numeric($id) && $id > 0 )
        {
            $this->db->where("userId", $id);
            $result = $this->db->get(TBL_USERS);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->userName;
                }

                $this->db->where_not_in("userName", array($old_name));
                $this->db->where_not_in("userId", array($id));
                $this->db->where("userName", $str);
                $num_rows = $this->db->get(TBL_USERS)->num_rows();

                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkUserName","The %s already Exist. Please try a different user name.");
                    return false;
                }
            }
        }
        else
        {
            $this->db->where("userName", $str);
            $num_rows = $this->db->get(TBL_USERS)->num_rows();

            if($num_rows > 0)
            {
                $this->form_validation->set_message("checkUserName","The %s already Exist. Please try a different user name.");
                return false;
            }
        }
    }

    function checkEmail($str){

        $id = (int)$this->uri->segment(5);
        $old_name = "";
        $result = null;

        if(!empty($id) && is_numeric($id) && $id > 0 )
        {
            $this->db->where("userId", $id);
            $result = $this->db->get(TBL_USERS);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->email;
                }

                $this->db->where_not_in("email",array($old_name));
                $this->db->where_not_in("userId", array($id));
                $this->db->where("email", $str);
                $num_rows = $this->db->get(TBL_USERS)->num_rows();

                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkEmail","The %s already Exist. Please try a different email.");
                    return false;
                }
            }
        }
        else
        {
            $this->db->where("email", $str);
            $num_rows = $this->db->get(TBL_USERS)->num_rows();

            if($num_rows > 0)
            {
                $this->form_validation->set_message("checkEmail","The %s already Exist. Please try a different email.");
                return false;
            }
        }
    }


    function other_info()
    {
        $userid = (int)$this->uri->segment(4);

        if($userid <= 0):
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        endif;

        $this->load->model("users_model");
        $result = $this->users_model->getUserInfoById($userid);

        if(!$result)
        {
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        }

        $data['uinfo'] = $result->row();

        $this->load->library("my_functions");
        /** initialization **/
        $data['css'] = "";
        $data['css'] .= $this->my_functions->addCss("grocery_crud/themes/datatables/css/datatables.css",true);
        $data['css'] .= $this->my_functions->addCss("themes/base/jquery.ui.all.css");

        $data['js'] = "";

        $data['js_footer'] = "";
        $data['js_footer'] .= $this->my_functions->addJs("ui/jquery.ui.core.js");
        $data['js_footer'] .= $this->my_functions->addJs("ui/jquery.ui.widget.js");
        $data['js_footer'] .= $this->my_functions->addJs("ui/jquery.ui.datepicker.js");
        $data['js_footer'] .= $this->my_functions->addJs("ui/jquery.ui.tabs.js");

        $data['pageTitle'] = "User Information";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "user_other_info.php";

        $this->load->view(MAIN_TEMPLATE_FILE,$data);

    }

    function user_info()
    {
        $userid = (int)$this->uri->segment(4);

        if($userid <= 0):
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        endif;

        $this->load->model("users_model");
        $result = $this->users_model->getUserInfoById($userid);

        if(!$result)
        {
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        }

        $data['uinfo'] = $result->row();

        $data['base_url'] = base_url();

        $this->load->view(SITE_TEMPLATES."user_info.php",$data);
    }

    function update_userinfo()
    {
        $action = $this->input->post("action",true);
        $json = array();
        if(!$action):
            if($this->input->is_ajax_request()):
                $json['msg'] = "There was a problem. Please try again later.";
                echo json_encode($json);
                die();
            else:
                redirect(base_url().'users');
            endif;
        endif;
//        die($action);

        if($action == "update_userinfo")
        {
            /*$fathername = $this->input->post("fathername",true);
            $mothername = $this->input->post("mothername",true);
            $nationalId = $this->input->post("nationalid",true);
            $city = $this->input->post("city",true);
            $zip = $this->input->post("zip",true);
			$cellphone = $this->input->post("cellphone",true);*/

            $address = $this->input->post("address",true);
            $phone = $this->input->post("phone",true);
            $email = $this->input->post("email",true);
            $userid = (int)$this->input->post("userid");

            $this->load->library("form_validation");
            $this->form_validation->set_rules("userid","User Id", "trim|xss_clean|greater_than[0]");

            if($this->form_validation->run() == FALSE)
            {
                $json['success'] = false;
                $json['msg'] = validation_errors();
                echo json_encode($json);
                die();
            }

            $time = time();
//            $data = array("fatherName" => $fathername, "motherName" => $mothername, "nationalId" => $nationalId, "address" => $address,"city" => $city, "zip" => $zip, "phone" => $phone, "cellPhone" => $cellphone, "updateDtTm" => $time);
            $data = array("address" => $address,"phone" => $phone, "updateDtTm" => $time);
            $this->load->model("users_model");
            $result = $this->users_model->updateUserOtherInfo($data, $userid);

            if($result)
            {
                if($this->input->is_ajax_request()):
                    $json['success'] = true;
                    $json['msg'] = "User Information has been updated.";
                    echo json_encode($json);
                    die();
                else:
                    redirect(base_url().ADMIN_FOLDER.'users/other_info/'.$userid);
                endif;
            }
            else
            {
                $json['success'] = false;
                $json['msg'] = "There was a problem. Please try again later.";
                echo json_encode($json);
                die();
            }
        }
    }

    function user_picture()
    {
        $userid = (int)$this->uri->segment(4);

        if($userid <= 0):
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        endif;

        $this->load->model("users_model");
        $result = $this->users_model->getUserInfoById($userid);

        if(!$result)
        {
            redirect(base_url().ADMIN_FOLDER.'users');
            die();
        }

        $data['uinfo'] = $result->row();

        $data['base_url'] = base_url();

        $this->load->view(SITE_TEMPLATES."user_picture.php",$data);

    }

    function upload_picture()
    {
        $json['success'] = false;
        $action = $this->input->post("action",true);

        if($action == "upload_picture")
        {
            $this->load->library("my_functions");
            $this->load->library("form_validation");

            $userid = $this->input->post("userid",true);
            $oldpic = $this->input->post("old_picture",true);
            $this->form_validation->set_rules('userid', 'User Id', 'trim|xssclean|required');

            if ($this->form_validation->run() == FALSE)
            {
                $json['msg'] = validation_errors();
                $json['success'] = false;
                echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>';
                die();
            }
            else
            {
                /***************
                 * UPLOAD HERE *
                 **************/
                $this->load->model("upload_model");

                $postFileName = "filename";
                $allowedExt = array('gif','jpg','jpeg','png');

                $upload_folder = ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER.PHOTOS_FOLDER;
                $upload_data = $this->upload_model->do_upload($postFileName, $upload_folder, $allowedExt, $maxFileSize = '1050');

                if($upload_data['success'] == false)
                {
                    echo '<script>parent.showUploadSuccess('.json_encode($upload_data).')</script>';
                    die();
                }

                $upload_data = $upload_data['upload_data'];
                /***** Run your Query to update rows ******/
                $data = array("profilePicture" => $upload_data['file_name']);
                $result = $this->db->where("userId",$userid)->update(TBL_USERS,$data);

                if(!$result)
                {
                    @unlink($upload_folder.$upload_data['file_name']);
                    $json['success'] = false;
                    $json['msg'] = "There was an error during photo upload.Please reload page and try again.";
                    echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>';
                    die();
                }
                else
                {
                    @unlink($upload_folder.$oldpic);
                }

                /***************
                 * Resize HERE *
                 **************/

                $resize = true;
                if($resize)
                {
                    $width = 200;
                    $height = 300;
                    $result = $this->upload_model->resize_image($upload_data['file_name'],$upload_folder,$creatThumb = false,$width,$height);

                    if($result['success']== false):
                        $json['success'] = true;
                        $json['resize_success'] = false;
                        $json['msg'] = "File Uploaded but could not be resized.";
                        echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>';
                        die();
                    endif;

                    $json['success'] = true;
                    $json['picture'] = $upload_data['file_name'];
                    $json['msg'] = "File has been uploaded.";
                    echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>';
                    die();

                }

            }
        }
    }



}
?>