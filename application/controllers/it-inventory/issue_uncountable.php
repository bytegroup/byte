<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/17/15
 * Time: 6:07 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issue_Uncountable extends MX_Controller {
    var $stockId=0;
    var $isCountable=true;
    var $preIssuedQty= 0;
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model('issue_model', 'issueModel');
        $this->load->model('it_inventory_model', 'itModel');
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
        $this->isCountable= $this->itModel->isCountableStock($stockId);

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
            $crud->callback_add_field('items', array($this, 'callback_add_field_items'));
            $crud->callback_edit_field('items', array($this, 'callback_edit_field_items'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_issue'));
            $crud->callback_before_update(array($this, 'callback_before_update_issue'));
            $crud->callback_after_update(array($this, 'callback_after_update_issue'));
            $crud->callback_after_delete(array($this, 'callback_after_delete_issue'));

            if($this->is_stock_empty($stockId))$crud->unset_add();

            $output = $crud->render();

            $currentIssueId= isset($crud->getStateInfo()->primary_key)? $crud->getStateInfo()->primary_key: 0;
            //$output->issuedItems=json_encode($this->issueModel->get_issued_uncountable_stock($stockId, $currentIssueId));
            //$output->toBeIssuedItems=json_encode($this->issueModel->get_uncountable_stock($stockId));
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->stockId=$stockId;
            $output->isCountable=$this->isCountable;
            $output->issueHeader= $this->issueModel->get_issue_item_header($stockId);

            $output->pageTitle = "Issue";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "issue_uncountable_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_company($row, $key){
        return $this->issueModel->get_company_name_by_stock_id($this->stockId);
    }
    function callback_read_field_company($row, $key){
        return $this->issueModel->get_company_name_by_stock_id($this->stockId);
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
        return $this->issueModel->get_stock_quantity($this->stockId);
    }
    function callback_read_field_stockQuantity($row, $key){
        return $this->issueModel->get_stock_quantity($this->stockId);
    }
    function callback_add_field_items($row, $key){
        $stockItems=$this->issueModel->get_uncountable_stock($this->stockId);
        $issuedItems= $this->issueModel->get_uncountable_issued_items_qty($this->stockId);
        $damagedItems= $this->issueModel->get_uncountable_stockDamaged_items_qty($this->stockId);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Issue Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($stockItems as $item){
            $issueQty= isset($issuedItems[$item['issuedId']])?$issuedItems[$item['issuedId']]:0;
            $damageQty= isset($damagedItems[$item['issuedId']])?$damagedItems[$item['issuedId']]:0;
            $remQty= $item['recQty']-($issueQty+$damageQty);
            if(!$remQty)continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" id="items-'.$item['issuedId'].'" name="selectedItems[]" value="'.$item['issuedId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['issuedId'].'">'.$remQty.'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['issuedId'].'" name="qty[]" min="0" max="'.$remQty.'" value=""/></li>';
            $html .= '<li>'.$item['warranty'] .'</li>';
            $html .= '<li>'.$item['vendor'] .'</li>';
            $html .= '<input type="hidden" name="issuedIds[]" value="'.$item['issuedId'].'"/>';
            $html .= '</ul>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    function callback_edit_field_items($row, $key){
        $stockItems=$this->issueModel->get_uncountable_stock($this->stockId, $key);
        $issuedItems= $this->issueModel->get_uncountable_issued_items_qty($this->stockId, $key);
        $totalIssuedItems= $this->issueModel->get_uncountable_issued_items_qty($this->stockId);
        $damagedItems= $this->issueModel->get_uncountable_stockDamaged_items_qty($this->stockId);
        $issueDamagedItems= $this->issueModel->get_uncountable_issueDamaged_items_qty($this->stockId, $key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Issue Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($stockItems as $item){
            $issueQty= isset($issuedItems[$item['issuedId']])?$issuedItems[$item['issuedId']]:0;
            $totalIssuedQty= isset($totalIssuedItems[$item['issuedId']])?$totalIssuedItems[$item['issuedId']]:0;
            $damageQty= isset($damagedItems[$item['issuedId']])?$damagedItems[$item['issuedId']]:0;
            $issueDamagedQty=isset($issueDamagedItems[$item['issuedId']])?$issueDamagedItems[$item['issuedId']]:0;
            $remQty= $item['recQty']-($totalIssuedQty+$damageQty);
            if(!$remQty && !($issueQty-$issueDamagedQty))continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" id="items-'.$item['issuedId'].'" name="selectedItems[]" value="'.$item['issuedId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['issuedId'].'">'.$remQty.'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['issuedId'].'" name="qty[]" min="0" max="'.($remQty+($issueQty-$issueDamagedQty)).'" value="'.($issueQty-$issueDamagedQty).'"/></li>';
            $html .= '<li>'.$item['warranty'] .'</li>';
            $html .= '<li>'.$item['vendor'] .'</li>';
            $html .= '<input type="hidden" name="issuedIds[]" value="'.$item['issuedId'].'"/>';
            $html .= '</ul>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    function callback_read_field_items($row, $key){
        $stockItems=$this->issueModel->get_uncountable_stock($this->stockId, $key);
        $issuedItems= $this->issueModel->get_uncountable_issued_items_qty($this->stockId, $key);
        $totalIssuedItems= $this->issueModel->get_uncountable_issued_items_qty($this->stockId);
        $damagedItems= $this->issueModel->get_uncountable_stockDamaged_items_qty($this->stockId);
        $issueDamagedItems= $this->issueModel->get_uncountable_issueDamaged_items_qty($this->stockId, $key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Issue Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($stockItems as $item){
            if(!isset($issuedItems[$item['issuedId']]))continue;
            $issueQty= isset($issuedItems[$item['issuedId']])?$issuedItems[$item['issuedId']]:0;
            $totalIssuedQty= isset($totalIssuedItems[$item['issuedId']])?$totalIssuedItems[$item['issuedId']]:0;
            $damageQty= isset($damagedItems[$item['issuedId']])?$damagedItems[$item['issuedId']]:0;
            $issueDamagedQty=isset($issueDamagedItems[$item['issuedId']])?$issueDamagedItems[$item['issuedId']]:0;
            $remQty= $item['recQty']-($totalIssuedQty+$damageQty);
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$remQty.'</li>';
            $html .= '<li>'.($issueQty-$issueDamagedQty).'</li>';
            $html .= '<li>'.$item['warranty'] .'</li>';
            $html .= '<li>'.$item['vendor'] .'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    function callback_after_insert_issue($post, $key){
        $issuedItems= $post['selectedItems'];
        foreach($issuedItems as $index=>$id):
            $this->db->insert(
                TBL_ISSUE_UNCOUNTABLE_DETAIL,
                array('issueId'=>$key, 'stockDetailId'=>$id, 'issueQuantity'=>$post['qty'][$index])
            );
        endforeach;

        $this->db->where('stockId', $this->stockId);
        $this->db->set('stockQuantity', 'stockQuantity - '.$post['issueQuantity'], FALSE);
        $this->db->set('issueQuantity', 'issueQuantity + '.$post['issueQuantity'], FALSE);
        $this->db->update(TBL_STOCK);
    }
    function callback_before_update_issue($post, $key){

    }
    function callback_after_update_issue($post, $key){
        $issuedItems= $post['selectedItems'];
        $preIssueQty= $post['preIssueQty'];
        $this->db->delete(TBL_ISSUE_UNCOUNTABLE_DETAIL, array('issueId'=>$key));

        foreach($issuedItems as $index=>$id):
            $this->db->insert(
                TBL_ISSUE_UNCOUNTABLE_DETAIL,
                array('issueId'=>$key, 'stockDetailId'=>$id, 'issueQuantity'=>$post['qty'][$index])
            );
        endforeach;

        $currentIssueQty= $post['issueQuantity'];

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
    function is_stock_empty($stockId=0){
        if(!$stockId)return false;
        if(!$this->issueModel->get_stock_quantity($stockId))return true;
        else return false;
    }
}
?>