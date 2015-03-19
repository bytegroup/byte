<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 2:18 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Others_Bill extends MX_Controller {
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
            //$crud->set_relation("serviceAgreementId", TBL_SERVICE_AGREEMENTS, '{serviceAgreementName}');
            $crud->set_relation("vendorsId", TBL_VENDORS, '{vendorsName}');
            $crud->set_relation("companyId", TBL_COMPANIES, '{companyName}');
            $crud->where(TBL_BILL.'.serviceAgreementId !=', 0);
            $crud->set_subject('Service and Daily Bill');

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
                ->display_as('serviceAgreementId', 'Bill Type')
                ->display_as('vendorsId', 'Vendor Name')
                ->display_as('companyId', 'Company Name');
            $crud->add_fields('companyId', 'budgetType', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'serviceAgreementId', 'vendorsId', 'billAmount', 'billPaymentType', 'invoiceNumber', 'invoiceDate', 'billCheckedById', 'billSubmittedById', 'billParticulars', 'billDescription', 'creatorId', 'createDate');
            $crud->edit_fields('companyId', 'billNumber', 'budgetType', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'serviceAgreementId', 'vendorsId', 'billAmount', 'billPaymentType', 'invoiceNumber', 'invoiceDate', 'billCheckedById', 'billSubmittedById', 'billParticulars', 'billDescription', 'billPaymentById', 'editorId', 'editDate');
            $crud->set_read_fields('companyId', 'billNumber', 'budgetType', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'serviceAgreementId', 'vendorsId', 'billAmount', 'billPaymentType', 'invoiceNumber', 'invoiceDate', 'billCheckedById', 'billSubmittedById', 'billParticulars', 'billDescription', 'billPaymentById');
            $crud->required_fields('companyId', 'budgetId', 'billingDate', 'billReceiveDate', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'serviceAgreementId', 'vendorsId');
            $crud->unset_texteditor('billDescription', 'billParticulars');
            $crud->field_type('billNumber', 'readonly');
            $crud->field_type('billPaymentById', 'readonly');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->field_type('billType', 'hidden', '');
            $crud->field_type('budgetType', 'dropdown', array('Capital'=>'Capital', 'Revenue'=>'Revenue'));
            $crud->field_type('billPaymentType', 'dropdown', array('Cash'=>'Cash', 'Cheque'=>'Cheque'));
            $crud->field_type('serviceAgreementId', 'dropdown', $this->get_bill_type_list());
            $crud->callback_after_insert(array($this, 'callback_after_insert_bill'));
            $crud->callback_after_update(array($this, 'callback_after_update_bill'));

            $output = $crud->render();
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Service Agreement & Direct Daily Bill List";
            $output->base_url = base_url();
            $output->cancelURL=base_url().IT_MODULE_FOLDER.'bill_list';

            $output->body_template = "others_bill_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_billType($row, $key){
        return 'Product Purchase Bill';
    }
    function callback_field_billAmount($row, $key){
        return $this->get_bill_amount($this->receiveId);
    }
    function callback_after_insert_bill($post, $key){
        $code= $this->get_company_code($post['companyId']);
        $this->db->update(
            TBL_BILL,
            array(
                'billNumber' => '' . $code . '/bill/' . mdate("%y", time()) . '/' . $key,
            ),
            array('billId' => $key)
        );
        $this->db->where('budgetId', $post['budgetId']);
        $this->db->set('budgetUtilization', 'budgetUtilization + '.$post['billAmount'], FALSE);
        $this->db->update(TBL_BUDGET);
    }
    function callback_after_update_bill($post, $key){
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
    function ajax_get_budget_head($companyId=0, $budgetType=''){
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
    function ajax_get_agreement_details($serviceAgreementId){
        if(!$serviceAgreementId){echo json_encode(array());exit;}
        $this->db->select('serviceAgreementAmount, vendorsId');
        $this->db->from(TBL_SERVICE_AGREEMENTS);
        $this->db->where('serviceAgreementId', $serviceAgreementId);
        $db= $this->db->get();
        $array = array();
        if(!$db->num_rows()) {echo json_encode($array); exit;}
        foreach ($db->result() as $row):
            $array= array("amount" => $row->serviceAgreementAmount, "vendor" => $row->vendorsId);
        endforeach;
        echo json_encode($array);
        exit;
    }

    /*******************************************************************************/
    function get_bill_type_list(){
        $this->db->select('serviceAgreementId, serviceAgreementName');
        $this->db->from(TBL_SERVICE_AGREEMENTS);
        $this->db->where('serviceAgreementEndDate >= ', mdate("%y-%m-%d", time()));
        $db= $this->db->get();
        if(!$db->num_rows())return array('');
        $array=array();
        foreach ($db->result() as $row):
            $array[$row->serviceAgreementId] = $row->serviceAgreementName;
        endforeach;
        return $array;
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
}