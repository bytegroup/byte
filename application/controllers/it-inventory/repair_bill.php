<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 2:53 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_Bill extends MX_Controller{
    var $companyId= 0;
    var $repairIds= array();
    function __construct(){
        parent::__construct();
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

    function index($str){
        if(!$str){
            redirect(base_url(IT_MODULE_FOLDER.'bill_list'));
            die();
        }
        $this->repairIds=explode(';', urldecode($str));
        $this->companyId= $this->get_companyId();
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
            $crud->set_subject('Repair Bill');

            $crud->columns('billNumber', 'budgetId', 'billingDate', 'billType', 'billAmount');
            $crud->display_as('billNumber','Bill No.')
                ->display_as('budgetType', 'Budget Type')
                ->display_as('budgetId','Budget Head')
                ->display_as('billingDate','Billing Date')
                ->display_as('billReceiveDate', 'Bill Receive Date')
                ->display_as('billType', 'Bill Type')
                ->display_as('billAmount', 'Bill Amount')
                ->display_as('billPaymentType', 'Payment Type')
                ->display_as('invoiceNumber', 'Invoice No.')
                ->display_as('invoiceDate', 'Invoice Date')
                ->display_as('billSubmittedById', 'Bill Submitted By')
                ->display_as('billPaymentById', 'Bill Payment By')
                ->display_as('billParticulars', 'Particulars')
                ->display_as('billDescription', 'Descriptions')
                ->display_as('billCheckedById', 'Bill Checked By')
                ->display_as('vendorsId', 'Vendor Name')
                ->display_as('companyId', 'Company Name');
            $crud->add_fields('companyId', 'budgetType', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'invoiceNumber', 'invoiceDate', 'billCheckedById', 'billSubmittedById', 'billParticulars', 'billDescription', 'creatorId', 'createDate');
            $crud->edit_fields('companyId', 'billNumber', 'budgetType', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'invoiceNumber', 'invoiceDate', 'billCheckedById', 'billSubmittedById', 'billParticulars', 'billDescription', 'billPaymentById', 'editorId', 'editDate');
            $crud->required_fields('companyId', 'budgetId', 'billingDate', 'billReceiveDate', 'billPaymentType', 'billCheckedById', 'billSubmittedById');
            $crud->unset_texteditor('billDescription', 'billParticulars');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->field_type('companyId', 'hidden', $this->companyId);
            $crud->field_type('billNumber', 'readonly');
            $crud->field_type('billPaymentById', 'readonly');
            $crud->field_type('budgetType', 'dropdown', array('Capital'=>'Capital', 'Revenue'=>'Revenue'));
            $crud->field_type('billPaymentType', 'dropdown', array('Cash'=>'Cash', 'Cheque'=>'Cheque'));
            $crud->callback_field('billAmount', array($this, 'callback_field_billAmount'));
            $crud->callback_field('billType', array($this, 'callback_field_billType'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_bill'));
            $crud->callback_after_update(array($this, 'callback_after_update_bill'));

            $crud->unset_list()->unset_delete()->unset_read()->unset_export()->unset_print()->unset_back_to_list();
            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'bill_list').'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'bill_list').'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();
            $output->state = $crud->getState();
            $output->companyId= $this->companyId;
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Repair Bill";
            $output->base_url = base_url();
            $output->cancelURL=base_url(IT_MODULE_FOLDER.'bill_list');

            $output->body_template = "repair_bill_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_billType($row, $key){
        return 'Product Repair Bill';
    }
    function callback_field_billAmount($row, $key){
        return $this->get_bill_amount();
    }
    function callback_after_insert_bill($post, $key){
        $code= $this->get_company_code($this->companyId);
        $billAmount= $this->get_bill_amount();
        $this->db->update(
            TBL_BILL,
            array(
                'billNumber' => '' . $code . '/bill/' . mdate("%y", time()) . '/' . $key,
                'billType'   => 'Product Repair Bill',
                'billAmount' => $billAmount
            ),
            array('billId' => $key)
        );
        foreach($this->repairIds as $repairId){
            $this->db->update(TBL_REPAIR, array('billId' => $key), array('repairId' => $repairId));
        }
        
        $this->db->where('budgetId', $post['budgetId']);
        $this->db->set('budgetUtilization', 'budgetUtilization + '.$billAmount, FALSE);
        $this->db->update(TBL_BUDGET);
    }
    function callback_before_update_bill($post, $key){
        $preBill= (float)$post['preBillAmount'];
        $bill= (float)$post['billAmount'];
        $billDifference= abs($preBill - $bill);
        if($preBill>$bill){
            $this->db->where('budgetId', $post['budgetId']);
            $this->db->set('budgetUtilization', 'budgetUtilization - '.$billDifference, FALSE);
            $this->db->update(TBL_BUDGET);
        }
        else if($preBill<$bill){
            $this->db->where('budgetId', $post['budgetId']);
            $this->db->set('budgetUtilization', 'budgetUtilization + '.$billDifference, FALSE);
            $this->db->update(TBL_BUDGET);
        }
        else{}
    }

    /*****************************/
    /*** ajax call functions ***/
    /*****************************/
    /**
     * @param $companyId
     * @param string $budgetType
     */
    function ajax_get_budget_head($companyId, $budgetType=''){
        $this->db->select('budgetId, budgetHead');
        $this->db->from(TBL_BUDGET);
        $this->db->where('companyId', $companyId);
        if($budgetType !== '')$this->db->where('budgetType', $budgetType);
        $db= $this->db->get();
        $array = array();
        if(!$db->num_rows()) {echo json_encode($array); exit;}
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->budgetId, "property" => $row->budgetHead);
        endforeach;
        echo json_encode($array);
        exit;
    }

    /***************************************************************************/
    function get_companyId(){
        $this->db->select('s.companyId');
        $this->db->from(TBL_REPAIR.' as r ');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=r.damageDetailId');
        $this->db->join(TBL_DAMAGE.' as d ', 'd.damageId=dd.damageId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=d.stockId');
        $this->db->where('r.repairId', $this->repairIds[0]);
        $db=$this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->companyId;
    }
    function get_company_code($companyId){
        if(!$companyId) return '';
        $this->db->select('companyCode');
        $this->db->from(TBL_COMPANIES);
        $this->db->where('companyId', $companyId);
        $qry= $this->db->get();
        if(!$qry->num_rows()) return '';
        return $qry->result()[0]->companyCode;
    }
    function get_bill_amount(){
        $this->db->select('sum(repairAmount) as total');
        $this->db->from(TBL_REPAIR);
        $this->db->where_in('repairId', $this->repairIds);
        $db= $this->db->get();
        if(!$db->num_rows()) return 0;
        return $db->result()[0]->total;
    }
}