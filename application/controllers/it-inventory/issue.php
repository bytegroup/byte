<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issue extends MX_Controller {
    var $stockId=0;
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

    function index($stockId=0){
        if(!$stockId){
            redirect(base_url().IT_MODULE_FOLDER.'stock');
            die();
        }
        $this->stockId=$stockId;
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_ISSUES);
            $crud->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}');
            $crud->set_relation("issueUserId", TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->where('stockId', $stockId);
            $crud->set_subject('Issue');

            $crud->columns('issueTo','issueDate','issueQuantity');
            $crud->display_as('issueDate','Date')
                ->display_as('issueTo','Issue To')
                ->display_as('departmentId','Department')
                ->display_as('issueUserId', 'Employee')
                ->display_as('issueDescription', 'Description')
                ->display_as('stockQuantity', 'Stock Quantity')
                ->display_as('issueQuantity','Issue Quantity');

            $crud->add_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items');
            $crud->required_fields(array('issueTo', 'issueDate', 'issueQuantity'));
            $crud->unset_texteditor('issueDescription');
            $crud->field_type('stockId', 'hidden', $stockId);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('company', array($this, 'callback_field_company'));
            $crud->callback_read_field('company', array($this, 'callback_read_field_company'));
            $crud->callback_field('issueTo', array($this, 'callback_field_issueTo'));
            $crud->callback_field('stockQuantity', array($this, 'callback_field_stockQuantity'));
            $crud->callback_read_field('stockQuantity', array($this, 'callback_read_field_stockQuantity'));
            $crud->callback_field('items', array($this, 'callback_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_issue'));
            $crud->callback_after_update(array($this, 'callback_after_update_issue'));
            $crud->callback_after_delete(array($this, 'callback_after_delete_issue'));

            if($this->is_stock_empty($stockId))$crud->unset_add();

            $output = $crud->render();

            $currentIssueId= isset($crud->getStateInfo()->primary_key)? $crud->getStateInfo()->primary_key: 0;
            $output->issuedItems=json_encode($this->get_issue_items_by_stock_id($stockId, $currentIssueId));
            $output->toBeIssuedItems=json_encode($this->get_issue_items_by_stock_id($stockId));
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->stockId=$stockId;
            $output->issueHeader= $this->get_issue_item_header($stockId);

            $output->pageTitle = "Issue";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "issue_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_company($row, $key){
        return $this->get_company_name_by_stock_id($this->stockId);
    }
    function callback_read_field_company($row, $key){
        return $this->get_company_name_by_stock_id($this->stockId);
    }
    function callback_field_issueTo($value, $key){
        if(!$value)$value='';
        $checkComp= $value==='Company'?'checked':'';
        $checkDpt = $value==='Department'?'checked':'';
        $checkEmp = $value==='Employee'?'checked':'';
        $html='';
        $html .= '<input type="radio" name="issueTo" '.$checkComp.' value="Company"/> Company';
        $html .= '<input type="radio" name="issueTo" '.$checkDpt.' value="Department"/> Department';
        $html .= '<input type="radio" name="issueTo" '.$checkEmp.' value="Employee"/> Employee';
        return $html;
    }
    function callback_field_stockQuantity($row, $key){
        return $this->get_stock_quantity($this->stockId);
    }
    function callback_read_field_stockQuantity($row, $key){
        return $this->get_stock_quantity($this->stockId);
    }
    function callback_field_items($row, $key){
        $html = '';
        return $html;
    }
    function callback_after_insert_issue($post, $key){
        $issuedItems= $post['selectedItems'];

        foreach($issuedItems as $id):
            $this->db->update(TBL_RECEIVES_DETAIL, array('issueId'=>$key), array('receiveDetailId'=>$id));
        endforeach;

        $this->db->where('stockId', $this->stockId);
        $this->db->set('stockQuantity', 'stockQuantity - '.$post['issueQuantity'], FALSE);
        $this->db->set('issueQuantity', 'issueQuantity + '.$post['issueQuantity'], FALSE);
        $this->db->update(TBL_STOCK);
    }
    function callback_after_update_issue($post, $key){
        $issuedItems= $post['selectedItems'];

        $this->db->update(TBL_RECEIVES_DETAIL, array('issueId'=>0), array('issueId'=>$key));
        $preIssueQty= $this->db->affected_rows();
        $currentIssueQty= $post['issueQuantity'];

        foreach($issuedItems as $id):
            $this->db->update(TBL_RECEIVES_DETAIL, array('issueId'=>$key), array('receiveDetailId'=>$id));
        endforeach;

        $qtyDeff=abs($preIssueQty-$currentIssueQty);
        if($preIssueQty > $currentIssueQty){
            $this->db->where('stockId', $this->stockId);
            $this->db->set('stockQuantity', 'stockQuantity + '.$qtyDeff, FALSE);
            $this->db->set('issueQuantity', 'issueQuantity - '.$qtyDeff, FALSE);
            $this->db->update(TBL_STOCK);
        }
        else if ($preIssueQty < $currentIssueQty){
            $this->db->where('stockId', $this->stockId);
            $this->db->set('stockQuantity', 'stockQuantity - '.$qtyDeff, FALSE);
            $this->db->set('issueQuantity', 'issueQuantity + '.$qtyDeff, FALSE);
            $this->db->update(TBL_STOCK);
        }
        else{}
    }
    function callback_after_delete_issue($key){
        $this->db->update(TBL_RECEIVES_DETAIL, array('issueId'=>0), array('issueId'=>$key));
        $qty= $this->db->affected_rows();

        $this->db->where('stockId', $this->stockId);
        $this->db->set('stockQuantity', 'stockQuantity + '.$qty, FALSE);
        $this->db->set('issueQuantity', 'issueQuantity - '.$qty, FALSE);
        $this->db->update(TBL_STOCK);
    }

    /*****************************/
    /***  ajax call functions  ***/
    /*****************************/
    function ajax_get_department($stockId, $sourceId){
        $this->db->select("d.departmentId, d.departmentName");
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 's.companyId=d.companyId');
        $this->db->where('stockId', $stockId);
        $db = $this->db->get();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->departmentId, "property" => $row->departmentName);
        endforeach;
        $test= array(
            array("value"=>'1', "property"=>$db->num_rows())
        );
        echo json_encode($array);
        exit;
    }
    function ajax_get_employee($stockId, $sourceId){
        $this->db->select("u.userId, u.firstName, u.middleName, u.lastName");
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_USERS.' as u ', 's.companyId=u.companyId');
        $this->db->where('stockId', $stockId);
        if($sourceId)$this->db->where('departmentId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->userId, "property" => $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName);
        endforeach;
        $test= array(
            array("value"=>'1', "property"=>$db->num_rows())
        );
        echo json_encode($array);
        exit;
    }

    /******************************************************************************/
    function get_company_name_by_stock_id($stockId){
        if(!$stockId)return array();
        $this->db->select('c.companyId, c.companyName');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 's.companyId=c.companyId');
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        return $db->result()[0]->companyName;
    }
    function get_stock_quantity($stockId){
        if(!$stockId)return array();
        $this->db->select('stockQuantity');
        $this->db->from(TBL_STOCK);
        $this->db->where('stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        return $db->result()[0]->stockQuantity;
    }
    function get_issue_item_header($stockId){
        if(!$stockId)return array();
        $this->db->select('s.stockNumber, i.itemName, c.categoryName');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 's.itemMasterId=i.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'i.categoryId=c.categoryId');
        $this->db->where('stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        $array= array();
        return $db->result()[0];
    }
    function get_issue_items_by_stock_id($stockId, $issueId=0){
        if(!$stockId)return array();
        $this->db->select('rd.receiveDetailId, v.vendorsName, rd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 's.itemMasterId=rd.itemMasterId and rd.issueId='.$issueId.' and rd.damageId=0');
        $this->db->join(TBL_RECEIVES.' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('s.stockId', $stockId);
        //$this->db->where('rd.issueId', 0);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('issuedId'=>$row->receiveDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate);
        endforeach;
        return $array;
    }
    function is_stock_empty($stockId=0){
        if(!$stockId)return false;
        if(!$this->get_stock_quantity($stockId))return true;
        else return false;
    }
}
?>