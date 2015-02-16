<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/16/15
 * Time: 2:27 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receive_old extends MX_Controller{
    var $quotationId = 0;
    var $requisitionId = 0;

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        /***********************/
        $this->load->model('it_inventory_model');

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index($quotationId){
        if (!$quotationId) {
            redirect(base_url() . IT_MODULE_FOLDER . 'receive_list');
            die();
        }
        $this->quotationId = $quotationId;
        $this->requisitionId = $this->get_requisitionId($quotationId);
        if (!$this->requisitionId) {
            redirect(base_url() . IT_MODULE_FOLDER . 'receive_list');
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
            $crud->set_table(TBL_RECEIVES);
            $crud->set_relation('userId', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->where('requisitionId', $this->requisitionId);
            $crud->set_subject('Receive');

            $crud->columns('receiveNumber', 'userId', 'receiveDate');
            $crud->display_as('receiveId', 'Receive No.')
                ->display_as('receiveNumber', 'Receive No.')
                ->display_as('requisitionId', 'Requisition No.')
                ->display_as('quotationId', 'Quotation No.')
                ->display_as('userId', 'Received By')
                ->display_as('numberOfProduct', 'No. Of Products')
                ->display_as('receiveDescription', 'Description')
                ->display_as('receiveDate', 'Receive Date');

            $crud->add_fields('quotationId', 'requisitionId', 'requisition', 'quotation', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('quotationId', 'requisitionId', 'requisition', 'quotation', 'vendor', 'receiveNumber', 'userId', 'receiveDate', 'receiveDescription', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('requisition', 'quotation', 'receiveNumber', 'vendor', 'userId', 'receiveDate', 'receiveDescription', 'items');
            $crud->required_fields('quotationId', 'userId', 'receiveDate');
            $crud->unset_texteditor('receiveDescription');
            $crud->field_type('receiveNumber', 'readonly');
            $crud->field_type('quotationId', 'hidden', $quotationId);
            $crud->field_type('requisitionId', 'hidden', $this->requisitionId);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('requisition', array($this, 'callback_field_requisition'));
            $crud->callback_field('quotation', array($this, 'callback_field_quotation'));
            $crud->callback_field('vendor', array($this, 'callback_field_vendor'));
            $crud->callback_field('items', array($this, 'callback_field_items'));
            $crud->callback_read_field('requisition', array($this, 'callback_read_field_requisition'));
            $crud->callback_read_field('quotation', array($this, 'callback_read_field_quotation'));
            $crud->callback_read_field('vendor', array($this, 'callback_read_field_vendor'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_receive'));
            $crud->callback_after_update(array($this, 'callback_after_update_receive'));
            $crud->callback_after_delete(array($this, 'callback_after_delete_receive'));

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

            $crud->unset_edit();
            $crud->unset_delete();

            if ($this->isAllProductReceived($this->requisitionId, $quotationId)) $crud->unset_add();

            $crud->add_action('Bill', '', '', 'ui-icon-calculator', array($this, 'callback_action_bill'));

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Receive Item";
            $output->base_url = base_url();
            $output->backToQuotationList = base_url() . IT_MODULE_FOLDER . 'quotation/index/' . $this->requisitionId;
            $output->body_template = "receive_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_requisition($row, $key){
        return $this->get_requisition_title($this->quotationId);
    }

    function callback_field_quotation($row, $key){
        return $this->get_quotation_title($this->quotationId);
    }

    function callback_field_vendor($row, $key){
        return $this->get_vendor_title($this->quotationId);
    }
    function callback_read_field_requisition($row, $key){
        return $this->get_requisition_title($this->quotationId);
    }

    function callback_read_field_quotation($row, $key){
        return $this->get_quotation_title($this->quotationId);
    }

    function callback_read_field_vendor($row, $key){
        return $this->get_vendor_title($this->quotationId);
    }

    function callback_field_items($row, $key){
        $items = $this->get_quotation_items($this->quotationId);
        $totalReceivedQty = $this->get_total_received_qty($this->quotationId, $this->requisitionId);
        $receiveDetails = $this->get_receive_details($key);
        //var_dump($receiveDetails);
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li class="item">Item Name</li><li class="category">Category</li><li class="ordQuantity">Ordered Quantity</li><li class="recQuantity">Receive Quantity</li><li class="remQuantity">Remaining Quantity</li><li class="warranty">Warranty</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach ($items as $id => $item) {
            $totalRecQty = isset($totalReceivedQty[$item['itemId']]) ? $totalReceivedQty[$item['itemId']] : 0;
            $remQty = $item['quantity'] - $totalRecQty;
            $currentRecQty = isset($receiveDetails[$item['itemId']]) ? $receiveDetails[$item['itemId']]['qty'] : 0;
            $warranty = isset($receiveDetails[$item['itemId']]) ? $receiveDetails[$item['itemId']]['warranty'] : '';
            $id += 1;
            $html .= '<li><ul id="rowNum-' . $id . '">';
            $html .= '<li class="item">' . $item['item'] . '<small>('.$item['unit'].')</small></li>';
            $html .= '<li class="category">' . $item['category'] . '</li>';
            $html .= '<li class="ordQuantity">' . $item['quantity'] . '</li>';
            $html .= '<li class="recQuantity"><input type="number" id="item-' . $id . '-recQuantity" name="recQty[]" min="0" max="' . ($remQty + $currentRecQty) . '" value="' . $currentRecQty . '"/><span class="already-received">+' . abs($totalRecQty-$currentRecQty) . '</span></li>';
            $html .= '<li class="remQuantity">' . $remQty . '</li>';
            $html .= '<li class="warranty"><input type="text" id="item-' . $id . '-warranty" name="warranty[]" value="' . $warranty . '"/></li>';
            $html .= '<input type="hidden" name="itemId[]" value="' . $item['itemId'] . '"/>';
            $html .= '</ul></li>';
        }
        $html .= '</ul>';
        return $html;
    }
    function callback_read_field_items($row, $key){
        $items = $this->get_quotation_items($this->quotationId);
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

    function callback_action_bill($key, $row){
        $billId= $this->is_bill_exist($key);
        if(!$billId)return base_url(IT_MODULE_FOLDER. 'bill/index').'/'.$key.'/add';
        else return base_url(IT_MODULE_FOLDER. 'bill/index').'/'.$key.'/edit/'.$billId;
    }

    function callback_after_insert_receive($post, $key){
        $this->db->select("c.companyId, c.companyCode")
            ->from(TBL_REQUISITIONS . ' as r ')
            ->join(TBL_COMPANIES . ' as c ', 'r.companyId=c.companyId')
            ->where('requisitionId', $this->requisitionId);
        $db = $this->db->get();
        if (!$db->num_rows()) {
            $companyId = 0;
            $code = '';
        } else {
            $row = $db->result();
            $code = $row[0]->companyCode;
            $companyId = $row[0]->companyId;
        }
        $this->db->update(
            TBL_RECEIVES,
            array('receiveNumber' => '' . $code . '/RECV/' . mdate("%y", time()) . '/' . $key),
            array('receiveId' => $key)
        );

        $itemIds = $post['itemId'];
        $recQty = $post['recQty'];
        $warranty = $post['warranty'];
        foreach ($itemIds as $index => $item) {
            $itemCode= $this->it_inventory_model->get_item_code_by_itemId($item);
            $data = array(
                'receiveId' => $key,
                'itemMasterId' => $item,
                'receiveQuantity'=> $recQty[$index],
                'warrantyEndDate' => $warranty[$index]
            );
            if($this->it_inventory_model->isCountable($item)){
                for($i=1; $i<= $recQty[$index]; $i++){
                    $this->db->insert(TBL_RECEIVES_DETAIL, $data);
                    $insertId= $this->db->insert_id();
                    $this->db->update(
                        TBL_RECEIVES_DETAIL,
                        array('productCode'=> ''.$code.'/'.$itemCode.'/'.$insertId),
                        array('receiveDetailId'=>$insertId)
                    );
                }
            }
            else{
                if(!$this->it_inventory_model->isReceivePrior($this->requisitionId, $item)){
                    $this->db->insert(TBL_RECEIVES_DETAIL, $data);
                    $insertId= $this->db->insert_id();
                    $this->db->update(
                        TBL_RECEIVES_DETAIL,
                        array('productCode'=> ''.$code.'/'.$itemCode.'/'.$insertId),
                        array('receiveDetailId'=>$insertId)
                    );
                }
            }
            $this->add_to_stock($key, $item, $companyId, $code, $recQty[$index]);
        }
    }

    function callback_after_update_receive($post, $key){
        $this->db->select("c.companyId, c.companyCode")
            ->from(TBL_REQUISITIONS . ' as r ')
            ->join(TBL_COMPANIES . ' as c ', 'r.companyId=c.companyId')
            ->where('requisitionId', $this->requisitionId);
        $db = $this->db->get();
        if (!$db->num_rows()) {
            $companyId = 0;
            $code = '';
        } else {
            $row = $db->result();
            $code = $row[0]->companyCode;
            $companyId = $row[0]->companyId;
        }
        $itemIds = $post['itemId'];
        $recQty = $post['recQty'];
        $warranty = $post['warranty'];
        $currentRecDetails= $this->get_receive_details($key);
        foreach ($itemIds as $index => $item) {
            if($currentRecDetails[$item]['qty']>$recQty[$index]){
                $numRowsToBeDeleted= $currentRecDetails[$item]['qty'] - $recQty[$index];
                $this->db->limit($numRowsToBeDeleted);
                $this->db->delete(TBL_RECEIVES_DETAIL, array('receiveId'=>$key, 'itemMasterId'=>$item));
            }
            else{
                $numRowsToBeInserted= $recQty[$index] - $currentRecDetails[$item]['qty'];
                $itemCode= $this->it_inventory_model->get_item_code_by_itemId($item);
                $data = array(
                    'receiveId' => $key,
                    'itemMasterId' => $item,
                    'warrantyEndDate' => $warranty[$index]
                );
                for($i=1; $i<=$numRowsToBeInserted; $i++){
                    $this->db->insert(TBL_RECEIVES_DETAIL, $data);
                    $insertId= $this->db->insert_id();
                    $this->db->update(
                        TBL_RECEIVES_DETAIL,
                        array('productCode'=> ''.$code.'/'.$itemCode.'/'.$insertId),
                        array('receiveDetailId'=>$insertId)
                    );
                }
            }
            $this->add_to_stock($key, $item, $companyId, $code, $recQty[$index]);
        }
    }

    function callback_after_delete_receive($key){
        $currentRecDetails= $this->get_receive_details($key);
        foreach($currentRecDetails as $id=>$item){
            $this->db->where('itemMasterId', $id);
            $this->db->set(
                'stockQuantity',
                'CASE WHEN stockQuantity < '.$item['qty'].' THEN 0  ELSE stockQuantity - '.$item['qty'].' END',
                FALSE
            );
            $this->db->update(TBL_STOCK);
        }
        $this->db->delete(TBL_RECEIVES_DETAIL, array('receiveId' => $key));
    }

    /*******************************************************************************/
    function count_damage_qty($recId){
        $this->db->select('rd.itemMasterId, rd.receiveQuantity, count(rd.damageId) as damageQty, i.itemType');
        $this->db->from(TBL_RECEIVES_DETAIL.' as rd ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=rd.itemMasterId');
        $this->db->where('receiveId', $recId);
        $this->db->group_by('itemMasterId');
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[$row->itemMasterId] = array('qty'=>$row->receiveQuantity, 'warranty' => $row->warrantyEndDate);
        endforeach;
        return $array;
    }
    function get_requisitionId($quotId){
        if (!$quotId) return 0;
        $this->db->select("requisitionId")
            ->from(TBL_QUOTATIONS)
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        if (!$db->num_rows()) return 0;
        $row = $db->result();
        return $row[0]->requisitionId;
    }

    function get_requisition_title($quotId){
        if (!$quotId) return '';
        $this->db->select("r.requisitionTitle, r.requisitionNumber")
            ->from(TBL_QUOTATIONS . ' as q ')
            ->join(TBL_REQUISITIONS . ' as r', 'q.requisitionId= r.requisitionId')
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->requisitionTitle . ' [' . $row[0]->requisitionNumber . ']';
    }

    function get_quotation_title($quotId){
        if (!$quotId) return '';
        $this->db->select("quotationTitle, quotationNumber")
            ->from(TBL_QUOTATIONS)
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->quotationTitle . ' [' . $row[0]->quotationNumber . ']';
    }

    function get_vendor_title($quotId){
        if (!$quotId) return '';
        $this->db->select("v.vendorsName")
            ->from(TBL_QUOTATIONS . ' as q ')
            ->join(TBL_VENDORS . ' as v', 'q.vendorsId=v.vendorsId')
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        $row = $db->result();
        return $row[0]->vendorsName;
    }

    function get_quotation_items($quotationId = 0){
        if (!$quotationId) return array();
        $this->db->select("qd.*, i.itemName, c.categoryName, u.unitName")
            ->from(TBL_QUOTATIONS_DETAIL . ' as qd ')
            ->join(TBL_ITEMS_MASTER . ' as i', 'qd.itemMasterId=i.itemMasterId')
            ->join(TBL_CATEGORIES . ' as c', 'i.categoryId=c.categoryId')
            ->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId')
            ->where('quotationId', $quotationId);
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

    function get_total_received_qty($quotationId, $requisitionId = 0){
        if (!$requisitionId || !$quotationId) return array();

        $db = $this->db->query('select itemMasterId, sum(qty) as totalQty from (select d.itemMasterId, d.receiveQuantity as qty from ocl_receives AS r INNER JOIN ocl_receives_detail AS d ON r.receiveId = d.receiveId WHERE r.requisitionId = 100 and r.quotationId = 83 GROUP BY d.receiveId, d.itemMasterId ) AS t GROUP BY itemMasterId');

        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[$row->itemMasterId] = $row->totalQty;
        endforeach;
        return $array;
    }

    function get_receive_details($receiveId = 0){
        if (!$receiveId) return array();
        $this->db->select('itemMasterId, receiveQuantity, warrantyEndDate');
        $this->db->from(TBL_RECEIVES_DETAIL);
        $this->db->where('receiveId', $receiveId);
        $this->db->group_by('itemMasterId');
        $db = $this->db->get();
        if (!$db->num_rows()) return array();
        $array = array();
        foreach ($db->result() as $row):
            $array[$row->itemMasterId] = array('qty'=>$row->receiveQuantity, 'warranty' => $row->warrantyEndDate);
        endforeach;
        return $array;
    }

    function isAllProductReceived($reqId, $quotId){
        if (!$reqId || !$quotId) return false;
        $this->db->select("orderedQuantity, itemMasterId")
            ->from(TBL_QUOTATIONS_DETAIL)
            ->where('quotationId', $quotId);
        $db = $this->db->get();
        if (!$db->num_rows()) return false;
        $ordQty = array();
        foreach ($db->result() as $row):
            $ordQty[$row->itemMasterId] = $row->orderedQuantity;
        endforeach;
        $recQty = $this->get_total_received_qty($quotId, $reqId);
        if (!count($recQty)) return false;
        foreach ($recQty as $id => $qty):
            if ($ordQty[$id] != $qty) return false;
        endforeach;
        return true;
    }

    function add_to_stock($recId, $itemId, $comId, $companyCode, $qty){
        //$qty = $this->it_inventory_model->get_received_item_quantity($itemId);
        if (!$itemId || !$recId) return false;
        $this->db->select("stockId")
            ->from(TBL_STOCK)
            ->where('itemMasterId', $itemId);
        $db = $this->db->get();
        if (!$db->num_rows()) {
            $data = array(
                "itemMasterId" => $itemId,
                "companyId" => $comId,
                "stockQuantity" => $qty
            );
            $this->db->insert(TBL_STOCK, $data);
            $primary_key = $this->db->insert_id();
            $this->db->update(
                TBL_STOCK,
                array('stockNumber' => '' . $companyCode . '/STK/' . mdate("%y", time()) . '/' . $primary_key),
                array('stockId' => $primary_key)
            );
        } else {
            $this->db->where('itemMasterId', $itemId);
            $this->db->set('stockQuantity', 'stockQuantity+'.$qty, FALSE);
            $this->db->update(TBL_STOCK);
        }
        return false;
    }
    function is_bill_exist($recId){
        if(!$recId)return 0;
        $this->db->select('billId');
        $this->db->from(TBL_BILL);
        $this->db->where('receiveId', $recId);
        $db= $this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->billId;
    }
}
?>