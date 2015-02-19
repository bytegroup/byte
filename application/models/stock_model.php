<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Stock_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_stock_items($stockId, $damageId=0){
        if(!$stockId) return array();
        $this->db->select('sd.stockDetailId, sd.productCode, rd.warrantyEndDate, v.vendorsName, dd.damageId');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=sd.stockDetailId', 'left', NULL);
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('s.stockId', $stockId);
        $this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_ISSUE_DETAIL.')', NULL, FALSE);
        if($damageId)$this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.' where damageId!='.$damageId.')', NULL, FALSE);
        else $this->db->where('sd.stockDetailId NOT IN (select stockDetailId from '.TBL_DAMAGE_DETAIL.')', NULL, FALSE);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'damageId'=>$row->damageId);
        endforeach;
        return $array;
    }

    public function html_for_countable_add_field($stockId){
        $items= $this->get_stock_items($stockId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_uncountable_add_field($stockId){
        $items= $this->get_stock_items($stockId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>0</li>';
            $html .= '<li><input type="number" id="qty-'.$item['stockDetailId'].'" name="qty[]" min="0" value="" /></li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_countable_edit_field($stockId, $damageId){
        $items= $this->get_stock_items($stockId, $damageId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $checked= $item['damageId']==$damageId ? 'checked':'';
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" name="selectedItems[]" '.$checked.' value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_countable_read_field($stockId, $damageId){
        $items= $this->get_stock_items($stockId, $damageId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            if($item['damageId']!=$damageId) continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }

}

?>