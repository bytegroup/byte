<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departments extends MX_Controller {
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
            $this->load->library('grocery_CRUD');
            //$this->load->library('ajax_grocery_CRUD');
            $crud = new grocery_CRUD($this);
            //$crud = new ajax_grocery_CRUD();
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_DEPARTMENTS);
            $crud->set_relation("companyId", TBL_COMPANIES, '{companyName}');
            //$crud->set_relation('userId', TBL_USERS, 'userId');
            $crud->set_relation('organizationId', TBL_ORGANIZATIONS, '{organizationName}');
            //$crud->set_relation_n_n('organizationId',TBL_COMPANIES, TBL_ORGANIZATIONS, 'organizationId', 'organizationId', 'organizationName');
            $crud->set_subject('Department');

            $crud->columns('departmentName','HODUserId','departmentPhone', 'departmentEmail', 'companyId');
            $crud->callback_column('HODUserId', array($this, 'callback_column_HOD'));
            $crud->display_as('departmentName','Dept. Name')
                ->display_as('HODUserId', 'Head of Dept.')
                ->display_as('departmentEmail', 'Email')
                ->display_as('departmentPhone', 'Phone')
                ->display_as('departmentCode','Code')
                ->display_as('sectionName', 'Section Name')
                ->display_as('departmentDescription', 'Description')
                ->display_as('companyId','Company')
                ->display_as('organizationId', 'Organization');

            $this -> db -> select('userId, firstName, middleName, lastName, companyName');
            $this -> db -> from(TBL_USERS);
            $this -> db -> join(TBL_COMPANIES, TBL_USERS.'.companyId='.TBL_COMPANIES.'.companyId');
            $query = $this -> db -> get();
            $HOD_array = array();
            foreach ($query->result() as $row) {
                $HOD_array[$row->userId]= $row->firstName." ".$row->middleName." ".$row->lastName.' ('.$row->companyName.')';
            }

            $crud->add_fields('organizationId','companyId','departmentName','HODUserId','departmentCode', 'sectionName','departmentPhone', 'departmentEmail','departmentDescription', 'active', 'creatorId', 'createDate');
            $crud->edit_fields('organizationId','companyId','departmentName','HODUserId','departmentCode', 'sectionName','departmentPhone', 'departmentEmail', 'departmentDescription', 'active', 'editorId', 'editDate');
            $crud->required_fields(array('organizationId','departmentName','companyId','departmentEmail','departmentCode','departmentPhone'));
            $crud->unique_fields('departmentName', 'departmentEmail','departmentCode');
            $crud->set_rules("departmentEmail", "Email", "trim|valid_email");
            $crud->set_rules('departmentPhone','Phone','trim|xss_clean|numeric');
            $crud->unset_texteditor('departmentDescription');
            $crud->field_type('HODUserId', 'dropdown',  $HOD_array);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_add_field('active',array($this,'add_field_callback_active'));
            $crud->callback_edit_field('active',array($this,'edit_field_callback_active'));

            if(!isset($this->my_session->permissions['canAdminAdd'])){
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
            }
            
            $output = $crud->render();

            $dependency=array(
                '1' => array(
                    'URL'     => base_url().ADMIN_FOLDER.'departments/get_company/',
                    'sourceId'=> 'organizationId',
                    'targetId'=> 'companyId'
                )
            );

            $output->ajaxLoaderURL=base_url().'ajax-loader.gif';
            $output->dependency= $dependency;
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Departments";
            $output->base_url = base_url();
            
            $output->body_template = "departments_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function add_field_callback_active(){
        return ' <input type="radio" name="active" value="Active" checked="true"/> Active
        <input type="radio" name="active" value="Inactive" /> Inactive';
    }
    function edit_field_callback_active($active){
        if($active=='Inactive')
            return ' <input type="radio" name="active" value="Active" /> Active
                 <input type="radio" name="active" value="Inactive" checked="true"/> Inactive';
        else
            return ' <input type="radio" name="active" value="Active" checked="true"/> Active
                 <input type="radio" name="active" value="Inactive" /> Inactive';
    }
    function callback_column_HOD($key, $row){
        $this -> db -> select('userId, firstName, middleName, lastName');
        $this -> db -> from(TBL_USERS);
        $this ->db -> where('userId', $row->HODUserId);
        $query = $this -> db -> get();
        foreach ($query->result() as $row) {
            return $row->firstName." ".$row->middleName." ".$row->lastName;
        }
    }

    function get_company($organizationId){
        if(!$organizationId){echo json_encode(array()); exit;}
        $this->db->select("companyId, companyName")
            ->from(TBL_COMPANIES)
            ->where('organizationId', $organizationId);
        $db = $this->db->get();

        $array = array();
        if(!$db->num_rows()){echo json_encode(array()); exit;}
        foreach($db->result() as $row):
            $array[] = array("value" => $row->companyId, "property" => $row->companyName);
        endforeach;
        echo json_encode($array);
        exit;
    }

    function get_HOD($companyId){
        if(!$companyId){echo json_encode(array()); exit;}
        $this->db->select("userId, firstName, middleName, lastName, companyName")
            ->from(TBL_USERS)
            ->join(TBL_COMPANIES, TBL_USERS.'.companyId='.TBL_COMPANIES.'.companyId')
            ->where(TBL_USERS.'.companyId', $companyId);
        $db = $this->db->get();

        $array = array();
        if(!$db->num_rows()){echo json_encode(array()); exit;}
        foreach($db->result() as $row):
            $array[] = array("value" => $row->userId, "property" => $row->firstName." ".$row->middleName." ".$row->lastName.' ('.$row->companyName.')');
        endforeach;

        echo json_encode($array);
        exit;
    }
}