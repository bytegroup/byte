<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_order extends MX_Controller {

    function __construct()
    {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */	
        $this->load->library("my_session");
        $this->my_session->checkSession();     
        $this->load->library("my_functions");
        $this->load->model("work_order_model");
		
        if(!isset($this->my_session->permissions['canViewWork_order'])){
            die("not allowed");
        }
    }
    
    function index($requisitionId){
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_Receives);
            $crud->where('requisitionId',$requisitionId);
			
            $crud->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}');
            $crud->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}');     
			
            $crud->set_subject('Work Orders');            
            
            $crud->columns('requisitionId','organizationId','quotationDate','departmentId','quotationApproved');
            $crud->display_as('requisitionId','Requisition No.')  
                 ->display_as('organizationId','Organization')
                 ->display_as('departmentId','Department')
                 ->display_as('work_orderDate','Date');
                 
            $crud->unset_add();
            $crud->unset_edit();		
            $crud->unset_delete();
            
            if(isset($this->my_session->permissions['canEditWork_order'])){                
                $crud->add_action('Edit', "", IT_MODULE_FOLDER.'quotation/edit_Work_order');
            }
            if(isset($this->my_session->permissions['canDeleteWork_order'])){                
                $crud->add_action('Delete', "", IT_MODULE_FOLDER.'quotation/delete_Work_order');            				 			
            }
            if(isset($this->my_session->permissions['canAddWork_order'])){                
                $crud->add_action('Quotation', "", IT_MODULE_FOLDER.'quotation/add_Work_order/'.$requisitionId);            				 			
            }	
            
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";
            $output->css .= $this->my_functions->addCss("js/fancybox/jquery.fancybox-1.3.4.css",true);

            $output->js = "";
            $output->js .= $this->my_functions->addJs("js/fancybox/jquery.mousewheel-3.0.4.pack.js",true);
            $output->js .= $this->my_functions->addJs("js/fancybox/jquery.fancybox-1.3.4.pack.js",true);
            $output->requisitionId = $requisitionId;
            $output->pageTitle = "Quotation List";
            $output->base_url = base_url();
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "gc_it_inventory_index.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
   	
    function add_Work_order($requisitionId)
    {
        $id = (int)$this->uri->segment(5);        
        $old_name = "";
		
        $data['css'] = "";
        $data['css'] .= $this->my_functions->addCss("grocery_crud/css/ui/simple/jquery-ui-1.8.23.custom.css",true);
        $data['js'] = "";
        $data['js'] .= $this->my_functions->addJs("js/knockout.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/knockout.validation.js",true);  

        $this->db->select('requisitionTitle,requisitionDescription,o.organizationName,d.departmentName,r.organizationId,r.departmentId')
                 ->from(TBL_REQUISITIONS.' as r ')
                 ->join(TBL_ORGANIZATIONS.' as o ','o.organizationId = r.organizationId')
                 ->join(TBL_DEPARTMENTS.' as d ','d.departmentId = r.departmentId')
                 ->where('r.requisitionId', $requisitionId);

        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonQuotationInfo'] = json_encode($result->result());
        endif;

        $this->db->select('rd.orderedQuantity,i.itemName,rd.itemMasterId')
                 ->from(TBL_REQUISITIONS_DETAIL.' as rd ')
                 ->join(TBL_ITEMS_MASTER.' as i ','rd.itemMasterId = i.itemMasterId')
                 ->where('rd.requisitionId', $requisitionId);
				 					 
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonItemMaster'] = json_encode($result->result());
        endif;
//var_dump($this->db->last_query());die();
        $result = $this->db->get(TBL_VENDORS);
        if($result->num_rows() > 0):
            $data['jsonVendor'] = json_encode($result->result());
        endif;
        $data['oldfile'] = "";
        $time = time();
        $data['requisitionId'] = $requisitionId;  
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Work Order Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '0';
        $data['editId'] = '0';
        $data['body_template'] = "work_order_detail_view.php";   
    	$this->load->view(MAIN_TEMPLATE_FILE,$data); 
    }
    function save_Work_order($editId = null){
        $postData = $this->input->post("data",true);
        $items =  (isset($postData['Items']))?$postData['Items']:null;
        $vendorId = (isset($postData['vendorId']))?$postData['vendorId']:null;
        $requisitionId = (isset($postData['requisitionId']))?$postData['requisitionId']:null;
        $description = (isset($postData['description']))?$postData['description']:null;
        $organizationId = (isset($postData['organizationId']))?$postData['organizationId']:null;
        $departmentId = (isset($postData['departmentId']))?$postData['departmentId']:null;		
        $edit = (isset($postData['edit']))?$postData['edit']:null;
        $date = date("Y-m-d H:i:s");
        $userId = $this->my_session->userId;		

        $data = array("quotationDate" =>$date, "requisitionId" => $requisitionId, "quotationDescription" => $description ,"organizationId" => $organizationId,"departmentId" => $departmentId,"vendorsId"=>$vendorId,"userId" => $userId,"quotationApproved" => false);

        if(!$edit):
            $insertId = $this->quotation_model->saveQuotation("",$data);
            if($insertId > 0):
                $json['success'] = true;
                $json['result'] = $insertId;
                $this->saveDetails($items,$insertId,$requisitionId);
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
            $insertId = $this->quotation_model->saveQuotation($editId,$data);
            if($this->db->affected_rows() >= 0):
                $json['success'] = true;
                $json['result'] = $editId;
                //echo json_encode($json);
                $this->updateDetails($items,$editId,$requisitionId);
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
    function saveDetails($details = '',$id='',$requisitionId=''){
        $detailArray = json_decode($details);
        foreach ($detailArray as $row){
            $data = array("quotationId" =>$id, "orderedQuantity" => $row->orderedQuantity, "itemMasterId" => $row->itemMasterId ,"quotationPrice" => $row->quotationPrice);
            $value = $this->quotation_model->addQuotationDetails($data,$id);
        }
        if($value['success']){
            $success['success'] = true;
            $success['result'] = $id;
            $success['url'] = base_url().IT_MODULE_FOLDER.'quotation/index/'.$requisitionId;
            echo json_encode($success);
        }
    }
    function updateDetails($details = '',$id='',$requisitionId=''){
        $detailArray = json_decode($details);
        $this->db->delete(TBL_Receives_DETAIL,array('quotationId' => $id));
        foreach ($detailArray as $row){
            $data = array("quotationId" =>$id, "orderedQuantity" => $row->orderedQuantity, "itemMasterId" => $row->itemMasterId ,"quotationPrice" => $row->quotationPrice);
            $value = $this->quotation_model->addQuotationDetails($data);
        }
        if($value['success']){
            $success['success'] = true;
            $success['result'] = $id;
            $success['url'] = base_url().IT_MODULE_FOLDER.'quotation/index/'.$requisitionId;
            echo json_encode($success);
        }
    }
    function edit_Quotation($quotationId = "")
    {
        if($quotationId==""){
            die("Requisition Does Not Exist");
        }
        $data['oldfile'] = "";
        $data['css'] = "";
        $data['css'] .= $this->my_functions->addCss("grocery_crud/css/ui/simple/jquery-ui-1.8.23.custom.css",true);
        $data['js'] = "";
        $data['js'] .= $this->my_functions->addJs("js/knockout.js",true);  
        $data['js'] .= $this->my_functions->addJs("js/knockout.validation.js",true);  

        $this->db->select('quotationDescription, o.organizationName, d.departmentName, q.organizationId, q.departmentId, q.requisitionId,q.vendorsId,q.quotationFile')
                 ->from(TBL_Receives.' as q ')
                 ->join(TBL_ORGANIZATIONS.' as o ','o.organizationId = q.organizationId')
                 ->join(TBL_DEPARTMENTS.' as d ','d.departmentId = q.departmentId')
                 ->where('q.quotationId', $quotationId);

        $result = $this->db->get();
        if($result->num_rows() > 0):
            foreach($result->result() as $row){
                $requisitionId = $row->requisitionId;
                $oldfile = $row->quotationFile;
            }
            $data['jsonQuotationInfo'] = json_encode($result->result());
            $data['oldfile'] = $oldfile;
        endif;

        $this->db->select('qd.orderedQuantity,i.itemName,qd.itemMasterId,qd.quotationPrice')
                 ->from(TBL_Receives_DETAIL.' as qd ')
                 ->join(TBL_ITEMS_MASTER.' as i ','qd.itemMasterId = i.itemMasterId')
                 ->where('qd.quotationId', $quotationId);

        $result = $this->db->get();
        if($result->num_rows() > 0):
            $data['jsonItemMaster'] = json_encode($result->result());
        endif;

        $result = $this->db->get(TBL_VENDORS);
        if($result->num_rows() > 0):
            $data['jsonVendor'] = json_encode($result->result());
        endif;

        $time = time();
        $data['requisitionId'] = $requisitionId;  
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Quotation Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '1';
        $data['editId'] = $quotationId;
        $data['body_template'] = "quotation_detail_view.php";   
    	$this->load->view(MAIN_TEMPLATE_FILE,$data);  
		
    }
	
    function delete_Quotation($id=''){
        $this->quotation_model->delete_quotation($id);
        redirect(base_url().IT_MODULE_FOLDER.'quotation');
    }
    function approve_Quotation($quotationId){
        if($quotationId <= 0):
            die("No Quotation Found");
        endif;
        $data['saveId'] = $quotationId;

        $this->db->select('*');
        $this->db->where("quotationId",$quotationId);
        $result = $this->db->get(TBL_Receives);

        if($result->num_rows()<1):
            die("No Contents Found");
        endif;
        $data['saveUrl'] = base_url().IT_MODULE_FOLDER."quotation/save_approve_Quotation/"; 
        $data['redirectUrl'] = base_url().IT_MODULE_FOLDER."quotation/index/"; 
        $data['qinfo'] = $result->result();
        foreach($result->result() as $row){
            $approve = $row->quotationApproved;
            $data['redirectId'] = $row->requisitionId; 
        }
        if($approve)
            $list = '<option value="1" selected="selected" >Approve</option>'.'<option value="0" >Not Approve</option>';
        else	
            $list = '<option value="1" >Approve</option>'.'<option selected="selected" value="0" >Not Approve</option>';
        $data['appList'] = $list; 
        $data['base_url'] = base_url();
        $this->load->view("approveQuotation.php",$data);
    }
    function save_approve_Quotation($quotationId){
        $action = $this->input->post("action",true);
        if($action == "approve"):
            $approve = $this->input->post("approve",true);
            $qid = $this->input->post("saveId",true);
            $data = array("quotationApproved" => $approve);
            $this->db->where('quotationId',$qid);
            $this->db->update(TBL_Receives,$data);
            $json['success'] = true;                    
            echo json_encode($json);
            die();
        endif;	
    }
    function upload_picture()
    {
        $json['success'] = false;        
        $action = $this->input->post("action",true);               
        
        if($action == "upload_picture")
        {
            $this->load->library("my_functions");         
            $quotationId = $this->input->post("quotationId",true);
            $reqId = $this->input->post("reqId",true);
            $old_file = $this->input->post("old_file",true);        
            /***************                  
             * UPLOAD HERE *                 
             ***************/
            $this->load->model("upload_model");
                
            $postFileName = "filename";
            $allowedExt = array('gif','jpg','jpeg','png');
                
            $upload_folder = ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER."Receives/".$reqId;//.PHOTOS_FOLDER;
            $upload_data = $this->upload_model->do_upload($postFileName, $upload_folder, $allowedExt, $maxFileSize = '1050');

            if($upload_data['success'] == false)
            {
                echo '<script>parent.showUploadSuccess('.json_encode($upload_data).')</script>';                    die();
            }
                
            $upload_data = $upload_data['upload_data'];
            /***** Run your Query to update rows ******/
            $data = array("quotationFile" => $upload_data['file_name']);
            $result = $this->db->where("quotationId",$quotationId)->update(TBL_Receives,$data);
                
            if(!$result)
            {
                @unlink($upload_folder.$upload_data['file_name']);
                $json['success'] = false;
                $json['msg'] = "There was an error during file upload.Please reload page and try again.";
                echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
                die();
            }
            else
            {
                @unlink($upload_folder."/".$old_file);
            }

            $json['success'] = true;
            $json['picture'] = $upload_data['file_name'];
            $json['msg'] = "File has been uploaded.";
            echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
            die();

        }
    }
    function upload_approvedQuotation()
    {
        $json['success'] = false;        
        $action = $this->input->post("action",true);               
        
        if($action == "upload_picture")
        {
            $this->load->library("my_functions");         
            $quotationId = $this->input->post("quotationId",true);
            $reqId = $this->input->post("reqId",true);
            $old_file = $this->input->post("old_file",true);        
            /***************                  
             * UPLOAD HERE *                 
             ***************/
            $this->load->model("upload_model");
                
            $postFileName = "filename";
            $allowedExt = array('gif','jpg','jpeg','png');

            $upload_folder = ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER."approved_Receives/".$reqId;                $upload_data = $this->upload_model->do_upload($postFileName, $upload_folder, $allowedExt, $maxFileSize = '1050');
                
            if($upload_data['success'] == false)
            {
                echo '<script>parent.showUploadSuccess('.json_encode($upload_data).')</script>';                    die();
            }
                
            $upload_data = $upload_data['upload_data'];
            $data = array("quotationFileApproved" => $upload_data['file_name']);
            $result = $this->db->where("quotationId",$quotationId)->update(TBL_Receives,$data);
                
            if(!$result)
            {
                @unlink($upload_folder.$upload_data['file_name']);
                $json['success'] = false;
                $json['msg'] = "There was an error during file upload.Please reload page and try again.";
                echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
                die();
            }
            else
            {
                @unlink($upload_folder."/".$old_file);
            }
            $json['success'] = true;
            $json['picture'] = $upload_data['file_name'];
            $json['msg'] = "File has been uploaded.";
            echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
            die();
        }
    }
}
?>