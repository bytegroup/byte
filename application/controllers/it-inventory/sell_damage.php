<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/20/15
 * Time: 3:29 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sell_Damage extends MX_Controller{
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
            redirect(base_url().IT_MODULE_FOLDER.'requisition_for_damage');
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
            $crud->set_subject('Sell');

            $crud->display_as('quotationId', 'Quotation');
            $crud->display_as('userId', 'Checked By');
            $crud->display_as('approveDate', 'Sold Date');
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
                .'window.location = "'.base_url().IT_MODULE_FOLDER.'quot_for_damage/index/'.$requisitionId.'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url().IT_MODULE_FOLDER.'quot_for_damage/index/'.$requisitionId.'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Approve Quotation";
            $output->base_url = base_url();
            $output->cancel=base_url().IT_MODULE_FOLDER.'quot_for_damage/index/'.$requisitionId;

            $output->body_template = "sell_damage_view.php";
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
        $damagedItems= $this->get_requisition_items($this->requisitionId);
        $quotationPrice= $this->get_quotation_details($this->quotationId);
        $total= 0.0;
        $html='';
        $html .= '<ul>';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Item</li><li>Tracking No.</li><li>Quantity</li><li>Total Price</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($damagedItems as $id=>$item):
            $total += $quotationPrice[$item['reqDetailId']];
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>'.$item['name'].'</li>';
            $html .= '<li>'.$item['code'].'</li>';
            $html .= '<li>'.$item['qty'].'</li>';
            $html .= '<li>'.$quotationPrice[$item['reqDetailId']].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;

        $html .= '<li>Grand Total = <span>'.$total.'</span></li>';
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
    function get_requisition_items($requisitionId){
        $this->db->select('rd.requisitionDetailId, dd.damageDetailId, dd.damageQuantity, sd.productCode, im.itemName, cat.categoryName, com.companyId');
        $this->db->from(TBL_REQUISITIONS_DETAIL.' as rd ');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=rd.damageDetailId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as com ', 'com.companyId=s.companyId');
        $this->db->where('rd.requisitionId', $requisitionId);
        $db=$this->db->get();
        if(!$db->num_rows())return [];
        $array= [];
        foreach($db->result() as $row ){
            $array[$row->damageDetailId]= [
                'reqDetailId'   => $row->requisitionDetailId,
                'code'          => $row->productCode,
                'name'          => $row->itemName,
                'category'      => $row->categoryName,
                'companyId'     => $row->companyId,
                'qty'           => $row->damageQuantity
            ];
        }
        return $array;
    }
    function get_quotation_details($quotationId){
        $this->db->select("requisitionDetailId, quotationPrice")
            ->from(TBL_QUOTATIONS_DETAIL)
            ->where('quotationId', $quotationId);
        $db = $this->db->get();
        $array = array();
        foreach($db->result() as $row):
            $array[$row->requisitionDetailId] = $row->quotationPrice;
        endforeach;
        return $array;
    }
}
?>