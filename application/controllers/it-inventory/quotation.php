<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation extends MX_Controller {

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
            redirect(base_url().IT_MODULE_FOLDER.'requisition');
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
            $crud->edit_fields('requisitionId', 'quotationNumber', 'quotationTitle', 'quotationDescription', 'vendorsId', 'quotationDate', 'paymentType', 'quotationFile', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('requisitionId', 'quotationNumber', 'quotationTitle', 'quotationDescription', 'vendorsId', 'quotationDate', 'paymentType', 'quotationFile', 'items');
            $crud->required_fields(array('requisitionId','quotationTitle', 'vendorsId', 'quotationDate'));
            $crud->unique_fields('quotationTitle');
            $crud->unset_texteditor('quotationDescription');
            $crud->set_field_upload('quotationFile','assets/uploads/files');
            $crud->field_type('quotationNumber', 'readonly');
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
            $crud->add_action('Approve', '', '', 'ui-icon-check', array($this,'setApproveQuotationURL'));
            $crud->add_action('Receive', '', '', 'ui-icon-transferthick-e-w', array($this,'setReceiveURL'));

            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'quotation/index/'.$id).'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'quotation/index/'.$id).'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();
            $output->quotationFor= $this->get_requisition_title($id);
            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->requisitionId = $id;
            $output->pageTitle = "Quotations";
            $output->base_url = base_url();
            $output->backToRequisitionList=base_url().IT_MODULE_FOLDER.'requisition';
            
            $output->body_template = "quotation_view.php";
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
            return base_url().IT_MODULE_FOLDER.'approve_quotation/index/'.$value->requisitionId.'/'.$primary_key.'/edit/'.$approvedId;
        else return base_url().IT_MODULE_FOLDER.'approve_quotation/index/'.$value->requisitionId.'/'.$primary_key.'/add';
    }
    function  setReceiveURL($primary_key, $value){
        $this->db->select("quotationId")
            ->from(TBL_APPROVE_QUOTATION)
            ->where('approveQuotationId', $this->approvedQuotationId);
        $db = $this->db->get();
        if(!$db->num_rows())return '#';
        $row= $db->result();
        if($row[0]->quotationId == $primary_key)
            return base_url(IT_MODULE_FOLDER.'receive/index').'/'.$primary_key;
        else return '#';
    }

    function callback_column_approved($primary_key, $value){
        $approvedId= $this->get_approved_quotation_id($value->requisitionId);
        $approvedMsg=''
            .'<span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span><span style="font-size: 14px">&nbsp; Approved</span>';
        $notApprovedMsg= ''
            .'<span class="ui-button-icon-primary ui-icon ui-icon-circle-close"></span><span style="font-size: 14px">&nbsp; Not-approved</span>';

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
        $this->db->select("c.companyCode")
            ->from(TBL_REQUISITIONS.' as r ')
            ->join(TBL_COMPANIES.' as c ', 'r.companyId=c.companyId')
            ->where('requisitionId', $this->requisitionId);
        $db = $this->db->get();
        if(!$db->num_rows())$code='';
        else{
            $row = $db->result();
            $code = $row[0]->companyCode;
        }
        $this->db->update(
            TBL_QUOTATIONS,
            array('quotationNumber' => '' . $code . '/QUO/' . mdate("%y", time()) . '/' . $key),
            array('quotationId' => $key)
        );

        $items= $post['items'];
        foreach($items as $item){
            $data= array(
                'quotationId'       => $key,
                'itemMasterId'      => $item,
                'productBrand'      => $post['brand-item-'.$item],
                'productOrigin'     => $post['origin-item-'.$item],
                'productType'       => $post['type-item-'.$item],
                'productWarranty'   => $post['warranty-item-'.$item],
                'productRemarks'    => $post['remarks-item-'.$item],
                'orderedQuantity'   => $post['quantity-item-'.$item],
                'unitPrice'         => $post['unitprice-item-'.$item],
                'quotationPrice'    => $post['totalprice-item-'.$item]
            );
            $this->db->insert(TBL_QUOTATIONS_DETAIL, $data);
        }
    }
    function callback_after_update_quotation($post, $key){
        $items= $post['items'];
        $this->db->select("itemMasterId")
            ->from(TBL_REQUISITIONS_DETAIL)
            ->where('requisitionId', $this->requisitionId);
        $db = $this->db->get();
        $orderedItems = array();
        foreach($db->result() as $row):
            $orderedItems[] = $row->itemMasterId;
        endforeach;
        foreach($orderedItems as $orderedItem){
            if(!in_array($orderedItem, $items)){
                $this->db->delete(TBL_QUOTATIONS_DETAIL, array('quotationId' => $key, 'itemMasterId'=>$orderedItem));
            }
        }
        foreach($items as $item){
            $data= array(
                //'quotationId'     => $key,
                'productBrand'      => $post['brand-item-'.$item],
                'productOrigin'     => $post['origin-item-'.$item],
                'productType'       => $post['type-item-'.$item],
                'productWarranty'   => $post['warranty-item-'.$item],
                'productRemarks'    => $post['remarks-item-'.$item],
                'orderedQuantity'   => $post['quantity-item-'.$item],
                'unitPrice'         => $post['unitprice-item-'.$item],
                'quotationPrice'    => $post['totalprice-item-'.$item]
            );
            $ql = $this->db->select('quotationId, itemMasterId')
                ->from(TBL_QUOTATIONS_DETAIL)
                ->where('quotationId',$key)->where('itemMasterId', $item)
                ->get();
            if( $ql->num_rows() > 0 ) {
                $this->db->update(TBL_QUOTATIONS_DETAIL, $data, array('quotationId' => $key, 'itemMasterId'=>$item));
            }
            else {
                $data= array(
                    'quotationId'       => $key,
                    'itemMasterId'      => $item,
                    'productBrand'      => $post['brand-item-'.$item],
                    'productOrigin'     => $post['origin-item-'.$item],
                    'productType'       => $post['type-item-'.$item],
                    'productWarranty'   => $post['warranty-item-'.$item],
                    'productRemarks'    => $post['remarks-item-'.$item],
                    'orderedQuantity'   => $post['quantity-item-'.$item],
                    'unitPrice'         => $post['unitprice-item-'.$item],
                    'quotationPrice'    => $post['totalprice-item-'.$item]
                );
                $this->db->insert(TBL_QUOTATIONS_DETAIL, $data);
            }
        }
    }

    function callback_field_items($row, $key){
        $orderedProducts= $this->get_requisition_items($this->requisitionId);
        $quotationDetails= $this->get_quotation_details($key);

        $total=0.0;
        $productsCheckbox=''
            .'<ul>'
            .'<li><ul class="items-table-header">'
            .'<li>&nbsp</li><li>Brand</li><li>Origin</li><li>Type</li><li>Warranty</li><li>Remarks</li><li>Quantity</li><li>Unit Price</li><li>Total Price</li>'
            .'</ul></li>'
            .'';
        foreach($orderedProducts as $id=>$item){
            $flag       = isset($quotationDetails[$item['itemMasterId']])? true : false;
            $total     += $flag ? $quotationDetails[$item['itemMasterId']]['totalPrice']: 0;
            $checked    = $flag ? 'checked="true"' : '';
            $brand      = $flag ? $quotationDetails[$item['itemMasterId']]['brand'] : '';
            $origin     = $flag ? $quotationDetails[$item['itemMasterId']]['origin'] : '';
            $type       = $flag ? $quotationDetails[$item['itemMasterId']]['type'] : '';
            $warranty   = $flag ? $quotationDetails[$item['itemMasterId']]['warranty'] : '';
            $remarks    = $flag ? $quotationDetails[$item['itemMasterId']]['remarks'] : '';
            $unitPrice  = $flag ? $quotationDetails[$item['itemMasterId']]['unitPrice'] : '';
            $totalPrice = $flag ? $quotationDetails[$item['itemMasterId']]['totalPrice'] : '';

            $productsCheckbox.='<li><ul id="'.$item['itemMasterId'].'">';
            $productsCheckbox.='<li><input type="checkbox" '.$checked.' name="items[]" id="item-'.$item['itemMasterId'].'" value="'.$item['itemMasterId'].'"/> '.$item['itemMasterName'].'</li>';

            $productsCheckbox.='<li><input type="text" placeholder="Brand" name="brand-item-'.$item['itemMasterId'].'" id="brand-item-'.$item['itemMasterId'].'" value="'.$brand.'"/></li>';
            $productsCheckbox.='<li><input type="text" placeholder="Origin" name="origin-item-'.$item['itemMasterId'].'" id="origin-item-'.$item['itemMasterId'].'" value="'.$origin.'"/></li>';
            $productsCheckbox.='<li><input type="text" placeholder="Type" name="type-item-'.$item['itemMasterId'].'" id="type-item-'.$item['itemMasterId'].'" value="'.$type.'"/></li>';
            $productsCheckbox.='<li><input type="text" class="warranty-date" placeholder="Warranty" name="warranty-item-'.$item['itemMasterId'].'" id="warranty-item-'.$item['itemMasterId'].'" value="'.$warranty.'"/></li>';
            $productsCheckbox.='<li><input type="text" placeholder="Remarks" name="remarks-item-'.$item['itemMasterId'].'" id="remarks-item-'.$item['itemMasterId'].'" value="'.$remarks.'"/></li>';

            $productsCheckbox.='<li><input type="number" placeholder="Quantity" name="quantity-item-'.$item['itemMasterId'].'" min="0" id="quantity-item-'.$item['itemMasterId'].'" readonly value="'.$item['orderedQuantity'].'"/></li>';

            $productsCheckbox.='<li><input type="number" placeholder="Unit Price" name="unitprice-item-'.$item['itemMasterId'].'" min="0" id="unitprice-item-'.$item['itemMasterId'].'" value="'.$unitPrice.'" step=".01"/></li>';

            $productsCheckbox.='<li><input type="number" placeholder="Total Price" name="totalprice-item-'.$item['itemMasterId'].'" min="0" id="totalprice-item-'.$item['itemMasterId'].'" value="'.$totalPrice.'"/></li>';

            $productsCheckbox.='</ul></li>';
        }
        $productsCheckbox.='<li>Grand Total = <span id="items-grand-total">'.$total.'</span></li></ul>';
        return $productsCheckbox;
    }
    function callback_read_field_items($row, $key){
        $items= $this->get_quotation_items($key);
        $total= 0.0;
        $html='';

        $html .= '<ul class="read">';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Category</li><li>Item</li><li>Quantity</li><li>Unit Price</li><li>Total Price</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($items as $id=>$item):
            $total += $item['totalPrice'];
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>'.$item['cat'].'</li>';
            $html .= '<li>'.$item['item'].' <small>('.$item['unit'].')</small></li>';
            $html .= '<li>'.$item['quantity'].'</li>';
            $html .= '<li>'.$item['unitPrice'].'</li>';
            $html .= '<li>'.$item['totalPrice'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;

        $html .= '<li>Grand Total = <span id="items-grand-total">'.$total.'</span></li>';
        $html .= '</ul>';

        return $html;
    }

    function get_requisition_items($requisitionId){
        $this->db->select("i.itemMasterId, i.itemName, r.orderedQuantity")
            ->from(TBL_REQUISITIONS_DETAIL.' as r ')
            ->join(TBL_ITEMS_MASTER.' as i ', 'r.itemMasterId=i.itemMasterId')
            ->where('requisitionId', $requisitionId);
        $db = $this->db->get();
        $array = array();
        foreach($db->result() as $row):
            $array[] = array("itemMasterId" => $row->itemMasterId, "itemMasterName" => $row->itemName, 'orderedQuantity'=> $row->orderedQuantity);
        endforeach;
        return $array;
    }
    function get_quotation_details($quotationId){
        $this->db->select("*")
            ->from(TBL_QUOTATIONS_DETAIL)
            ->where('quotationId', $quotationId);
        $db = $this->db->get();
        $array = array();
        foreach($db->result() as $row):
            $array[$row->itemMasterId] = array(
                'brand'     => $row->productBrand,
                'origin'    => $row->productOrigin,
                'type'      => $row->productType,
                'warranty'  => $row->productWarranty,
                'remarks'   => $row->productRemarks,
                'unitPrice' => $row->unitPrice,
                'totalPrice'=> $row->quotationPrice
            );
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