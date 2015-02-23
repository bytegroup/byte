<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/23/15
 * Time: 3:03 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issue_List extends MX_Controller{
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model('it_inventory_model', 'itModel');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index(){
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_ISSUES);
            $crud->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}');
            $crud->set_relation("issueUserId", TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_relation("stockId", TBL_STOCK, '{stockNumber}');
            $crud->set_subject('Issue');

            $crud->columns('issueTo', 'company', 'departmentId', 'issueUserId', 'stockId', 'issueDate');
            //$crud->callback_column('productCode', array($this, 'callback_column_productCode'));
            $crud->callback_column('company', array($this, 'callback_column_company'));
            $crud->display_as('issueDate', 'Date')
                ->display_as('issueTo', 'Issue To')
                ->display_as('departmentId', 'Department')
                ->display_as('issueUserId', 'Employee')
                ->display_as('issueDescription', 'Description')
                ->display_as('stockQuantity', 'Stock Quantity')
                ->display_as('stockId', 'Stock No.')
                ->display_as('items', 'Issued Items')
                ->display_as('productCode', 'Item Code')
                ->display_as('issueQuantity', 'Issue Quantity');

            $crud->add_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate', 'issueDescription', 'issueQuantity', 'items');

            $crud->callback_read_field('company', array($this, 'callback_read_field_company'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));

            $crud->unset_add()->unset_edit()->unset_delete();

            $crud->add_action('Damage', '', base_url(IT_MODULE_FOLDER.'issue_damage/index').'/', 'ui-icon-trash');

            $output = $crud->render();
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";

            $output->pageTitle = "Issue List";
            $output->base_url = base_url();
            $output->backToStockList = base_url(IT_MODULE_FOLDER . 'stock');
            $msg = null;
            $output->msg = $msg;
            $output->body_template = "issue_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_column_productCode($value, $row){
        $items= $this->get_issue_items_by_issue_id($row->issueId);
        $code='';
        foreach($items as $item):
            $code .= $item['productCode'].'</br>';
        endforeach;
        return $code;
    }
    function callback_column_company($value, $row){
        return $this->get_company_name_by_issue_id($row->issueId);
    }
    function callback_read_field_company($row, $key){
        return $this->get_company_name_by_issue_id($key);
    }
    function callback_read_field_items($row, $key){
        if(!$this->itModel->isCountableIssue($key))
            return $this->callback_read_field_uncountable_items($key);
        $issuedItems= $this->get_issue_items_by_issue_id($key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        if(count($issuedItems))
            foreach($issuedItems as $items){
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>'.$items['productCode'].'</li>';
            $html .= '<li>'.$items['warranty'].'</li>';
            $html .= '<li>'.$items['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        return $html;
    }
    function callback_read_field_uncountable_items($key){
        $issuedItems= $this->get_issued_uncountable_items($key);
        $damageQty= $this->get_uncountable_damage_qty($key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Product Code</li><li>Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        if(count($issuedItems))
            foreach($issuedItems as $items){
                $html .= '<li>';
                $html .= '<ul>';
                $html .= '<li>'.$items['productCode'].'</li>';
                $html .= '<li>'.($items['issueQty']-$damageQty[$items['issuedId']]).'</li>';
                $html .= '<li>'.$items['warranty'].'</li>';
                $html .= '<li>'.$items['vendor'].'</li>';
                $html .= '</ul>';
                $html .= '</li>';
            }

        $html .= '</ul>';
        return $html;
    }

    /******************************************************************************/
    function get_company_name_by_issue_id($issueId){
        if(!$issueId)return '';
        $this->db->select('c.companyName');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_STOCK.' as s ', 'i.stockId=s.stockId');
        $this->db->join(TBL_COMPANIES.' as c ', 's.companyId=c.companyId');
        $this->db->where('issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        return $db->result()[0]->companyName;
    }
    function get_issue_items_by_issue_id($issueId = 0){
        if (!$issueId) return array();
        $this->db->select('id.stockDetailId, v.vendorsName, sd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_ISSUE_DETAIL . ' as id ', 'id.issueId=i.issueId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'sd.receiveDetailId=rd.receiveDetailId');
        $this->db->join(TBL_RECEIVES . ' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS . ' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS . ' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('i.issueId', $issueId);
        $this->db->where('id.stockDetailId NOT IN (SELECT stockDetailId FROM '.TBL_DAMAGE_DETAIL.')');
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array('issuedId' => $row->stockDetailId, 'vendor' => $row->vendorsName, 'productCode' => $row->productCode, 'warranty' => $row->warrantyEndDate);
        endforeach;
        return $array;
    }
    function get_issued_uncountable_items($issueId){
        if (!$issueId) return array();
        $this->db->select('id.stockDetailId, id.issueQuantity, v.vendorsName, sd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL . ' as id ', 'id.issueId=i.issueId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'sd.receiveDetailId=rd.receiveDetailId');
        $this->db->join(TBL_RECEIVES . ' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS . ' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS . ' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('i.issueId', $issueId);
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array('issuedId' => $row->stockDetailId, 'vendor' => $row->vendorsName, 'productCode' => $row->productCode, 'warranty' => $row->warrantyEndDate, 'issueQty'=>$row->issueQuantity);
        endforeach;
        return $array;
    }
    public function get_uncountable_damage_qty($issueId){
        if(!$issueId)return array();
        $this->db->select('dd.stockDetailId, sum(dd.damageQuantity) as qty');
        $this->db->from(TBL_DAMAGE.' as d ');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageId=d.damageId');
        $this->db->where('d.issueId', $issueId);
        $this->db->group_by('dd.stockDetailId');
        $db=$this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]=$row->qty;
        }
        return $array;
    }
}
?>