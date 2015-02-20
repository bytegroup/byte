<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Stock_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_stock_items($stockId, $damageId=0, $isCountable=true){
        if(!$stockId) return array();
        $this->db->select('sd.stockDetailId, sd.productCode, rd.receiveQuantity, rd.warrantyEndDate, v.vendorsName, dd.damageId, dd.damageQuantity');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=sd.stockDetailId', 'left', NULL);
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('s.stockId', $stockId);
        $this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_ISSUE_DETAIL.')', NULL, FALSE);
        if($damageId){
            if($isCountable)$this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.' where damageId!='.$damageId.')', NULL, FALSE);
            else{
                $this->db->where('sd.stockDetailId IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.' where damageId='.$damageId.') and dd.damageId='.$damageId, NULL, FALSE);
            }
        }
        else {
            if($isCountable)$this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.')', NULL, FALSE);
            //else $this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.')', NULL, FALSE);
        }
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'damageId'=>$row->damageId, 'recQty'=> $row->receiveQuantity, 'damageQty'=>$row->damageQuantity);
        endforeach;
        return $array;
    }
    public function get_issue_Qty($stockId){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, sum(id.issueQuantity) as qty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId');
        $this->db->where('s.stockId', $stockId);
        $this->db->group_by('id.stockDetailId');
        $db=$this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]=$row->qty;
        }
        return $array;
    }
    public function get_damage_qty($stockId){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, sum(dd.damageQuantity) as qty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=sd.stockDetailId and issueId=0');
        $this->db->where('s.stockId', $stockId);
        $this->db->group_by('dd.stockDetailId');
        $db=$this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]=$row->qty;
        }
        return $array;
    }
}

?>