<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/30/15
 * Time: 3:33 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bill_List extends MX_Controller {
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_BILL);
            $crud->set_relation("billCheckedById", TBL_USERS, '{firstName} {middleName} {LastName}');
            $crud->set_relation("billSubmittedById", TBL_USERS, '{firstName} {middleName} {LastName}');
            $crud->set_relation("billPaymentById", TBL_USERS, '{firstName} {middleName} {LastName}');
            $crud->set_relation("budgetId", TBL_BUDGET, '{budgetHead}');
            $crud->set_relation("receiveId", TBL_RECEIVES, '{receiveNumber}');
            $crud->set_subject('Bill');

            $crud->columns('billNumber','budgetId');
            $crud->display_as('billNumber','Bill No.')
                ->display_as('budgetId','Budget Head')
                ->display_as('billingDate','Billing Date')
                ->display_as('billReceiveDate', 'Bill Receive Date')
                ->display_as('billType', 'Bill Type')
                ->display_as('billAmount', 'Bill Amount')
                ->display_as('billPaymentType', 'Payment Type')
                ->display_as('billSubmittedById', 'Bill Submitted By')
                ->display_as('billPaymentById', 'Bill Payment By')
                ->display_as('billDescription', 'Particulars')
                ->display_as('billCheckedById', 'Bill Checked By');
            $crud->add_fields('receiveId', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'billDescription', 'creatorId', 'createDate');
            $crud->edit_fields('receiveId', 'billNumber', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'billDescription', 'billPaymentById', 'editorId', 'editDate');
            $crud->unset_texteditor('billDescription', 'Particulars');
            $crud->field_type('billNumber', 'readonly');
            $crud->field_type('billPaymentById', 'readonly');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->field_type('billPaymentType', 'dropdown', array('Cash'=>'Cash', 'Cheque'=>'Cheque'));
            //$crud->callback_field('billType', array($this, 'callback_field_billType'));
            //$crud->callback_after_insert(array($this, 'callback_after_insert_bill'));
            //$crud->callback_after_update(array($this, 'callback_after_update_bill'));

            $crud->unset_add();

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Bill List";
            $output->base_url = base_url();

            $output->body_template = "bill_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    function add_Bill()
    {
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

        $result = $this->db->get(TBL_DEPARTMENTS);
        if($result->num_rows() > 0):
            $data['jsonDepartment'] = json_encode($result->result());
        endif;
        $result = $this->db->get(TBL_VENDORS);
        if($result->num_rows() > 0):
            $data['jsonVendor'] = json_encode($result->result());
        endif;
        $data['childItems'] = '';
        $this->db->select('*');
        // $this->db->where("parentId",'4');
        $result = $this->db->get(TBL_CATEGORIES);
        if($result->num_rows()>0):
            $data['childItems'] = json_encode($result->result());
        endif;
        $result = $this->db->get(TBL_BUDGET);
        if($result->num_rows()>0):
            $data['jsonBudgetHead'] = json_encode($result->result());
        endif;

        $result = $this->db->get(TBL_USERS);
        if($result->num_rows() > 0):
            $data['jsonUser'] = json_encode($result->result());
        endif;

        $data['jsonBillSubItem'] = '';

        $time = time();
        $data['jsonBill'] = '';
        $data['jsonBillDetail'] = '';
        //var_dump($data['jsonItemMaster']);die();
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Bill Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '0';
        $data['editId'] = '0';
        $data['body_template'] = "bill_detail_view.php";
        $this->load->view(MAIN_TEMPLATE_FILE,$data);
        // echo '<script> alert("test");      </script>';

    }

    function save_Bill($editId = null){
        // echo '<script> alert("test");      </script>';
        $postData = $this->input->post("data",true);
        $items =  (isset($postData['Items']))?$postData['Items']:null;
        $subItems =  (isset($postData['subItems']))?$postData['subItems']:null;
        $title = (isset($postData['title']))?$postData['title']:null;
        $description = (isset($postData['description']))?$postData['description']:null;
        $organizationId = (isset($postData['organizationId']))?$postData['organizationId']:null;
        $departmentId = (isset($postData['departmentId']))?$postData['departmentId']:null;

        $edit = (isset($postData['edit']))?$postData['edit']:null;
        $billAmount = (isset($postData['billAmount']))?$postData['billAmount']:null;
        $date = date("Y-m-d H:i:s");
        $userId = $this->my_session->userId;
        $billNo=(isset($postData['billNo']))?$postData['billNo']:null;
        $budgetId= (isset($postData['budgetId']))?$postData['budgetId']:null;
        $vendorsId= (isset($postData['vendorId']))?$postData['vendorId']:null;
        $itemMasterId= (isset($postData['itemMasterId']))?$postData['itemMasterId']:null;
        $type= (isset($postData['type']))?$postData['type']:null;
        $paymentType= (isset($postData['paymentType']))?$postData['paymentType']:null;
        $checkedById= (isset($postData['checkedById']))?$postData['checkedById']:null;
        $paidById= (isset($postData['paidById']))?$postData['paidById']:null;

        $receiveDate=(isset($postData['receiveDate']))?$postData['receiveDate']:null;
        $receiveDate= date_create($receiveDate);
        $receiveDate= date_format($receiveDate,'Y-m-d H:i:s');

        $billingDate=(isset($postData['billingDate']))?$postData['billingDate']:null;
        $billingDate= date_create($billingDate);
        $billingDate= date_format($billingDate,'Y-m-d H:i:s');

        $accSubmitDate=(isset($postData['accSubmitDate']))?$postData['accSubmitDate']:null;
        $accSubmitDate= date_create($accSubmitDate);
        $accSubmitDate= date_format($accSubmitDate,'Y-m-d H:i:s');

        $paymentDate=(isset($postData['paymentDate']))?$postData['paymentDate']:null;
        $paymentDate= date_create($paymentDate);
        $paymentDate= date_format($paymentDate,'Y-m-d H:i:s');

        $data = array("createDate" =>$date, "description" => $description ,"billAmount"=>$billAmount,
            "organizationId" => $organizationId,"departmentId" => $departmentId,"userId" => $userId,"billNo" => $billNo,
            "billAmount" => $billAmount,"budgetId" => $budgetId,"vendorsId" => $vendorsId,
            "itemMasterId" => $itemMasterId,"type" => $type,"paymentType" => $paymentType,
            "checkedById" => $checkedById,"paidById" => $paidById,"receiveDate" => $receiveDate,
            "billingDate" => $billingDate,"accSubmitDate" => $accSubmitDate,"paymentDate" => $paymentDate);

        if(!$edit):
            $insertId = $this->bill_model->saveBill("",$data);
            //$this->db->insert(TBL_BILL,$data);
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
            $insertId = $this->bill_model->saveBill($editId,$data);
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
            $row->billId = $id;
            $categoryId=intval($row->categoryId);
            $billQuantity=intval($row->billQuantity);
            $data = array("billId" =>$row->billId, "billQuantity" => $billQuantity, "categoryId" => $categoryId );
            $value = $this->bill_model->addBillDetails($data,$id);
        }
        /*foreach ($subItemsArray as $row){
            $row->billId = $id;
            $data = array("billId" =>$row->billId, "billQuantity" => $row->billQuantity, "itemMasterId" => $row->itemMasterId ,"receivedQuantity" => 0,"parentId"=>4);
            $value = $this->bill_model->addBillDetails($data,$id);
        }*/
        if($value['success']){
            $success['success'] = true;
            $success['url'] = base_url().IT_MODULE_FOLDER.'bill';
            echo json_encode($success);
        }
    }
    function updateDetails($details = '',$id='', $subItems = ''){
        $detailArray = json_decode($details);
        $subItemsArray = json_decode($subItems);
        $this->db->delete(TBL_BILL_DETAIL,array('billId' => $id));
        foreach ($detailArray as $row){
            $row->billId = $id;
            //$row->itemMasterId = $row->itemMaster->itemMasterId;
            $data = array("billId" =>$row->billId, "billQuantity" => $row->billQuantity, "categoryId" => $row->categoryId );
            $value = $this->bill_model->addBillDetails($data);
        }
        /*foreach ($subItemsArray as $row){
            $row->billId = $id;
            $data = array("billId" =>$row->billId, "billQuantity" => $row->billQuantity, "itemMasterId" => $row->itemMasterId ,"receivedQuantity" => 0,"parentId"=>4);
            $value = $this->bill_model->addBillDetails($data);
        }*/
        if($value['success']){
            $success['success'] = true;
            $success['url'] = base_url().IT_MODULE_FOLDER.'bill';
            echo json_encode($success);
        }
    }
    function edit_Bill($billId = "")
    {
        if($billId==""){
            die("Bill Does Not Exisit");
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

        $result = $this->db->get(TBL_DEPARTMENTS);
        if($result->num_rows() > 0):
            $data['jsonDepartment'] = json_encode($result->result());
        endif;

        $this->db->where("billId",$billId);
        $result = $this->db->get(TBL_BILL);
        if($result->num_rows() > 0):
            $data['jsonBill'] = json_encode($result->result());
        endif;

        $this->db->where("billId",$billId);
        $this->db->where("parentId",'0');
        $result = $this->db->get(TBL_BILL_DETAIL);
        if($result->num_rows() > 0):
            $data['jsonBillDetail'] = json_encode($result->result());
            foreach($result->result() as $row){
                $this->db->where("itemMasterId",$row->itemMasterId);
                $resultItem = $this->db->get(TBL_ITEMS_MASTER);
                $row->itemMaster = $resultItem->result();
            }
            $data['jsonBillDetail'] = json_encode($result->result());
        endif;

        $data['childItems'] = '';
        $this->db->select('*');
        $this->db->where("parentId",'4');
        $result = $this->db->get(TBL_ITEMS_MASTER);
        if($result->num_rows()>0):
            $data['childItems'] = json_encode($result->result());
        endif;

        $data['jsonBillSubItem'] = '';
        $this->db->where("billId",$billId);
        $this->db->where("parentId",'4');
        $result = $this->db->get(TBL_BILL_DETAIL);
        if($result->num_rows() > 0):
            $data['jsonBillSubItem'] = json_encode($result->result());
            foreach($result->result() as $row){
                $this->db->where("itemMasterId",$row->itemMasterId);
                $resultItem = $this->db->get(TBL_ITEMS_MASTER);
                $row->itemMaster = $resultItem->result();
            }
            $data['jsonBillSubItem'] = json_encode($result->result());
        endif;

        $time = time();

        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "Bill Detail";
        $data['base_url'] = base_url();
        $data['edit'] = '1';
        $data['editId'] = $billId;
        $data['body_template'] = "bill_detail_view.php";
        $this->load->view(MAIN_TEMPLATE_FILE,$data);

    }

    function delete_Bill($id=''){
        $this->bill_model->delete_bill($id);
        redirect(base_url().IT_MODULE_FOLDER.'bill');
    }
    function approve_Bill($billId){
        if($billId <= 0):
            die("No Bill Found");
        endif;
        $data['saveId'] = $billId;

        $this->db->select('*');
        $this->db->where("billId",$billId);
        $result = $this->db->get(TBL_BILL);
        //var_dump($this->db->last_query());die;
        if($result->num_rows()<1):
            die("No Contents Found");
        endif;
        $data['saveUrl'] = base_url().IT_MODULE_FOLDER."bill/save_approve_Bill/";
        $data['redirectUrl'] = base_url().IT_MODULE_FOLDER."bill/index/";
        $data['qinfo'] = $result->result();
        foreach($result->result() as $row){
            $approve = $row->billApproved;
            $data['redirectId'] = $row->billId;
        }
        if($approve)
            $list = '<option value="1" selected="selected" >Approve</option>'.'<option value="0" >Not Approve</option>';
        else
            $list = '<option value="1" >Approve</option>'.'<option selected="selected" value="0" >Not Approve</option>';
        $data['appList'] = $list;
        $data['base_url'] = base_url();
        $this->load->view("approve.php",$data);
    }
    function save_approve_Bill($billId){
        $action = $this->input->post("action",true);
        if($action == "approve"):
            $approve = $this->input->post("approve",true);

            $rid = $this->input->post("saveId",true);

            $data = array("billApproved" => $approve);

            $this->db->where('billId',$rid);
            $this->db->update(TBL_BILL,$data);
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