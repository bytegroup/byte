<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/20/15
 * Time: 6:04 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Issue_Damage_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_countable_issued_items($issueId, $damageId=0){
        if(!$issueId) return array();
        $this->db->select('id.stockDetailId, sd.productCode, rd.warrantyEndDate, v.vendorsName, dd.damageId');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_ISSUE_DETAIL.' as id ', 'id.issueId=i.issueId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=id.stockDetailId', 'left');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('i.issueId', $issueId);
        if(!$damageId)$this->db->where('id.stockDetailId NOT IN (SELECT stockDetailId FROM '.TBL_DAMAGE_DETAIL.')');
        else $this->db->where('id.stockDetailId NOT IN (SELECT stockDetailId FROM '.TBL_DAMAGE_DETAIL.' where damageId!='.$damageId.')');
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'damageId'=>$row->damageId);
        endforeach;
        return $array;
    }
    function get_uncountable_issued_items($issueId, $damageId=0){
        if(!$issueId) return array();
        if($damageId)$this->db->select('id.stockDetailId, id.issueQuantity, sd.productCode, rd.warrantyEndDate, v.vendorsName, dd.damageQuantity');
        else $this->db->select('id.stockDetailId, id.issueQuantity, sd.productCode, rd.warrantyEndDate, v.vendorsName');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.issueId=i.issueId');
        if($damageId)$this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=id.stockDetailId and damageId='.$damageId);
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('i.issueId', $issueId);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        if($damageId){
            foreach($db->result() as $row):
                $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'issueQty'=>$row->issueQuantity, 'damageQty'=>$row->damageQuantity);
            endforeach;
        }else{
            foreach($db->result() as $row):
                $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'issueQty'=>$row->issueQuantity);
            endforeach;
        }

        return $array;
    }

    public function get_issue_Qty($issueId){
        if(!$issueId)return array();
        $this->db->select('stockDetailId, issueQuantity as qty');
        $this->db->from(TBL_ISSUE_UNCOUNTABLE_DETAIL);
        $this->db->where('issueId', $issueId);
        $db=$this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]=$row->qty;
        }
        return $array;
    }
    public function get_damage_qty($issueId){
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

    public function html_for_countable_add_field($issueId){
        $items= $this->get_countable_issued_items($issueId);
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
    public function html_for_uncountable_add_field($issueId){
        $items= $this->get_uncountable_issued_items($issueId);
        $damageQty= $this->get_damage_qty($issueId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['issueQty']-(isset($damageQty[$item['stockDetailId']])? $damageQty[$item['stockDetailId']]:0);
            if(!$remQty)continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$remQty.'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['stockDetailId'].'" name="qty[]" min="0" max="'.$remQty.'" value="" /></li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_countable_edit_field($issueId, $damageId){
        $items= $this->get_countable_issued_items($issueId, $damageId);
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
    public function html_for_uncountable_edit_field($issueId, $damageId){
        $items= $this->get_uncountable_issued_items($issueId, $damageId);
        $damageQty= $this->get_damage_qty($issueId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['issueQty']-((isset($issueQty[$item['stockDetailId']])? $issueQty[$item['stockDetailId']]:0) + (isset($damageQty[$item['stockDetailId']])? $damageQty[$item['stockDetailId']]:0));
            $checked= isset($item['damageQty'])?'checked=true':'';
            $damQty= isset($item['damageQty'])? $item['damageQty']:0;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" '.$checked.' id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$remQty.'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['stockDetailId'].'" name="qty[]" min="0" max="'.($remQty+$damQty).'" value="'.$damQty.'" /></li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_countable_read_field($issueId, $damageId){
        $items= $this->get_countable_issued_items($issueId, $damageId);
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
    public function html_for_uncountable_read_field($issueId, $damageId){
        $items= $this->get_uncountable_issued_items($issueId, $damageId);
        $damageQty= $this->get_damage_qty($issueId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['issueQty']-((isset($issueQty[$item['stockDetailId']])? $issueQty[$item['stockDetailId']]:0) + (isset($damageQty[$item['stockDetailId']])? $damageQty[$item['stockDetailId']]:0));
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$remQty.'</li>';
            $html .= '<li>'.$item['damageQty'].'</li>';
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