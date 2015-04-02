<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/4/15
 * Time: 7:34 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issue_Damage extends MX_Controller {
    var $issueId=0;
    var $stockId=0;
    var $isCountable= true;
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model('issue_damage_model', 'damageModel');
        $this->load->model('it_inventory_model', 'itModel');
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index($issueId){
        if(!$issueId){
            redirect(base_url().IT_MODULE_FOLDER.'issue_list');
            die();
        }
        $this->issueId= $issueId;
        $this->stockId= $this->get_stock_id_by_issue_id($issueId);
        $this->isCountable=$this->itModel->isCountableStock($this->stockId);
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DAMAGE);
            $crud->set_relation("checkedById", TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->where('issueId', $issueId);
            $crud->set_subject('Issue Damage');

            $crud->columns('itemMasterId','damageType','damageQuantity','damageDate');
            $crud->display_as('itemMasterId','Product')
                ->display_as('stockNumber', 'Stock No.')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('checkedById', 'Checked By')
                ->display_as('damageDetails', 'Details')
                ->display_as('damageRemarks', 'Remarks');

            $crud->add_fields('stockId', 'issueId', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity', 'items');
            $crud->required_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageQuantity');
            $crud->unset_texteditor('damageDetails', 'damageRemarks');
            $crud->field_type('stockId', 'hidden', $this->stockId);
            $crud->field_type('issueId', 'hidden', $issueId);
            //$crud->field_type('damageType', 'enum', array('Repairable-Damage', 'Permanent-Damage'));
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_add_field('items', array($this, 'callback_add_field_items'));
            $crud->callback_edit_field('items', array($this, 'callback_edit_field_items'));
            $crud->callback_read_field('items', array($this, 'callback_read_field_items'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_damage'));
            $crud->callback_after_delete(array($this, 'callback_after_delete_damage'));
            $crud->callback_after_update(array($this, 'callback_after_update_damage'));

            if(!isset($this->my_session->permissions['canIT-InventoryAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryView'])){
                $crud->unset_read();
            }

            if($this->is_issue_empty($issueId))$crud->unset_add();

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->stockInfo= $this->get_stock_info($this->issueId);
            $output->isCountable=$this->isCountable;
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Damaged Products From Issue";
            $output->base_url = base_url();
            $output->backToIssueList= base_url(IT_MODULE_FOLDER.'issue_list');
            $output->body_template = "issue_damage_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/
    function callback_add_field_items($row, $key){
        if($this->isCountable)return $this->damageModel->html_for_countable_add_field($this->issueId);
        else return $this->damageModel->html_for_uncountable_add_field($this->issueId);
    }
    function callback_edit_field_items($row, $key){
        if($this->isCountable)return $this->damageModel->html_for_countable_edit_field($this->issueId, $key);
        else return $this->damageModel->html_for_uncountable_edit_field($this->issueId, $key);
    }
    function callback_read_field_items($row, $key){
        if($this->isCountable)return $this->damageModel->html_for_countable_read_field($this->issueId, $key);
        else return $this->damageModel->html_for_uncountable_read_field($this->issueId, $key);
    }
    function callback_after_insert_damage($post, $key){
        $damagedItems= $post['selectedItems'];
        $damageType= $post['damageType'];

        foreach($damagedItems as $index=>$id):
            $qty= $this->isCountable? 1: $post['qty'][$id];
            $this->db->insert(
                TBL_DAMAGE_DETAIL,
                array('damageId'=>$key, 'stockDetailId'=>$id, 'damageQuantity'=>$qty, 'damageType'=>$damageType[$id], 'issueId'=>$this->issueId, 'active'=>true)
            );
            if($this->isCountable)
                $this->db->update(TBL_ISSUE_DETAIL, array('active'=>false), array('issueId'=>$this->issueId, 'stockDetailId'=>$id));
            else $this->db->update(TBL_ISSUE_UNCOUNTABLE_DETAIL, array('active'=>false, 'issueQuantity'=>0), array('issueId'=>$this->issueId, 'stockDetailId'=>$id));
        endforeach;

        $this->db->where('stockId', $this->stockId);
        $this->db->set('issueQuantity', 'issueQuantity - '.$post['damageQuantity'], FALSE);
        $this->db->set('damageQuantityFromIssue', 'damageQuantityFromIssue + '.$post['damageQuantity'], FALSE);
        $this->db->update(TBL_STOCK);

        $this->db->where('issueId', $this->issueId);
        $this->db->set('issueQuantity', 'issueQuantity - '.$post['damageQuantity'], FALSE);
        $this->db->update(TBL_ISSUES);
    }
    function callback_after_update_damage($post, $key){
        $damageItems= $post['selectedItems'];
        $damageType= $post['damageType'];
        $preDamageQty= $post['preDamageQty']? $post['preDamageQty']:0;

        $currentlyDamagedItems= $this->damageModel->get_damage_from_issued_items($this->issueId, $key);
        foreach($currentlyDamagedItems as $itemId=>$damageQty){
            if($this->isCountable)$this->db->update(TBL_ISSUE_DETAIL, array('active'=>true), array('issueId'=>$this->issueId, 'stockDetailId'=>$itemId));
            else $this->db->update(TBL_ISSUE_UNCOUNTABLE_DETAIL, array('active'=>true, 'issueQuantity'=>$damageQty), array('issueId'=>$this->issueId, 'stockDetailId'=>$itemId));
        }
        $this->db->delete(TBL_DAMAGE_DETAIL, array('damageId'=>$key));

        foreach($damageItems as $index=>$id):
            $qty= $this->isCountable? 1: $post['qty'][$id];
            $this->db->insert(
                TBL_DAMAGE_DETAIL,
                array('damageId'=>$key, 'stockDetailId'=>$id, 'damageQuantity'=>$qty, 'damageType'=>$damageType[$id], 'issueId'=>$this->issueId, 'active'=>true)
            );
            if($this->isCountable)
                $this->db->update(TBL_ISSUE_DETAIL, array('active'=>false), array('issueId'=>$this->issueId, 'stockDetailId'=>$id));
            else $this->db->update(TBL_ISSUE_UNCOUNTABLE_DETAIL, array('active'=>false, 'issueQuantity'=>0), array('issueId'=>$this->issueId, 'stockDetailId'=>$id));
        endforeach;

        $currentDamageQty= $post['damageQuantity'];

        $qtyDeff=abs($preDamageQty-$currentDamageQty);
        if($preDamageQty > $currentDamageQty){
            $this->db->where('stockId', $this->stockId);
            $this->db->set('issueQuantity', 'issueQuantity + '.$qtyDeff, FALSE);
            $this->db->set('damageQuantityFromIssue', 'damageQuantityFromIssue - '.$qtyDeff, FALSE);
            $this->db->update(TBL_STOCK);

            $this->db->where('issueId', $this->issueId);
            $this->db->set('issueQuantity', 'issueQuantity + '.$qtyDeff, FALSE);
            $this->db->update(TBL_ISSUES);
        }
        else if ($preDamageQty < $currentDamageQty){
            $this->db->where('stockId', $this->stockId);
            $this->db->set('issueQuantity', 'issueQuantity - '.$qtyDeff, FALSE);
            $this->db->set('damageQuantityFromIssue', 'damageQuantityFromIssue + '.$qtyDeff, FALSE);
            $this->db->update(TBL_STOCK);

            $this->db->where('issueId', $this->issueId);
            $this->db->set('issueQuantity', 'issueQuantity - '.$qtyDeff, FALSE);
            $this->db->update(TBL_ISSUES);
        }
        else{}
    }
    function callback_after_delete_damage($key){
        $this->db->update(TBL_RECEIVES_DETAIL, array('damageId'=>0), array('damageId'=>$key));
        $qty= $this->db->affected_rows();

        $this->db->where('stockId', $this->stockId);
        $this->db->set('issueQuantity', 'issueQuantity + '.$qty, FALSE);
        $this->db->set('damageQuantityFromIssue', 'damageQuantityFromIssue - '.$qty, FALSE);
        $this->db->update(TBL_STOCK);

        $this->db->where('issueId', $this->issueId);
        $this->db->set('issueQuantity', 'issueQuantity + '.$qty, FALSE);
        $this->db->update(TBL_ISSUES);
    }

    /*************************************************************************/
    /**
     * @param $issueId
     * @return int
     */
    function get_stock_id_by_issue_id($issueId){
        if(!$issueId)return 0;
        $this->db->select('stockId');
        $this->db->from(TBL_ISSUES);
        $this->db->where('issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->stockId;
    }
    function get_stock_info($issueId){
        if(!$issueId) return array();
        $this->db->select('s.stockNumber, i.issueQuantity, im.itemName, c.categoryName, u.unitName');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=i.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=im.categoryId');
        $this->db->join(TBL_UNITS.' as u ', 'u.unitId=im.unitId');
        $this->db->where('i.issueId', $issueId);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(
            'stockNumber'   => $db->result()[0]->stockNumber,
            'qty'           => $db->result()[0]->issueQuantity,
            'item'          => $db->result()[0]->itemName,
            'category'      => $db->result()[0]->categoryName,
            'unit'          => $db->result()[0]->unitName
        );

        return $array;
    }

    function get_issue_quantity($issueId){
        if(!$issueId)return array();
        $this->db->select('issueQuantity');
        $this->db->from(TBL_ISSUES);
        $this->db->where('issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        return $db->result()[0]->issueQuantity;
    }
    function is_issue_empty($issueId=0){
        if(!$issueId)return false;
        if(!$this->get_issue_quantity($issueId))return true;
        else return false;
    }
}