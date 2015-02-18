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
        if(!$issueId)$this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_ISSUE_DETAIL.')',NULL,FALSE);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('issuedId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate);
        endforeach;
        return $array;
    }
    public function get_uncountable_stock_items($stockId){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, v.vendorsName, sd.productCode, rd.warrantyEndDate, rd.receiveQuantity');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'rd.receiveId=r.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'r.quotationId=q.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'q.vendorsId=v.vendorsId');
        $this->db->where('s.stockId', $stockId);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $totalIssued= $this->get_issued_uncountable_stock($stockId);
        $array= array();
        foreach($db->result() as $row):
            if(isset($totalIssued[$row->stockDetailId])){
                if($totalIssued[$row->stockDetailId]==$row->receiveQuantity)continue;
                else $array[]= array('issuedId'=>$row->stockDetailId, 'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'remQty'=>$row->receiveQuantity-$totalIssued[$row->stockDetailId]);
            }else{
                $array[]= array('issuedId'=>$row->stockDetailId, 'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'remQty'=>$row->receiveQuantity);
            }
        endforeach;
        return $array;
    }
    public function get_issued_uncountable_stock($stockId, $issueId=0){
        if(!$stockId)return array();
        $this->db->select('sd.stockDetailId, sum(id.issueQuantity) as issueQty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        if($issueId)$this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId and id.issueId='.$issueId);
        else $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId');
        $this->db->where('s.stockId', $stockId);
        $this->db->group_by('id.stockDetailId');
        $db= $this->db->get();
        if(!$db->num_rows())return array();

        $array= array();
        foreach($db->result() as $row):
            $array[$row->stockDetailId ]= $row->issueQty;
        endforeach;
        return $array;
    }
}

?>