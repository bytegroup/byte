<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/15/15
 * Time: 3:56 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class It_Inventory_Model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * @param int $itemId
     * @return bool
     */
    public function get_received_item_quantity($itemId=0){
        $this->db->select("itemMasterId, receiveQuantity as qty");
        $this->db->from(TBL_RECEIVES_DETAIL);
        if($itemId)$this->db->where('itemMasterId', $itemId);
        $this->db->group_by('itemMasterId');
        $db = $this->db->get();
        if(!$db->num_rows())return false;
        $array=array();
        foreach($db->result() as $row):
            $array[$row->itemMasterId]=$row->qty;
        endforeach;
        return $array;
    }
    public function get_received_quantity_per_receive($itemId=0, $receiveId=0){
        if(!$itemId || !$receiveId)return 0;
        $this->db->select("itemMasterId, receiveQuantity");
        $this->db->from(TBL_RECEIVES_DETAIL);
        $this->db->where(array('itemMasterId'=> $itemId, 'receiveId' => $receiveId));
        $db = $this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->receiveQuantity;
    }
    public function get_item_code_by_itemId($itemId){
        if(!$itemId) return '';
        $this->db->select("itemMasterId, itemCode");
        $this->db->from(TBL_ITEMS_MASTER);
        $this->db->where('itemMasterId', $itemId);
        $db = $this->db->get();
        if(!$db->num_rows())return '';
        return $db->result()[0]->itemCode;
    }
    public function get_all_items_code_by_itemId($itemId){

    }
    public function get_item_code_by_stockId($stockId=0){
        if(!$stockId) return '';
        $this->db->select("i.itemMasterId, i.itemCode");
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 's.itemMasterId=i.itemMasterId');
        $this->db->where('stockId', $stockId);
        $db = $this->db->get();
        if(!$db->num_rows())return '';
        return $db->result()[0]->itemCode;
    }
    public function get_all_items_code_by_stockId(){

    }
    public function get_stockId_by_itemId($itemId, $companyId){
        if(!$itemId||!$companyId) return 0;
        $this->db->select("stockId");
        $this->db->from(TBL_STOCK);
        $this->db->where(array('itemMasterId'=> $itemId, 'companyId'=>$companyId));
        $db = $this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->stockId;
    }
    public function get_itemId_by_stockId(){

    }
    public function isCountable($itemId){
        if(!$itemId)return false;
        $this->db->select('itemType');
        $this->db->from(TBL_ITEMS_MASTER);
        $this->db->where('itemMasterId', $itemId);
        $db= $this->db->get();
        if(!$db->num_rows())return false;
        if($db->result()[0]->itemType==='Countable')return true;
        else return false;
    }
    public function isCountableStock($stockId){
        if(!$stockId)return false;
        $this->db->select('i.itemType');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->where('s.stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows())return false;
        if($db->result()[0]->itemType==='Countable')return true;
        else return false;
    }
    public function isCountableIssue($issueId){
        if(!$issueId)return false;
        $this->db->select('im.itemType');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=i.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->where('i.issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows())return false;
        if($db->result()[0]->itemType==='Countable')return true;
        else return false;
    }
    public function isReceivePrior($reqId, $itemId){
        if(!$reqId || !$itemId) return false;
        $this->db->select('productCode');
        $this->db->from(TBL_RECEIVES_DETAIL);
        $this->db->where(array('requisitionId'=>$reqId, 'itemMasterId'=>$itemId));
        $db= $this->db->get();
        if(!$db->num_rows())return false;
        else return true;
    }
    public function add_to_stock($recId, $itemId, $comId, $companyCode, $qty){
        if (!$itemId || !$comId) return 0;
        $stockId=0;
        $this->db->select("stockId")
            ->from(TBL_STOCK)
            ->where(array('itemMasterId'=> $itemId, 'companyId'=>$comId));
        $db = $this->db->get();
        if (!$db->num_rows()) {
            $data = array(
                "itemMasterId" => $itemId,
                "companyId" => $comId,
                "stockQuantity" => $qty
            );
            $this->db->insert(TBL_STOCK, $data);
            $stockId = $this->db->insert_id();
            $this->db->update(
                TBL_STOCK,
                array('stockNumber' => '' . $companyCode . '/STK/' . mdate("%y", time()) . '/' . $stockId),
                array('stockId' => $stockId)
            );
        } else {
            $stockId= $db->result()[0]->stockId;
            $this->db->where('stockId', $stockId);
            $this->db->set('stockQuantity', 'stockQuantity+'.$qty, FALSE);
            $this->db->update(TBL_STOCK);
        }
        return $stockId;
    }
}

?>