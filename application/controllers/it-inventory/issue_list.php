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

            $crud->columns('issueTo', 'issueDate', 'issueQuantity');
            $crud->display_as('issueDate', 'Date')
                ->display_as('issueTo', 'Issue To')
                ->display_as('departmentId', 'Department')
                ->display_as('issueUserId', 'Employee')
                ->display_as('issueDescription', 'Description')
                ->display_as('stockQuantity', 'Stock Quantity')
                ->display_as('stockId', 'Stock No.')
                ->display_as('items', 'Issued Items')
                ->display_as('issueQuantity', 'Issue Quantity');

            $crud->add_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate',  'issueDescription', 'stockQuantity', 'issueQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('stockId', 'company', 'issueTo', 'departmentId', 'issueUserId', 'issueDate', 'issueDescription', 'issueQuantity', 'items');

            $crud->callback_read_field('company', array($this, 'callback_read_field_company'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));

            $crud->unset_add()->unset_edit()->unset_delete();

            $crud->add_action('Damage', '', base_url(IT_MODULE_FOLDER.'damage/index'), 'ui-icon-trash');

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
    function callback_read_field_company($row, $key){
        return $this->get_company_name_by_issue_id($key);
    }
    function callback_read_field_items($row, $key){
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
        $this->db->select('rd.receiveDetailId, v.vendorsName, rd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_RECEIVES_DETAIL . ' as rd ', 'rd.issueId=i.issueId');
        $this->db->join(TBL_RECEIVES . ' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS . ' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS . ' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('i.issueId', $issueId);
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array('issuedId' => $row->receiveDetailId, 'vendor' => $row->vendorsName, 'productCode' => $row->productCode, 'warranty' => $row->warrantyEndDate);
        endforeach;
        return $array;
    }
}
?>