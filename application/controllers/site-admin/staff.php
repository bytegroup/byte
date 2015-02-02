<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 11/26/14
 * Time: 7:17 PM
 */

class Staff extends MX_Controller {
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Staff'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $this->load->library('grocery_CRUD');
            //$this->load->library('grocery_CRUD_extended');
            $this->load->library('ajax_grocery_crud');

            //$crud = new grocery_CRUD($this);
            //$crud = new grocery_CRUD_extended($this);
            $crud = new ajax_grocery_CRUD();

            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_USERS);
            $crud->set_relation("companyId", TBL_COMPANIES, '{companyName}')
                 ->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}')
                 ->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}')
                 ->set_relation("designationId", TBL_DESIGNATIONS, '{designationName}')
                //->set_relation_dependency('companyId','organizationId','organizationId')
                //->set_relation_dependency('departmentId', 'companyId', 'companyId')
                 ->set_subject('Employee');

            $crud->columns('userId','fullName','email','mobile','companyId');
            $crud->callback_column('fullName', array($this, 'getFullName'));
            $crud->display_as('userId','No.')
                ->display_as('companyId', 'Company')
                ->display_as('organizationId','Organization')
                ->display_as('departmentId','Department')
                ->display_as('email','Email')
                ->display_as('fullName', 'Full Name')
                ->display_as('mobile', 'Mobile')
                ->display_as('dob','Date of Birth')
                ->display_as("designationId", "Designation")
                ->display_as("employeeId", "Employee ID")
                ->display_as("cdob", "Certificate Date Of Birth")
                ->display_as("extNo", "Ext. of TNT")
                ->display_as("personalMobile", "Personal Mobile")
                ->display_as("confirmationDate", "Confirmation Date")
                ->display_as("lastPromotionDate", "Last Promotion Date")
                ->display_as("basicSalary", "Basic Salary")
                ->display_as("grossSalary", "Gross Salary")
                ->display_as("resignationDate", "Resignation Date")
                ->display_as("lastYearRating", "Last Year Rating")
                ->display_as("employeeType", "Employee Type");

            $crud->add_fields('firstName','middleName','lastName', 'employeeId','email','dob', 'cdob','gender','phone', 'extNo','mobile', 'personalMobile', 'bloodGroup','joiningDate', 'confirmationDate', 'lastPromotionDate', 'resignationDate','education', 'basicSalary', 'grossSalary', 'lastYearRating', 'employeeType','presentAddress','permanentAddress','organizationId','companyId','departmentId','designationId','active','profilePicture','createDate', 'createUserId','ip');
            $crud->edit_fields('firstName','middleName','lastName', 'employeeId','email','dob', 'cdob','gender','phone', 'extNo','mobile', 'personalMobile', 'bloodGroup','joiningDate', 'confirmationDate', 'lastPromotionDate', 'resignationDate','education', 'basicSalary', 'grossSalary', 'lastYearRating', 'employeeType', 'presentAddress','permanentAddress','organizationId','companyId','departmentId','designationId','active','profilePicture', 'editDate', 'editorId','ip');
            $crud->required_fields(array('gender','mobile','firstName','lastName','organizationId','companyId','departmentId', 'designationId'));

            $crud->display_as('groupId', 'Group ID')
                ->display_as('firstName','First Name')
                ->display_as('lastName','Last Name')
                ->display_as('middleName','Middle Name')
                ->display_as('phone','TNT No')
                ->display_as('ip','IP')
                ->display_as('bloodGroup','Blood Group')
                ->display_as('joiningDate','Joining Date')
                ->display_as('profilePicture', 'Profile Picture')
                ->display_as('presentAddress','Present Address')
                ->display_as('permanentAddress','Permanent Address')
                ->display_as('userRole', 'Role');

            $crud->field_type('presentAddress', 'text');
            $crud->unset_texteditor('presentAddress');
            $crud->field_type('permanentAddress', 'text');
            $crud->unset_texteditor('permanentAddress');
            $crud->set_field_upload('profilePicture','assets/uploads/profile_img');
            $crud->field_type('createDate','hidden', $time);
            $crud->field_type('createUserId','hidden', $this->my_session->userId);
            $crud->field_type('editDate','hidden', $time);
            $crud->field_type('editorId','hidden', $this->my_session->userId);
            $crud->field_type('ip','hidden',$_SERVER['REMOTE_ADDR']);
            $crud->field_type('employeeType','dropdown', array( "PERMANENT"  => "PERMANENT", "PROVISION" => "PROVISION", "CASUAL"=>"CASUAL"));
            $crud->field_type('userRole','hidden', 'Stuff');
            $crud->callback_add_field('active',array($this,'add_field_callback_active'));
            $crud->callback_edit_field('active',array($this,'add_field_callback_active'));
            //$crud->callback_add_field('userRole',array($this,'add_field_callback_userRole'));
            //$crud->callback_edit_field('userRole',array($this,'add_field_callback_userRole'));
            $crud->callback_before_upload(array($this,'test_callback_before_upload'));

            $crud->unique_fields('userName','email');
            $crud->set_rules('userName','User Name','trim|min_length[3]|xss_clean');
            $crud->set_rules('email','Email','trim|valid_email');
            $crud->set_rules('mobile', 'Mobile no','numeric');
            //$crud->set_rules('phone','TNT no','numeric');

            //$crud->callback_before_insert(array($this,'convertPassword'));

            if(!isset($this->my_session->permissions['canStaffAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canStaffEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canStaffDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canStaffView'])){
                $crud->unset_read();
            }

            $output = $crud->render();

            $dd_data = array(
                'dd_state' =>  $crud->getState(),
                'dd_dropdowns' => array('organizationId','departmentId'),
                'dd_url' => array('', base_url().ADMIN_FOLDER.'staff/get_departments/'),
                'dd_ajax_loader' => base_url().'ajax-loader.gif'
            );
            $output->dd_state = $dd_data['dd_state'];
            $output->dd_dropdowns = $dd_data['dd_dropdowns'];
            $output->dd_url = $dd_data['dd_url'];
            $output->dd_ajax_loader = $dd_data['dd_ajax_loader'];

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Employee Management";
            //die($output->base_url);
            $output->base_url = base_url();
            $output->cancel=base_url().ADMIN_FOLDER.'staff';

            $output->body_template = "staff_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
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
    function get_departments()
    {
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

    function ajax_get_company($sourceId){
        if($sourceId <=0 ){echo json_encode(null);exit;}
        $this->db->select("companyId, companyName")
            ->from(TBL_COMPANIES)
            ->where('organizationId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach($db->result() as $row):
            $array[] = array("value" => $row->companyId, "property" => $row->companyName);
        endforeach;
        echo json_encode($array);
        exit;
    }

    function ajax_get_department($sourceId){
        if($sourceId <=0 ){echo json_encode(null);exit;}
        $this->db->select("departmentId, departmentName")
            ->from(TBL_DEPARTMENTS)
            ->where('companyId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach($db->result() as $row):
            $array[] = array("value" => $row->departmentId, "property" => $row->departmentName);
        endforeach;
        echo json_encode($array);
        exit;
    }


    /*****************************/
    /*** call back functions ***/
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
}
?>