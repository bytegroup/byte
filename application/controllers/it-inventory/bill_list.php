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

            $crud->columns('billNumber','budgetId', 'billAmount', 'billType', 'billingDate');
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
                //->display_as('billParticulars', 'Particulars')
                ->display_as('billCheckedById', 'Bill Checked By');
            $crud->add_fields('receiveId', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'billDescription', 'creatorId', 'createDate');
            $crud->edit_fields('receiveId', 'billNumber', 'budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'billDescription', 'billPaymentById', 'editorId', 'editDate');
            $crud->unset_texteditor('billDescription', 'Particulars');
            $crud->set_read_fields('budgetId', 'billingDate', 'billReceiveDate', 'billType', 'billAmount', 'billPaymentType', 'billCheckedById', 'billSubmittedById', 'billDescription');
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
            $crud->callback_before_delete(array($this, 'callback_before_delete_bill'));

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

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    /**
     * @param $primary_key
     */
    function callback_before_delete_bill($primary_key){
        $billDetails= $this->get_bill_amount_from_bill($primary_key);
        $this->db->where('budgetId', $billDetails[0]['budgetId']);
        $this->db->set('budgetUtilization', 'budgetUtilization - '.$billDetails[0]['billAmount'], FALSE);
        $this->db->update(TBL_BUDGET);
    }

    /***************************************************************************************/
    function get_bill_amount_from_bill($billId){
        if(!$billId)return 0;
        $this->db->select('budgetId, billAmount');
        $this->db->from(TBL_BILL);
        $this->db->where('billId', $billId);
        $db=$this->db->get();
        if(!$db->num_rows()) return 0;
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('budgetId'=> $row->budgetId, 'billAmount'=>$row->billAmount);
        endforeach;
        return $array;
    }
}
?>