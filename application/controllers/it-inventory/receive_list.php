<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/13/15
 * Time: 4:01 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receive_List extends MX_Controller {
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
            $crud->set_table(TBL_RECEIVES);
            $crud->set_relation('userId', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_relation('requisitionId', TBL_REQUISITIONS, '{requisitionNumber}');
            $crud->set_relation('quotationId', TBL_QUOTATIONS, '{quotationNumber}');
            $crud->set_subject('Receive');

            $crud->columns('receiveNumber', 'requisitionId', 'quotationId', 'receiveDate', 'vendorsId');
            $crud->display_as('receiveNumber', 'Receive')
                ->display_as('requisitionId','Requisition No.')
                ->display_as('quotationId','Quotation No.')
                ->display_as('userId', 'Received By')
                ->display_as('vendorsId', 'Vendor')
                ->display_as('numberOfProduct', 'No. Of Products')
                ->display_as('receiveDescription', 'Description')
                ->display_as('stock', 'Stock')
                ->display_as('receiveDate','Receive Date');

            $crud->add_fields('quotationId', 'requisitionId', 'requisition', 'quotation', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items', 'stock', 'creatorId', 'createDate');
            $crud->edit_fields('quotationId', 'requisitionId', 'requisition', 'quotation', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items', 'stock', 'editorId', 'editDate');
            $crud->set_read_fields('requisitionId', 'quotationId', 'receiveNumber', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items', 'stock');
            $crud->required_fields('quotationId', 'userId', 'receiveDate');
            $crud->unset_texteditor('receiveDescription');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            //$crud->callback_read_field('requisition', array($this, 'callback_field_requisition'));
            //$crud->callback_read_field('quotation', array($this, 'callback_field_quotation'));
            $crud->callback_read_field('vendor', array($this, 'callback_read_field_vendor'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_read_field('stock', array($this, 'callback_read_field_stock'));

            if(!isset($this->my_session->permissions['canIT-InventoryAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryView'])){
                $crud->unset_read();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryDelete'])){
                $crud->unset_delete();
            }

            $crud->unset_add()->unset_edit()->unset_delete();

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Receive Item";
            $output->base_url = base_url();
            $output->body_template = "receive_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            redirect(base_url(IT_MODULE_FOLDER.'receive_list'));
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    /*function callback_field_requisition($row, $key){
        return $this->get_requisition_title($key);
    }
    function callback_field_quotation($row, $key){
        return $this->get_quotation_title($key);
    }*/
    function callback_read_field_vendor($row, $key){
        return $this->get_vendor_title($key);
    }
    function callback_read_field_items($row, $key){
        $items= $this->get_received_items($key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li class="item">Item Name</li><li class="category">Category</li><li class="recQuantity">Receive Quantity</li><li class="warranty">Warranty</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($items as $id=>$item){
            $id += 1;
            $html .= '<li><ul id="rowNum-'.$id.'">';
            $html .= '<li class="item">'.$item['item'].'</li>';
            $html .= '<li class="category">'.$item['category'].'</li>';
            //$html .= '<li class="ordQuantity">'.$item['quantity'].'</li>';
            $html .= '<li class="recQuantity"><h5>'.$item['recQuantity'].'</h5></li>';
            $html .= '<li class="warranty">'.$item['warranty'].'</li>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';
        return $html;
    }
    function callback_read_field_stock($row, $key){
        $items= $this->get_stock_items($key);
        //var_dump($items);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li class="item">Item Name</li><li class="code">Item Code</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($items as $id=>$item){
            $id += 1;
            $html .= '<li><ul id="rowNum-'.$id.'">';
            $html .= '<li class="item">'.$item['name'].'</li>';
            $html .= '<li class="code">'.$item['code'].'</li>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /*******************************************************************************/
    function get_requisition_title($recId){
        if(!$recId)return '';
        $this->db->select("r.requisitionTitle, r.requisitionNumber")
            ->from(TBL_RECEIVES.' as rec ')
            ->join(TBL_REQUISITIONS.' as r', 'rec.requisitionId= r.requisitionId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->requisitionTitle.' ['.$row[0]->requisitionNumber.']';
    }
    function get_quotation_title($recId){
        if(!$recId)return '';
        $this->db->select("q.quotationTitle, q.quotationNumber")
            ->from(TBL_RECEIVES.' as r ')
            ->join(TBL_QUOTATIONS.' as q ', 'r.quotationId=q.quotationId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->quotationTitle.' ['.$row[0]->quotationNumber.']';
    }
    function get_vendor_title($recId){
        if(!$recId)return '';
        $this->db->select("v.vendorsName")
            ->from(TBL_RECEIVES.' as r ')
            ->join(TBL_QUOTATIONS.' as q', 'r.quotationId=q.quotationId')
            ->join(TBL_VENDORS.' as v', 'q.vendorsId=v.vendorsId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row=$db->result();
        return $row[0]->vendorsName;
    }
    function get_received_items($receiveId=0){
        if(!$receiveId)return array();
        $this->db->select("rd.itemMasterId, rd.receiveQuantity, rd.warrantyEndDate, i.itemName, c.categoryName, u.unitName")
            ->from(TBL_RECEIVES_DETAIL.' as rd ')
            ->join(TBL_ITEMS_MASTER.' as i ', 'rd.itemMasterId=i.itemMasterId')
            ->join(TBL_CATEGORIES.' as c ', 'i.categoryId=c.categoryId')
            ->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId')
            //->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'rec.quotationId=qd.quotationId and rd.itemMasterId=qd.itemMasterId')
            ->where('rd.receiveId', $receiveId);
        $db = $this->db->get();
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row):
            $array[] = array(
                "itemId"        => $row->itemMasterId,
                "item"          => $row->itemName,
                "category"      => $row->categoryName,
                "quantity"      => '',
                "unitPrice"     => '',
                "totalPrice"    => '',
                "recQuantity"   => $row->receiveQuantity.'<small> '.$row->unitName.'</small>',
                "warranty"      => $row->warrantyEndDate
            );
        endforeach;
        return $array;
    }
    function get_stock_items($receiveId=0){
        $db=$this->db->select('sd.productCode, im.itemName')
            ->from(TBL_STOCK_DETAIL.' as sd ')
            ->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId')
            ->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=rd.itemMasterId')
            ->where('rd.receiveId', $receiveId)
            ->get();
        if(!$db->num_rows()) return array();
        $array = array();
        foreach($db->result() as $row){
            $array[]= array(
                'code'=> $row->productCode,
                'name'=> $row->itemName
            );
        }
        return $array;
    }
}
?>