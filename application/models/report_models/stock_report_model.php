<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/10/15
 * Time: 5:07 PM
 */
?>
<?php
class Stock_Report_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    function get_data(){
        $this->db->select('c.companyName, cat.categoryName, i.itemName, i.itemCode, i.itemDescription, s.stockQuantity, s.issueQuantity, s.damageQuantity');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=i.categoryId');
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'=>++$i,
                'companyName'=>$row->companyName,
                'category'=>$row->categoryName,
                'itemName'=>$row->itemName,
                'itemCode'=>$row->itemCode,
                'itemDetails'=>$row->itemDescription,
                'MiniReq'=>0,
                'new'=>$row->stockQuantity,
                'repaired'=>0,
                'issued'=>$row->issueQuantity,
                'rDamage'=>$row->damageQuantity,
                'pDamage'=>0
            );
        endforeach;
        return $array;
    }
}
?>