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

    private function _data_with_filter($filters){
        $date  = !$filters['date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['date']));
        $this->db->select('c.companyName, cat.categoryName, i.itemName, i.itemCode, i.itemDescription, s.stockId, s.stockQuantity, s.issueQuantity, s.damageQuantity');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=i.categoryId');

        if($filters['company'])$this->db->where('s.companyId', $filters['company']);
        if($filters['item'])$this->db->where('s.itemMasterId', $filters['item']);
        if($filters['category'])$this->db->where('cat.categoryId', $filters['category']);

        return $db=$this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('c.companyName, cat.categoryName, i.itemName, i.itemCode, i.itemDescription, s.stockId, s.stockQuantity, s.issueQuantity, s.damageQuantity');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=i.categoryId');

        return $db=$this->db->get();
    }

    function get_data($filters=array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[$row->stockId]= array(
                ++$i,
                $row->companyName,
                $row->categoryName,
                $row->itemName,
                $row->itemCode,
                $row->itemDescription,
                0,
                $row->stockQuantity,
                0,
                $row->issueQuantity,
                $row->damageQuantity,
                0
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Company Name',
            'Category',
            'Item Name',
            'Item Code',
            'Item Details',
            'Mini. Req.',
            'New',
            'Repaired',
            'Issued',
            'R. Damage',
            'P. Damage'
        );
    }

    function get_company_list(){
        $db= $this->db->get(TBL_COMPANIES);
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->companyId]= $row->companyName;
        }
        return $array;
    }
    function get_department_list($companyId=0){
        $db= !$companyId ?
            $this->db->get(TBL_DEPARTMENTS)
            :
            $this->db->get_where(TBL_DEPARTMENTS, array('companyId'=>$companyId));
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->departmentId]= $row->departmentName;
        }
        return $array;
    }
    function get_categories_list(){
        $db= $this->db->get(TBL_CATEGORIES);
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->categoryId]= $row->categoryName;
        }
        return $array;
    }
    function get_item_list($catId=0){
        $db= !$catId ?
            $this->db->get(TBL_ITEMS_MASTER)
            :
            $this->db->get_where(TBL_ITEMS_MASTER, array('categoryId'=>$catId));
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->itemMasterId]= $row->itemName;
        }
        return $array;
    }

    public function get_filters(){
        return array(
            'Company'       => array('select', $this->get_company_list()),
            'Department'    => array('select', $this->get_department_list()),
            'Category'      => array('select', $this->get_categories_list()),
            'Item'          => array('select', $this->get_item_list()),
            'Date'     => 'date'
        );
    }
}
?>