<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/29/14
 * Time: 1:14 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approve_Quotation extends MX_Controller{
    var $quotationId=0;
    var $requisitionId= 0;
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

    function index($requisitionId=0, $quotationId=0){
        $this->quotationId= $quotationId;
        $this->requisitionId= $requisitionId;
        if(!$this->requisitionId || !$this->quotationId){
            redirect(base_url().IT_MODULE_FOLDER.'requisition');
            die();
        }
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_APPROVE_QUOTATION);
            $crud->set_relation('userId', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_subject('Approve');

            $crud->display_as('quotationId', 'Quotation');
            $crud->display_as('userId', 'Approved By');
            $crud->display_as('approveDate', 'Approve Date');
            $crud->display_as('approveDetails', 'Description');
            $crud->display_as('approvalAttachment', 'Attachment');

            $crud->unset_list()->unset_delete()->unset_read()->unset_export()->unset_print()->unset_back_to_list();

            $crud->add_fields('quotationId', 'requisition', 'quotation', 'vendor', 'userId', 'approveDate', 'approveDetails', 'approvalAttachment', 'items','creatorId', 'createDate');
            $crud->edit_fields('quotationId', 'requisition', 'quotation', 'vendor', 'userId', 'approveDate', 'approveDetails', 'approvalAttachment', 'items','editorId', 'editDate');
            $crud->required_fields(array('userId', 'quotationId', 'approveDate'));
            $crud->unset_texteditor('approveDetails');
            $crud->field_type('quotationId', 'hidden', $quotationId);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->set_field_upload('approvalAttachment', 'assets/uploads/files');
            $crud->callback_field('requisition', array($this, 'callback_field_requisition'));
            $crud->callback_field('quotation', array($this, 'callback_field_quotation'));
            $crud->callback_field('vendor', array($this, 'callback_field_vendor'));
            $crud->callback_field('items', array($this, 'callback_field_items'));

            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url().IT_MODULE_FOLDER.'quotation/index/'.$requisitionId.'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url().IT_MODULE_FOLDER.'quotation/index/'.$requisitionId.'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Approve Quotation";
            $output->base_url = base_url();
            $output->cancel=base_url().IT_MODULE_FOLDER.'quotation/index/'.$requisitionId;

            $output->body_template = "approve_quotation_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_requisition($row, $key){
        return $this->get_requisition_title($this->requisitionId);
    }
    function callback_field_quotation($row, $key){
        return $this->get_quotation_title($this->quotationId);
    }
    function callback_field_vendor($row, $key){
        return $this->get_vendor_title($this->quotationId);
    }
    function callback_field_items($row, $key){
        $items= $this->get_quotation_items($this->quotationId);
        $grandTotal= 0;
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Item Name</li><li>Quantity</li><li>Unit Price</li><li>Total Price</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($items as $item){
            $html .= '<li><ul>';
            $html .= '<li>'.$item['item'].'</li>';
            $html .= '<li>'.$item['quantity'].'</li>';
            $html .= '<li>'.$item['unitPrice'].'</li>';
            $html .= '<li>'.$item['totalPrice'].'</li>';
            $html .= '</ul></li>';
            $grandTotal += $item['totalPrice'];
        }
        $html .= '<li>Grand Total = <span id="items-grand-total">'.$grandTotal.'</span></li>';
        $html .= '</ul>';

        return $html;
    }

    /*******************************************************************************/
    function get_requisition_title($reqId){
        if(!$reqId)return '';
        $this->db->select("requisitionTitle, requisitionNumber")
            ->from(TBL_REQUISITIONS)
            ->where('requisitionId', $reqId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->requisitionTitle.' ['.$row[0]->requisitionNumber.']';
    }
    function get_quotation_title($quotId){
        if(!$quotId)return '';
        $this->db->select("quotationTitle, quotationNumber")
            ->from(TBL_QUOTATIONS)
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->quotationTitle.' ['.$row[0]->quotationNumber.']';
    }
    function get_vendor_title($quotId){
        if(!$quotId)return '';
        $this->db->select("v.vendorsName")
            ->from(TBL_QUOTATIONS.' as q ')
            ->join(TBL_VENDORS.' as v', 'q.vendorsId=v.vendorsId')
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->vendorsName;
    }
    function get_quotation_items($quotationId){
        if(!$quotationId)return array();
        $this->db->select("qd.*, i.itemName")
            ->from(TBL_QUOTATIONS_DETAIL.' as qd ')
            ->join(TBL_ITEMS_MASTER.' as i', 'qd.itemMasterId=i.itemMasterId')
            ->where('quotationId', $quotationId);
        $db = $this->db->get();
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row):
            $array[] = array(
                "item"          => $row->itemName,
                "quantity"      => $row->orderedQuantity,
                "unitPrice"     => $row->unitPrice,
                "totalPrice"    => $row->quotationPrice
            );
        endforeach;
        return $array;
    }
}
?>