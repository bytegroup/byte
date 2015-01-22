<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends MX_Controller {

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */	
        $this->load->library("my_session");
        $this->my_session->checkSession();     
        $this->load->library("my_functions");
        $this->load->model("budget_model");
        $this->load->helper('array');
		
        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }
    
    function index(){
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_BUDGET);
            $crud->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}');
            $crud->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}');
            $crud->set_subject('Budget');
            
            $crud->columns('budgetTitle', 'budgetQuantity', 'budgetYear', 'budgetConsumedQuantity');
            $crud->display_as('budgetTitle','Title')
                 ->display_as('organizationId','Organization')
                 ->display_as('budgetCreated','Date')
                 ->display_as('budgetApproved','Approved');

            $crud->fields('budgetTitle', 'budgetQuantity', 'budgetYear', 'budgetDescription', 'companyId', 'departmentId');


            if(!isset($this->my_session->permissions['canIT-InventoryAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryView'])){
                $crud->unset_read();
            }

            //$crud->unset_edit();
            //$crud->unset_delete();

            /*if(isset($this->my_session->permissions['canAddQuotations'])){
                $crud->add_action('Add Quotation', "", IT_MODULE_FOLDER.'quotation/add_Quotation','change_action_style');
            }*/
           /* if(isset($this->my_session->permissions['canViewQuotations'])){
                $crud->add_action('View Quotation', "", IT_MODULE_FOLDER.'quotation/index','change_action_style');
            }	
            if(isset($this->my_session->permissions['canAddReceives'])){
                $crud->add_action('Receive', "", IT_MODULE_FOLDER.'receive/add_Receive');
            }*/
            
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";
            //$output->css .= $this->my_functions->addCss("js/fancybox/jquery.fancybox-1.3.4.css",true);

            $output->js = "";
            //$output->js .= $this->my_functions->addJs("js/fancybox/jquery.mousewheel-3.0.4.pack.js",true);
            //$output->js .= $this->my_functions->addJs("js/fancybox/jquery.fancybox-1.3.4.pack.js",true);
            $output->pageTitle = "Budget List";
            $output->base_url = base_url();
            
            $output->body_template = "budget_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /**
     *
     */
    function add_Budget()
    {
       // $this->load->helper('array');
        $data['css'] = "";
        //$data['css'] .= $this->my_functions->addCss("grocery_crud/themes/datatables/css/datatables.css",true);
        $data['css'] .= $this->my_functions->addCss("grocery_crud/css/ui/simple/jquery-ui-1.8.23.custom.css",true);
        $data['css'] .= $this->my_functions->addCss("css/KoGrid.css",true);
        $data['js'] = "";
        $data['js'] .= $this->my_functions->addJs("js/knockout.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/knockout.validation.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/koGrid.js",true); 
        
        $result = $this->db->get(TBL_CATEGORIES);
        if($result->num_rows() > 0):
            $data['jsonItemMaster'] = json_encode($result->result());
        endif;

        $this->db->select('*');
        $this->db->from(TBL_ORGANIZATIONS);
        $this->db->where('organizationId = '.$this->my_session->userOrganizationId);
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonOrganization'] = json_encode($result->result());
        endif;

        $result = $this->db->get(TBL_DEPARTMENTS);
        if($result->num_rows() > 0):
            $data['jsonDepartment'] = json_encode($result->result());
        endif;
        
        $data['childItems'] = '';
        $this->db->select('*');
       // $this->db->where("parentId",'4');
        $result = $this->db->get(TBL_CATEGORIES);
        if($result->num_rows()>0):
            $data['childItems'] = json_encode($result->result());
        endif;


        $data['jsonBudgetSubItem'] = '';

        $time = time();
        $data['jsonBudget'] = '';
        $data['jsonBudgetDetail'] = '';


        //var_dump($data['jsonItemMaster']);die();
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Budget Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '0';
        $data['editId'] = '0';
        $data['cancel_issue']= base_url().IT_MODULE_FOLDER.'budget';
        $data['body_template'] = "budget_detail_view.php";
    	$this->load->view(MAIN_TEMPLATE_FILE,$data);
       // echo '<script> alert("test");      </script>';

    }

    function save_Budget($editId = null){
       // echo '<script> alert("test");      </script>';
        $postData = $this->input->post("data",true);
        $items =  (isset($postData['Items']))?$postData['Items']:null;
        $subItems =  (isset($postData['subItems']))?$postData['subItems']:null;
        $title = (isset($postData['title']))?$postData['title']:null;
        $description = (isset($postData['description']))?$postData['description']:null;
        $organizationId = (isset($postData['organizationId']))?$postData['organizationId']:null;
        $departmentId = (isset($postData['departmentId']))?$postData['departmentId']:null;
        $budgetYear = (isset($postData['budgetYear']))?$postData['budgetYear']:null;
        $edit = (isset($postData['edit']))?$postData['edit']:null;
        $budgetQuantity = (isset($postData['budgetQuantity']))?$postData['budgetQuantity']:null;
        $date = date("Y-m-d H:i:s");
        $userId = $this->my_session->userId;

        $data = array("createDate" =>$date, "budgetTitle" => $title, "budgetDescription" => $description ,"budgetQuantity"=>$budgetQuantity,
            "organizationId" => $organizationId,"departmentId" => $departmentId,"userId" => $userId,"budgetYear" => $budgetYear);

        if(!$edit):
            $insertId = $this->budget_model->saveBudget("",$data);
            //$this->db->insert(TBL_BUDGET,$data);
            if($insertId > 0):
                $json['success'] = true;
                $json['result'] = $insertId;
                //echo json_encode($json);
                //$this->saveDetails($items,$insertId,$subItems);
                die();
            else:
                $json['success'] = false;
                $json['result'] = false;
                echo json_encode($json);
                die();
            endif;
        endif;

       if($edit):
            if($editId<1)die("Error in Update. Try Later.");
            $insertId = $this->budget_model->saveBudget($editId,$data);
            if($this->db->affected_rows() >= 0):
                $json['success'] = true;
                $json['result'] = array();
                //echo json_encode($json);
                $this->updateDetails($items,$editId,$subItems);
                die();
            else:
                $json['success'] = false;
                $json['result'] = false;
                echo json_encode($json);
                die();
            endif; 
        endif;
    	die();
    }
    function saveDetails($details = '',$id='', $subItems = ''){
        $detailArray = json_decode($details);
        $subItemsArray = json_decode($subItems);
        foreach ($detailArray as $row){
            //$row->itemMasterId = $row->itemMaster->itemMasterId;
            $row->budgetId = $id;
            $categoryId=intval($row->categoryId);
            $budgetQuantity=intval($row->budgetQuantity);
            $data = array("budgetId" =>$row->budgetId, "budgetQuantity" => $budgetQuantity, "categoryId" => $categoryId );
            $value = $this->budget_model->addBudgetDetails($data,$id);
        }
        /*foreach ($subItemsArray as $row){
            $row->budgetId = $id;
            $data = array("budgetId" =>$row->budgetId, "budgetQuantity" => $row->budgetQuantity, "itemMasterId" => $row->itemMasterId ,"receivedQuantity" => 0,"parentId"=>4);
            $value = $this->budget_model->addBudgetDetails($data,$id);
        }*/
        if($value['success']){
            $success['success'] = true;
            $success['url'] = base_url().IT_MODULE_FOLDER.'budget';
            echo json_encode($success);
        }
    }
    function updateDetails($details = '',$id='', $subItems = ''){
        $detailArray = json_decode($details);
        $subItemsArray = json_decode($subItems);
        $this->db->delete(TBL_BUDGET_DETAIL,array('budgetId' => $id));
        foreach ($detailArray as $row){
            $row->budgetId = $id;
            //$row->itemMasterId = $row->itemMaster->itemMasterId;
            $data = array("budgetId" =>$row->budgetId, "budgetQuantity" => $row->budgetQuantity, "categoryId" => $row->categoryId );
            $value = $this->budget_model->addBudgetDetails($data);
        }
        /*foreach ($subItemsArray as $row){
            $row->budgetId = $id;
            $data = array("budgetId" =>$row->budgetId, "budgetQuantity" => $row->budgetQuantity, "itemMasterId" => $row->itemMasterId ,"receivedQuantity" => 0,"parentId"=>4);
            $value = $this->budget_model->addBudgetDetails($data);
        }*/
        if($value['success']){
            $success['success'] = true;
            $success['url'] = base_url().IT_MODULE_FOLDER.'budget';
            echo json_encode($success);
        }
    }
    function edit_Budget($budgetId = "")
    {
        if($budgetId==""){
            die("Budget Does Not Exisit");
        }

        $data['css'] = "";
        $data['css'] .= $this->my_functions->addCss("grocery_crud/css/ui/simple/jquery-ui-1.8.23.custom.css",true);
        $data['css'] .= $this->my_functions->addCss("css/KoGrid.css",true);
        $data['js'] = "";
        $data['js'] .= $this->my_functions->addJs("js/knockout.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/knockout.validation.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/koGrid.js",true); 
        
        $result = $this->db->get(TBL_ITEMS_MASTER);
        if($result->num_rows() > 0):
            $data['jsonItemMaster'] = json_encode($result->result());
        endif;

        $this->db->select('*');
        $this->db->from(TBL_ORGANIZATIONS);
        $this->db->where('organizationId = '.$this->my_session->userOrganizationId);
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonOrganization'] = json_encode($result->result());
        endif;

        $this->db->select('*')
                 ->from(TBL_DEPARTMENTS)
                ->where('organizationId = '.$this->my_session->userOrganizationId);
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonDepartment'] = json_encode($result->result());
        endif;

        $this->db->where("budgetId",$budgetId);
        $result = $this->db->get(TBL_BUDGET);
        if($result->num_rows() > 0):
            $data['jsonBudget'] = json_encode($result->result());
        endif;

        $this->db->where("budgetId",$budgetId);
        //$this->db->where("parentId",'0');
        $result = $this->db->get(TBL_BUDGET_DETAIL);
        $data['jsonBudgetDetail']="test";
        if($result->num_rows() > 0):
            $data['jsonBudgetDetail'] = json_encode($result->result());
            foreach($result->result() as $row){
                $this->db->where("itemMasterId",$row->itemMasterId);
                $resultItem = $this->db->get(TBL_ITEMS_MASTER);
                $row->itemMaster = $resultItem->result();
            }
            $data['jsonBudgetDetail'] = json_encode($result->result());
        endif;
        
        $data['childItems'] = '';
        $this->db->select('*');
        //$this->db->where("parentId",'4');
        $result = $this->db->get(TBL_ITEMS_MASTER);
        if($result->num_rows()>0):
            $data['childItems'] = json_encode($result->result());
        endif;
        
        $data['jsonBudgetSubItem'] = '';
        $this->db->where("budgetId",$budgetId);
        //$this->db->where("parentId",'4');
        $result = $this->db->get(TBL_BUDGET_DETAIL);
        if($result->num_rows() > 0):
            $data['jsonBudgetSubItem'] = json_encode($result->result());
            foreach($result->result() as $row){
                $this->db->where("itemMasterId",$row->itemMasterId);
                $resultItem = $this->db->get(TBL_ITEMS_MASTER);
                $row->itemMaster = $resultItem->result();
            }
            $data['jsonBudgetSubItem'] = json_encode($result->result());
        endif;
        
        $time = time();
            
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Budget Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '1';
        $data['editId'] = $budgetId;
        $data['cancel_issue']= base_url().IT_MODULE_FOLDER.'budget';
        $data['body_template'] = "budget_detail_view.php";
    	$this->load->view(MAIN_TEMPLATE_FILE,$data); 
		
    }
	
    function delete_Budget($id=''){
        $this->budget_model->delete_budget($id);
        redirect(base_url().IT_MODULE_FOLDER.'budget');
    }
    function approve_Budget($budgetId){
        if($budgetId <= 0):
            die("No Budget Found");
        endif;
        $data['saveId'] = $budgetId;
        
        $this->db->select('*');
        $this->db->where("budgetId",$budgetId);
        $result = $this->db->get(TBL_BUDGET);
		//var_dump($this->db->last_query());die;
        if($result->num_rows()<1):
            die("No Contents Found");
        endif;
        $data['saveUrl'] = base_url().IT_MODULE_FOLDER."budget/save_approve_Budget/";
        $data['redirectUrl'] = base_url().IT_MODULE_FOLDER."budget/index/";
        $data['qinfo'] = $result->result();
        foreach($result->result() as $row){
            $approve = $row->budgetApproved;
            $data['redirectId'] = $row->budgetId;
        }
        if($approve)
            $list = '<option value="1" selected="selected" >Approve</option>'.'<option value="0" >Not Approve</option>';
        else	
        $list = '<option value="1" >Approve</option>'.'<option selected="selected" value="0" >Not Approve</option>';
        $data['appList'] = $list; 
        $data['base_url'] = base_url();
        $this->load->view("approve.php",$data);
    }
    function save_approve_Budget($budgetId){
        $action = $this->input->post("action",true);
        if($action == "approve"):
            $approve = $this->input->post("approve",true);

            $rid = $this->input->post("saveId",true);

            $data = array("budgetApproved" => $approve);

            $this->db->where('budgetId',$rid);
            $this->db->update(TBL_BUDGET,$data);
            $json['success'] = true;                    
            echo json_encode($json);
            die();
        endif;	
    }
    function get_Childitems($itemId){
        //$itemId = $this->input->post("itemId",true);
        $this->db->select('*');
        $this->db->where("parentId",$itemId);
        $result = $this->db->get(TBL_ITEMS_MASTER);
        if($result->num_rows()>0):
            $json['success'] = true;
            $json['childItems'] = $result->result();
            echo json_encode($json);
            die();
        endif;
        $json['success'] = false;                    
        echo json_encode($json);
        die();
    }
}
?>