<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Issue_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function get_company_name_by_stock_id($stockId){
        if(!$stockId)return array();
        $this->db->select('c.companyId, c.companyName');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 's.companyId=c.companyId');
        $this->db->where('s.stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        return $db->result()[0]->companyName;
    }
    public function get_stock_quantity($stockId){
        if(!$stockId)return array();
        $this->db->select('stockQuantity');
        $this->db->from(TBL_STOCK);
        $this->db->where('stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        return $db->result()[0]->stockQuantity;
    }
    public function get_issue_item_header($stockId){
        if(!$stockId)return array();
        $this->db->select('s.stockNumber, i.itemName, c.categoryName, u.unitName');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 's.itemMasterId=i.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'i.categoryId=c.categoryId');
        $this->db->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId');
        $this->db->where('stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        $array= array();
        return $db->result()[0];
    }
    public function get_issue_items_by_stock_id($stockId, $issueId=0){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, v.vendorsName, sd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        if($issueId)$this->db->join(TBL_ISSUE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId and id.issueId='.$issueId);
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('s.stockId', $stockId);
        if(!$issueId)$this->db->where('sd.active', true);
        else $this->db->where('id.active', true);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('issuedId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate);
        endforeach;
        return $array;
    }
    public function get_preIssued_items_by_issue_id($issueId){
        if(!$issueId)return array();
        $this->db->select('*');
        $this->db->from(TBL_ISSUE_DETAIL.' as id ');
        $this->db->where('id.issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows()) return array();
        $array=array();
        foreach($db->result() as $row){
            $array[]= array('stockDetailId'=>$row->stockDetailId);
        }
        return $array;
    }
    public function get_uncountable_stock($stockId){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, v.vendorsName, sd.productCode, sd.activeAmount, rd.warrantyEndDate');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('s.stockId', $stockId);
        $this->db->where('sd.active', true);
        //$this->db->where('sd.activeAmount > 0');
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('issuedId'=>$row->stockDetailId, 'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'qty'=>$row->activeAmount);
        endforeach;
        return $array;
    }
    public function get_uncountable_issued_items_qty($stockId, $issueId=0){
        if(!$stockId)return array();
        $this->db->select('id.stockDetailId, sum(id.issueQuantity) as issueQty');
        $this->db->from(TBL_STOCK.' as s ');
        if($issueId)$this->db->join(TBL_ISSUES.' as i ', 'i.stockId=s.stockId and i.issueId='.$issueId);
        else $this->db->join(TBL_ISSUES.' as i ', 'i.stockId=s.stockId');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.issueId=i.issueId');
        $this->db->where('s.stockId', $stockId);
        $this->db->where('id.active', true);
        $this->db->group_by('id.stockDetailId');
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[$row->stockDetailId ]= $row->issueQty;
        endforeach;
        return $array;
    }
    public function get_uncountable_stockDamaged_items_qty($stockId){
        if(!$stockId)return array();
        $this->db->select('dd.stockDetailId, sum(dd.damageQuantity) as qty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_DAMAGE.' as d ', 'd.stockId=s.stockId and d.issueId=0');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageId=d.damageId');
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
    public function get_uncountable_issueDamaged_items_qty($stockId, $issueId){
        if(!$stockId || !$issueId)return array();
        $this->db->select('dd.stockDetailId, sum(dd.damageQuantity) as qty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_DAMAGE.' as d ', 'd.stockId=s.stockId and d.issueId='.$issueId);
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageId=d.damageId');
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
    public function get_uncountable_damage_qty($issueId){
        if(!$issueId)return array();
        $this->db->select('dd.stockDetailId, sum(dd.damageQuantity) as qty');
        $this->db->from(TBL_DAMAGE.' as d ');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageId=d.damageId');
        $this->db->where('d.issueId', $issueId);
        $this->db->group_by('dd.stockDetailId');
        $db=$this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]=$row->qty;
        }
        return $array;
    }
    function get_company_code_stockId($stockId){
        if(!$stockId) return '';
        $this->db->select('c.companyCode');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->where('s.stockId', $stockId);
        $qry= $this->db->get();
        if(!$qry->num_rows()) return '';
        return $qry->result()[0]->companyCode;
    }
}

?>