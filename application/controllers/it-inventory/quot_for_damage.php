<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/17/15
 * Time: 12:28 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quot_For_Damage extends MX_Controller {

    var $requisitionId='';
    var $approvedQuotationId=0;
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library("my_functions");
        $this->load->model("quotation_model");

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index($id){
        $this->id=$id;
        if(!$id){
            redirect(base_url().IT_MODULE_FOLDER.'requisition_for_damage');
            die();
        }
        $this->requisitionId=$id;
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_QUOTATIONS);
            $crud->set_relation('vendorsId', TBL_VENDORS, '{vendorsName}');
            //$crud->set_relation('requisitionId', TBL_REQUISITIONS, '{requisitionTitle}');
            //$crud->set_relation('', '','');
            $crud->where(TBL_QUOTATIONS.'.requisitionId',$id);
            $crud->set_subject('Quotation');

            $crud->columns('quotationTitle','quotationDate','vendorsId', 'approved');
            $crud->callback_column('approved', array($this, 'callback_column_approved'));
            $crud->display_as('requisitionId','Requisition Title')
                ->display_as('quotationNumber', 'Quotation Number')
                ->display_as('organizationId','Organization')
                ->display_as('departmentId','Department')
                ->display_as('quotationApproved','Approval')
                ->display_as('vendorsId', 'Vendor')
                ->display_as('quotationTitle', 'Quotation Title')
                ->display_as('quotationFile', 'Attachment')
                ->display_as('quotationDescription', 'Description')
                ->display_as('paymentType', 'Payment Type')
                ->display_as('finalTotalPrice', 'Total Price')
                ->display_as('quotationDate','Date');

            $crud->add_fields('requisitionId', 'quotationTitle', 'quotationDescription', 'vendorsId', 'quotationDate', 'paymentType', 'quotationFile', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('requisitionId', 'quotationTitle', 'quotationDescription', 'vendorsId', 'quotationDate', 'paymentType', 'quotationFile', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('requisitionId', 'quotationTitle', 'quotationDescription', 'vendorsId', 'quotationDate', 'paymentType', 'quotationFile', 'items');
            $crud->required_fields(array('requisitionId','quotationTitle', 'vendorsId', 'quotationDate'));
            $crud->unique_fields('quotationTitle');
            $crud->unset_texteditor('quotationDescription');
            $crud->set_field_upload('quotationFile','assets/uploads/files');
            $crud->field_type('items', 'readonly');
            $crud->field_type('requisitionId', 'hidden', $id);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('items', array($this, 'callback_field_items'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_quotation'));
            $crud->callback_after_update(array($this, 'callback_after_update_quotation'));

            $this->approvedQuotationId= $this->get_approved_quotation_id($id);
            if($this->approvedQuotationId) $crud->unset_add();

            $crud->add_action('Sold', '', '', 'ui-icon-cart', array($this,'setApproveQuotationURL'));
           /* $crud->add_action('Receive', '', '', 'ui-icon-transferthick-e-w', array($this,'setReceiveURL'));*/

            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'quot_for_damage/index/'.$id).'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'quot_for_damage/index/'.$id).'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();
            $output->quotationFor= $this->get_requisition_title($id);
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->requisitionId = $id;
            $output->pageTitle = "Quotations On Damage Sell";
            $output->base_url = base_url();
            $output->backToRequisitionList=base_url().IT_MODULE_FOLDER.'requisition_for_damage';

            $output->body_template = "quot_for_damage_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function setApproveQuotationURL($primary_key, $value){
        $approvedId= $this->get_approved_quotation_id($value->requisitionId);
        if($approvedId)
            return base_url().IT_MODULE_FOLDER.'sell_damage/index/'.$value->requisitionId.'/'.$primary_key.'/edit/'.$approvedId;
        else return base_url().IT_MODULE_FOLDER.'sell_damage/index/'.$value->requisitionId.'/'.$primary_key.'/add';
    }
    /*function  setReceiveURL($primary_key, $value){
        $this->db->select("quotationId")
            ->from(TBL_APPROVE_QUOTATION)
            ->where('approveQuotationId', $this->approvedQuotationId);
        $db = $this->db->get();
        if(!$db->num_rows())return '#';
        $row= $db->result();
        if($row[0]->quotationId == $primary_key)
            return base_url(IT_MODULE_FOLDER.'receive/index').'/'.$primary_key;
        else return '#';
    }*/

    function callback_column_approved($primary_key, $value){
        $approvedId= $this->get_approved_quotation_id($value->requisitionId);
        $approvedMsg=''
            .'<span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span><span style="font-size: 14px">&nbsp; Sold</span>';
        $notApprovedMsg= ''
            .'<span class="ui-button-icon-primary ui-icon ui-icon-circle-close"></span><span style="font-size: 14px">&nbsp; Unsold</span>';

        if(!$approvedId) return $notApprovedMsg;
        $this->db->select("quotationId")
            ->from(TBL_APPROVE_QUOTATION)
            ->where('approveQuotationId', $approvedId);
        $db = $this->db->get();
        if(!$db->num_rows()) return $notApprovedMsg;
        $row= $db->result();
        return $row[0]->quotationId == $value->quotationId ? $approvedMsg : $notApprovedMsg;
    }

    function callback_after_insert_quotation($post, $key){
        $itemIds= $post['damagedItemIds'];
        foreach($itemIds as $itemId){
            $data= array(
                'quotationId'           => $key,
                'requisitionDetailId'   => $itemId,
                'quotationPrice'        => $post['totalPrice'][$itemId]
            );
            $this->db->insert(TBL_QUOTATIONS_DETAIL, $data);
        }
    }
    function callback_after_update_quotation($post, $key){
        $items= $post['damagedItemIds'];
        foreach($items as $item){
            $this->db->update(
                TBL_QUOTATIONS_DETAIL,
                array('quotationPrice'=> $post['totalPrice'][$item]),
                array('quotationId'=>$key, 'requisitionDetailId'=>$item)
            );
        }
    }

    function callback_field_items($row, $key){
        $damagedItems= $this->get_requisition_items($this->requisitionId);
        $flag= $key ? true : false;
        $quotationPrice= $flag ? $this->get_quotation_details($key): null;

        $total=0.0;
        $html = '';
        $html .= '<ul>';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Item</li><li>Tracking No.</li><li>Quantity</li><li>Total Price</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($damagedItems as $id=>$item){
            $price= $flag ? $quotationPrice[$item['reqDetailId']]:'';
            $html.='<li><ul>';
            $html.='<li>'.$item['name'].'</li>';
            $html.='<li>'.$item['code'].'</li>';
            $html.='<li>'.$item['qty'].'</li>';
            $html.='<li><input type="number" placeholder="Price" name="totalPrice['.$item['reqDetailId'].']" value="'.$price.'" min="0"/>';
            $html.='<input type="hidden" name="damagedItemIds[]" value="'.$item['reqDetailId'].'"/></li>';
            $html.='</ul></li>';
        }

        $html.='<li>Grand Total = <span id="items-grand-total">'.$total.'</span></li></ul>';
        return $html;
    }
    function callback_read_field_items($row, $key){
        $damagedItems= $this->get_requisition_items($this->requisitionId);
        $quotationPrice= $this->get_quotation_details($key);
        $total= 0.0;
        $html='';
        $html .= '<ul class="read">';

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
    function get_requisition_title($requisitionId){
        if(!$requisitionId)return '';
        $this->db->select("requisitionTitle, requisitionNumber")
            ->from(TBL_REQUISITIONS)
            ->where('requisitionId', $requisitionId);
        $db = $this->db->get();
        $row=$db->result();

        return $row[0]->requisitionTitle.' ['.$row[0]->requisitionNumber.']';
    }
    function get_approved_quotation_id($requisitionId){
        if(!$requisitionId)return 0;
        $this->db->select("aq.approveQuotationId")
            ->from(TBL_QUOTATIONS.' as q ')
            ->join(TBL_APPROVE_QUOTATION.' as aq ', 'q.quotationId = aq.quotationId')
            ->where('requisitionId', $requisitionId);
        $db = $this->db->get();
        if(!$db->num_rows()) return 0;
        $row=$db->result();
        return $row[0]->approveQuotationId;
    }
    function get_quotation_items($quotId){
        if(!$quotId){return array();}
        $this->db->select("qd.*, c.categoryName, i.itemName, u.unitName")
            ->from(TBL_QUOTATIONS_DETAIL.' as qd ')
            ->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId')
            ->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId')
            ->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId')
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        if(!$db->num_rows()){return array();}
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array(
                "cat"       => $row->categoryName,
                "item"      => $row->itemName,
                "unit"      => $row->unitName,
                "quantity"  => $row->orderedQuantity,
                "unitPrice" => $row->unitPrice,
                "totalPrice"=> $row->quotationPrice
            );
        endforeach;
        return $array;
    }
}
?>