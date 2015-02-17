<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/16/15
 * Time: 3:25 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_Stock extends MX_Controller{
    var $quotationId= 0;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();
        /***********************/
        $this->load->model('it_inventory_model', 'model');

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index(){
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            if($crud->getState()!=='edit' || $crud->getState()!=='update_validation' || $crud->getState()!=='update'){

            }
            else{redirect(base_url() . IT_MODULE_FOLDER . 'receive_list');
            die();}

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_RECEIVES);
            $crud->set_relation('userId', TBL_USERS, '{firstName} {middleName} {lastName}');
            //$crud->where('receiveId', $recId);
            $crud->set_subject('Items to Stock');

            $crud->columns('receiveNumber', 'userId', 'receiveDate');
            $crud->display_as('receiveId', 'Receive No.')
                ->display_as('receiveNumber', 'Receive No.')
                ->display_as('requisitionId', 'Requisition No.')
                ->display_as('quotationId', 'Quotation No.')
                ->display_as('userId', 'Received By')
                ->display_as('numberOfProduct', 'No. Of Products')
                ->display_as('receiveDescription', 'Description')
                ->display_as('receiveConfirmed', 'Receive Confirmed?')
                ->display_as('receiveDate', 'Receive Date');

            $crud->add_fields('requisition', 'quotation', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('requisition', 'quotation', 'vendor', 'receiveNumber', 'userId', 'receiveDate', 'receiveDescription', 'items', 'receiveConfirmed');
            $crud->required_fields('receiveConfirmed');
            $crud->field_type('receiveNumber', 'readonly');
            $crud->field_type('userId', 'readonly');
            $crud->field_type('receiveDate', 'readonly');
            $crud->field_type('receiveDescription', 'readonly');
            $crud->field_type('receiveConfirmed', 'hidden', 'Yes');
            $crud->callback_field('requisition', array($this, 'callback_field_requisition'));
            $crud->callback_field('quotation', array($this, 'callback_field_quotation'));
            $crud->callback_field('vendor', array($this, 'callback_field_vendor'));
            $crud->callback_field('items', array($this, 'callback_field_items'));
            $crud->callback_after_update(array($this, 'callback_after_add_stock'));

            if (!isset($this->my_session->permissions['canIT-InventoryAdd'])) {
                $crud->unset_add();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryEdit'])) {
                $crud->unset_edit();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryView'])) {
                $crud->unset_read();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryDelete'])) {
                $crud->unset_delete();
            }

            $crud->unset_add()->unset_delete()->unset_list()->unset_export()->unset_print();
            $crud->unset_back_to_list();

            $quotId= $this->get_quotationId($crud->getStateInfo()->primary_key);
            $crud->set_lang_string(
                'update_success_message',
                'Item added to stock successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'receive/index').'/'.$quotId.'"'
                .'</script>'
                .'<div style="display:none">'
            );

            $crud->set_lang_string('form_update_changes','Add To Stock');
            $crud->set_lang_string('form_edit', 'Add');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Confirm the Receive, then add items to stock";
            $output->base_url = base_url();
            $output->backToReceive = base_url(IT_MODULE_FOLDER.'/receive/index').'/'. $quotId;
            $output->body_template = "add_stock_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_requisition($row, $key){
        return $this->get_requisition_title($key);
    }
    function callback_field_quotation($row, $key){
        return $this->get_quotation_title($key);
    }
    function callback_field_vendor($row, $key){
        return $this->get_vendor_title($key);
    }
    function callback_field_items($row, $key){
        $items = $this->get_quotation_items($key);
        $receiveDetails = $this->get_receive_details($key);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li class="item">Item Name</li><li class="category">Category</li><li class="ordQuantity">Ordered Quantity</li><li class="recQuantity">Receive Quantity</li><li class="warranty">Warranty</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach ($items as $id => $item) {
            $currentRecQty = isset($receiveDetails[$item['itemId']]) ? $receiveDetails[$item['itemId']]['qty'] : 0;
            $warranty = isset($receiveDetails[$item['itemId']]) ? $receiveDetails[$item['itemId']]['warranty'] : '';
            $id += 1;
            $html .= '<li><ul id="rowNum-' . $id . '">';
            $html .= '<li class="item">' . $item['item'] . '<small>('.$item['unit'].')</small></li>';
            $html .= '<li class="category">' . $item['category'] . '</li>';
            $html .= '<li class="ordQuantity">' . $item['quantity'] . '</li>';
            $html .= '<li class="recQuantity">' . $currentRecQty . '</li>';
            $html .= '<li class="warranty">' . $warranty . '</li>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    function callback_after_add_stock($post, $key){
        $this->db->select("c.companyId, c.companyCode")
            ->from(TBL_RECEIVES.' as rec ')
            ->join(TBL_REQUISITIONS . ' as r ', 'r.requisitionId=rec.requisitionId')
            ->join(TBL_COMPANIES . ' as c ', 'r.companyId=c.companyId')
            ->where('receiveId', $key);
        $db = $this->db->get();
        if (!$db->num_rows()) {
            $companyId = 0;
            $code = '';
        } else {
            $row = $db->result();
            $code = $row[0]->companyCode;
            $companyId = $row[0]->companyId;
        }

        $items= $this->get_receive_details($key);
        foreach($items as $itemId=>$item) {
            $stockId= $this->model->add_to_stock($key, $itemId, $companyId, $code, $item['qty']);
            $itemCode= $this->model->get_item_code_by_itemId($itemId);
            $data = array(
                'receiveDetailId' => $item['receiveDetailId'],
                'stockId' => $stockId
            );
            if ($this->model->isCountable($itemId)) {
                for($i=1; $i<= $item['qty']; $i++){
                    $this->db->insert(TBL_STOCK_DETAIL, $data);
                    $insertId= $this->db->insert_id();
                    $this->db->update(
                        TBL_STOCK_DETAIL,
                        array('productCode'=> ''.$code.'/'.$itemCode.'/'.$insertId),
                        array('stockDetailId'=>$insertId)
                    );
                }
            }else{
                $this->db->insert(TBL_STOCK_DETAIL, $data);
                $insertId= $this->db->insert_id();
                $this->db->update(
                    TBL_STOCK_DETAIL,
                    array('productCode'=> ''.$code.'/'.$itemCode.'/'.$insertId),
                    array('stockDetailId'=>$insertId)
                );
            }
        }
    }

    /*******************************************************************************/
    function get_quotationId($recId=0){
        if(!$recId)return 0;
        $this->db->select("quotationId")
            ->from(TBL_RECEIVES)
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->quotationId;
    }
    function get_requisition_title($recId){
        if (!$recId) return '';
        $this->db->select("r.requisitionTitle, r.requisitionNumber")
            ->from(TBL_RECEIVES . ' as rec ')
            ->join(TBL_REQUISITIONS . ' as r', 'r.requisitionId= rec.requisitionId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->requisitionTitle . ' [' . $row[0]->requisitionNumber . ']';
    }

    function get_quotation_title($recId){
        if (!$recId) return '';
        $this->db->select("quotationTitle, quotationNumber")
            ->from(TBL_RECEIVES.' as r ')
            ->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->quotationTitle . ' [' . $row[0]->quotationNumber . ']';
    }

    function get_vendor_title($recId){
        if (!$recId) return '';
        $this->db->select("v.vendorsName")
            ->from(TBL_RECEIVES.' as r ')
            ->join(TBL_QUOTATIONS .' as q ', 'q.quotationId=r.quotationId')
            ->join(TBL_VENDORS . ' as v', 'v.vendorsId=q.vendorsId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->vendorsName;
    }

    function get_quotation_items($recId = 0){
        if (!$recId) return array();
        $this->db->select("qd.*, i.itemName, c.categoryName, u.unitName")
            ->from(TBL_RECEIVES.' as r ')
            ->join(TBL_QUOTATIONS_DETAIL . ' as qd ', 'qd.quotationId=r.quotationId')
            ->join(TBL_ITEMS_MASTER . ' as i', 'qd.itemMasterId=i.itemMasterId')
            ->join(TBL_CATEGORIES . ' as c', 'i.categoryId=c.categoryId')
            ->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId')
            ->where('receiveId', $recId);
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array(
                "itemId"    => $row->itemMasterId,
                "item"      => $row->itemName,
                "unit"      => $row->unitName,
                "category"  => $row->categoryName,
                "quantity"  => $row->orderedQuantity,
                "unitPrice" => $row->unitPrice,
                "totalPrice"=> $row->quotationPrice
            );
        endforeach;
        return $array;
    }

    function get_receive_details($receiveId = 0){
        if (!$receiveId) return array();
        $this->db->select('receiveDetailId, itemMasterId, receiveQuantity, warrantyEndDate');
        $this->db->from(TBL_RECEIVES_DETAIL);
        $this->db->where('receiveId', $receiveId);
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[$row->itemMasterId] = array('receiveDetailId'=>$row->receiveDetailId, 'qty'=>$row->receiveQuantity, 'warranty'=>$row->warrantyEndDate);
        endforeach;
        return $array;
    }
}
?>