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
        $this->db->select('id.stockDetailId, sd.productCode, rd.warrantyEndDate, v.vendorsName, dd.damageType, dd.active');
        $this->db->from(TBL_ISSUE_DETAIL.' as id ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.stockDetailId=id.stockDetailId', 'left');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('id.issueId', $issueId);
        if(!$damageId)$this->db->where('id.active', true);
        else{
            $this->db->where('id.active = true OR id.stockDetailId IN (SELECT stockDetailId FROM '.TBL_DAMAGE_DETAIL.' where damageId='.$damageId.' and active = true )');
        }
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'type'=>$row->damageType, 'isDamaged'=>$row->active);
        endforeach;
        return $array;
    }
    function get_uncountable_issued_items($issueId, $damageId=0){
        if(!$issueId) return array();
        $this->db->select('id.stockDetailId, id.issueQuantity, sd.productCode, rd.warrantyEndDate, v.vendorsName');
        $this->db->from(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as id ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('id.issueId', $issueId);
        $this->db->where('id.active', true);

        $db = $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();

        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'issueQty'=>$row->issueQuantity);
        endforeach;

        return $array;
    }

    public function get_uncountable_damage_from_issued_items($damageId=0){
        $this->db->select('dd.stockDetailId, dd.damageQuantity, dd.damageType, sd.productCode, rd.warrantyEndDate, v.vendorsName');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=r.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('dd.damageId', $damageId);
        $this->db->where('dd.active', true);

        $db = $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();

        foreach($db->result() as $row):
            $array[]= array('stockDetailId'=>$row->stockDetailId ,'vendor'=>$row->vendorsName, 'productCode'=>$row->productCode, 'warranty'=>$row->warrantyEndDate, 'damageQty'=>$row->damageQuantity, 'type'=>$row->damageType);
        endforeach;

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
    public function html_for_uncountable_add_field($issueId){
        $items= $this->get_uncountable_issued_items($issueId);
        //$damageQty= $this->get_damage_qty($issueId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Issued Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $remQty= $item['issueQty'];
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$remQty.'</li>';
            $html .= '<input type="hidden" id="qty-'.$item['stockDetailId'].'" name="qty['.$item['stockDetailId'].']" min="0" max="'.$remQty.'" value="'.$remQty.'" />';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']"><option value="Permanent-Damage">Permanent</option><option value="Repairable-Damage">Repairable</option></select></li>';
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
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $checked= $item['isDamaged'] ? 'checked':'';
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
    public function html_for_uncountable_edit_field($issueId, $damageId){
        $items= $this->get_uncountable_damage_from_issued_items($damageId);
        //var_dump($items);
        //$damageQty= $this->get_damage_qty($issueId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $checked= isset($item['damageQty'])?'checked=true':'';
            $damQty= isset($item['damageQty'])? $item['damageQty']:0;
            $options= isset($item['type'])?$this->typeOptions($item['type']):$this->typeOptions('');
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li><input type="checkbox" '.$checked.' id="items-'.$item['stockDetailId'].'" name="selectedItems[]" value="'.$item['stockDetailId'].'"/></li>';
            $html .= '<li>'.$item['productCode'].'</li>';
            $html .= '<li id="remQty-'.$item['stockDetailId'].'">'.$damQty.'</li>';
            $html .= '<input type="hidden" id="qty-'.$item['stockDetailId'].'" name="qty['.$item['stockDetailId'].']" min="0" max="'.$damQty.'" value="'.$damQty.'" />';
            $html .= '<li><select name="damageType['.$item['stockDetailId'].']">'.$options.'</select></li>';
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
    public function html_for_uncountable_read_field($issueId, $damageId){
        $items= $this->get_uncountable_damage_from_issued_items($damageId);
        $html='';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Damage Quantity</li><li>Damage Type</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach($items as $item):
            $html .= '<li>';
            $html .= '<ul>';
            $html .= '<li>&nbsp;</li>';
            $html .= '<li>'.$item['productCode'].'</li>';
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
    public function get_damage_from_issued_items($issueId, $damageId=0){
        if(!$issueId) return array();
        $this->db->select('dd.damageQuantity, dd.stockDetailId');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->where('dd.issueId', $issueId);
        $this->db->where('dd.damageId', $damageId);
        $db = $this->db->get();
        if(!$db->num_rows()) return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->stockDetailId]= $row->damageQuantity;
        }
        return $array;
    }
    public function get_itemName($issueId){
        if(!$issueId) return '';
        $this->db->select('im.itemName');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=i.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->where('i.issueId', $issueId);
        $db = $this->db->get();
        if(!$db->num_rows()) return '';

        return $db->result()[0]->itemName;
    }
}

?>