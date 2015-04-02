<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Stock_Damage_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_stock_items($stockId, $damageId=0, $isCountable=true){
        if(!$stockId) return array();
        $this->db->select('sd.stockDetailId, sd.productCode, sd.active, rd.receiveQuantity, rd.warrantyEndDate, v.vendorsName, dd.damageId, dd.damageQuantity, dd.damageType');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=sd.stockDetailId', 'left', NULL);
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('s.stockId', $stockId);
        if(!$damageId)$this->db->where('sd.active', true);
        else $this->db->where('sd.active = true OR sd.stockDetailId IN (SELECT stockDetailId FROM '.TBL_DAMAGE_DETAIL.' WHERE damageId='.$damageId.' and active=true)');
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'damageId'=>$row->damageId, 'recQty'=> $row->receiveQuantity, 'damageQty'=>$row->damageQuantity, 'type'=>$row->damageType, 'active'=>$row->active);
        endforeach;
        return $array;
    }
    public function get_uncountable_stock_items($stockId, $damageId=0){
        if(!$stockId) return array();
        $this->db->select('sd.stockDetailId, sd.productCode, rd.warrantyEndDate, v.vendorsName, sd.activeAmount');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockId=s.stockId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('s.stockId', $stockId);
        $this->db->where('sd.active', true);
        $this->db->where('sd.activeAmount > 0 ');
        $db= $this->db->get();

        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'stockQty'=> $row->activeAmount);
        endforeach;
        return $array;
    }
    public function get_uncountable_damaged_items($damageId=0){
        if(!$damageId) return array();
        $this->db->select('sd.stockDetailId, sd.productCode, rd.warrantyEndDate, v.vendorsName, sd.activeAmount, dd.damageType, dd.damageQuantity');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('dd.damageId', $damageId);
        $this->db->where('dd.active', true);
        $this->db->where('sd.activeAmount > 0 ');
        $db= $this->db->get();

        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'stockQty'=> $row->activeAmount, 'type'=>$row->damageType, 'damageQty'=>$row->damageQuantity);
        endforeach;
        return $array;
    }
    public function get_issue_Qty($stockId){
        if(!$stockId)return array();
        $this->db->select('id.stockDetailId, sum(id.issueQuantity) as qty');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ISSUES.' as i ', 'i.stockId=s.stockId');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ', 'id.issueId=i.issueId');
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

    public function html_for_countable_add_field($stockId){
        $items= $this->get_stock_items($stockId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']"><option value="Permanent-Damage" selected>Permanent</option><option value="Repairable-Damage">Repairable</option></select></li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_uncountable_add_field($stockId){
        $items= $this->get_uncountable_stock_items($stockId);

        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['stockQty'];
            if(!$remQty)continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$remQty.'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['stockDetailId'].'" name="qty['.$item['stockDetailId'].']" min="0" max="'.$remQty.'" value="" /></li>';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']"><option value="Permanent-Damage">Permanent</option><option value="Repairable-Damage">Repairable</option></select></li>';
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
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $checked= !$item['active'] ? 'checked':'';
            $options= isset($item['type'])? $this->typeOptions($item['type']):$this->typeOptions('');
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" name="selectedItems[]" '.$checked.' value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']">'.$options.'</select></li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_uncountable_edit_field($stockId, $damageId){
        $items= $this->get_uncountable_damaged_items($damageId);

        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $checked= isset($item['damageQty'])?'checked=true':'';
            $options= isset($item['type'])?$this->typeOptions($item['type']):$this->typeOptions('');
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" '.$checked.' id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$item['stockQty'].'</li>';
            $html .= '<li><input type="number" id="qty-'.$item['stockDetailId'].'" name="qty['.$item['stockDetailId'].']" min="0" max="'.($item['stockQty']+$item['damageQty']).'" value="'.$item['damageQty'].'" /></li>';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']">'.$options.'</select></li>';
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
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            if($item['damageId']!=$damageId) continue;
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$item['type'].'</li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function html_for_uncountable_read_field($stockId, $damageId){
        $items= $this->get_uncountable_damaged_items($damageId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Damage Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['stockQty'];
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li>'.$remQty.'</li>';
            $html .= '<li>'.$item['damageQty'].'</li>';
            $html .= '<li>'.explode('-',$item['type'])[0].'</li>';
            $html .= '<li>'.$item['warranty'].'</li>';
            $html .= '<li>'.$item['vendor'].'</li>';
            $html .= '</ul>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        return $html;
    }
    public function typeOptions($selected=''){
        $options= ($selected==='Repairable-Damage') ?
            '<option value="Permanent-Damage">Permanent</option><option value="Repairable-Damage" selected >Repairable</option>'
            :
            '<option value="Permanent-Damage" selected >Permanent</option><option value="Repairable-Damage">Repairable</option>';
        return $options;
    }
    public function get_damaged_items($damageId=0){
        $this->db->select('dd.stockDetailId, dd.damageQuantity');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        if($damageId)$this->db->where('dd.damageId', $damageId);
        $this->db->where('dd.active', true);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]= $row->damageQuantity;
        }
        return $array;
    }
}

?>