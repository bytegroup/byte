<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/27/15
 * Time: 3:05 PM
 */
?>
<?php
class Product_Details_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $this->db->select('sd.stockDetailId, im.itemType, c.companyName, sd.productCode, cat.categoryName, im.itemName, rd.receiveQuantity, s.issueQuantity, sum(iud.issueQuantity) as uncountableIssueQuantity, id.issueDetailId, s.stockQuantity, qd.productBrand, d.departmentName, unit.unitName, rec.receiveDate, rd.warrantyEndDate, v.vendorsName, qd.unitPrice, i.issueDate, issueFor.firstName rFirstName, issueFor.middleName rMiddleName, issueFor.lastName rLastName');
        $this->db->from(TBL_STOCK_DETAIL.' as sd ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_UNITS.' as unit ', 'unit.unitId=im.unitId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as rec ', 'rec.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId and qd.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_ISSUE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId', 'left');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as iud ', 'iud.stockDetailId=sd.stockDetailId', 'left');
        $this->db->join(TBL_ISSUES.' as i ', 'i.issueId=id.issueId OR i.issueId=iud.issueId', 'left');

        if($filters['department'])$this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId and d.departmentId='.$filters['department']);
        else $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId', 'left');

        $this->db->join(TBL_USERS.' as issueFor ', 'issueFor.userId=i.issueUserId', 'left');
        $this->db->group_by('sd.stockDetailId');

        if($filters['company'])$this->db->where('c.companyId', $filters['company']);
        if($filters['category'])$this->db->where('cat.categoryId', $filters['category']);
        if($filters['item']) $this->db->where('im.itemMasterId', $filters['item']);
        if($filters['vendor'])$this->db->where('v.vendorsId', $filters['vendor']);
        if($filters['tracking_no'])$this->db->where('sd.productCode', $filters['tracking_no']);

        return $this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('sd.stockDetailId, im.itemType, c.companyName, sd.productCode, cat.categoryName, im.itemName, rd.receiveQuantity, s.issueQuantity, sum(iud.issueQuantity) as uncountableIssueQuantity, id.issueDetailId, s.stockQuantity, qd.productBrand, d.departmentName, unit.unitName, rec.receiveDate, rd.warrantyEndDate, v.vendorsName, qd.unitPrice, i.issueDate, issueFor.firstName rFirstName, issueFor.middleName rMiddleName, issueFor.lastName rLastName');
        $this->db->from(TBL_STOCK_DETAIL.' as sd ');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_UNITS.' as unit ', 'unit.unitId=im.unitId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as rec ', 'rec.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId and qd.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_ISSUE_DETAIL.' as id ', 'id.stockDetailId=sd.stockDetailId', 'left');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as iud ', 'iud.stockDetailId=sd.stockDetailId', 'left');
        $this->db->join(TBL_ISSUES.' as i ', 'i.issueId=id.issueId OR i.issueId=iud.issueId', 'left');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId', 'left');
        $this->db->join(TBL_USERS.' as issueFor ', 'issueFor.userId=i.issueUserId', 'left');
        $this->db->group_by('sd.stockDetailId');
        return $this->db->get();
    }

    /*
     * TODO :
     * Net Issue Quantity = Issue Quantity - Damage from Issue Quantity
     * Net Stock Quantity = Stock Quantity - Issue Quantity - Damage from Stock Quantity
    */
    function get_data($filters=array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $isCountable= $row->itemType==='Countable' ? true: false;
            $startDate  = date_create($row->receiveDate);
            $endDate    = date_create($row->warrantyEndDate);
            $period   = $endDate > $startDate ? date_diff($startDate, $endDate)->format('%a days') : '0 days';
            $array[$row->stockDetailId]= array(
                ++$i, // SL
                $row->companyName,//Company
                $row->productCode,//Tracking No.
                $row->categoryName,//Product Category
                $row->itemName,//Product Name
                $isCountable ? 1 : $row->receiveQuantity,//Quantity
                $isCountable ? $row->issueDetailId ? 1 : 0 : $row->uncountableIssueQuantity,//Issued Quantity
                $isCountable ? $row->issueDetailId ? 0 : 1 : $row->receiveQuantity - $row->uncountableIssueQuantity,//Stock Quantity
                $row->productBrand,//Brand
                '',//Current Location
                $row->departmentName,//Department
                $row->unitName,//Unit
                $row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName,//Current User
                $row->receiveDate,//Purchase Date
                $period,//Warranty
                $row->warrantyEndDate,//Warranty End Date
                $row->vendorsName,//Purchase Vendor
                $isCountable ? $row->unitPrice : $row->receiveQuantity * $row->unitPrice,//Purchase Price
                $row->issueDate,//Issue Date
                '',//Last Repair Date
                '',//No. Of Repair
                '',//Total Repair Cost
                '',//Total Cost
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Company',
            'Tracking No.',
            'Product Category',
            'Product Name',
            'Quantity',
            'Issued Quantity',
            'Stock Quantity',
            'Brand',
            'Current Location',
            'Department',
            'Unit',
            'Current User',
            'Purchase Date',
            'Warranty',
            'Warranty End Date',
            'Purchase Vendor',
            'Purchase Price',
            'Issue Date',
            'Last Repair Date',
            'No. Of Repair',
            'Total Repair Cost',
            'Total Cost',
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
    function get_vendor_list(){
        $db= $this->db->get(TBL_VENDORS);
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->vendorsId]= $row->vendorsName;
        }
        return $array;
    }

    function get_filters(){
        return array(
            'Company'       => array('select', $this->get_company_list()),
            'Department'    => array('select', $this->get_department_list()),
            'Category'      => array('select', $this->get_categories_list()),
            'Item'          => array('select', $this->get_item_list()),
            'Vendor'        => array('select', $this->get_vendor_list()),
            'Tracking No'   => 'text'
        );
    }
}
?>