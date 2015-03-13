<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/3/15
 * Time: 7:15 PM
 */
?>
<?php
class Quotation_List_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    private function _data_with_filter($filters){
        $fromDate= !$filters['from_date'] ? '': mdate('%y-%m-%d', strtotime($filters['from_date']));
        $toDate  = !$filters['to_date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['to_date']));
        $this->db->select('c.companyName, q.quotationId, q.quotationNumber, q.quotationDate, q.quotationDescription, v.vendorsName, r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, q.createDate, q.editDate');
        $this->db->from(TBL_QUOTATIONS.' as q ');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');

        if(!$filters['department']) $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        else $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId and d.departmentId = '.$filters['department']);

        if($filters['category'] || $filters['item']){
            $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId');
            if(!$filters['item'])$this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId');
            else $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId and i.itemMasterId='.$filters['item']);
            if($filters['category'])$this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=i.categoryId and cat.categoryId='.$filters['category']);
        }

        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=q.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=q.editorId', 'left');

        if(!$fromDate)$this->db->where('q.quotationDate <= ', $toDate);
        else $this->db->where('q.quotationDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" ');

        if($filters['company'])$this->db->where('c.companyId', $filters['company']);
        if($filters['vendor']) $this->db->where('v.vendorsId', $filters['vendor']);

        return $this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('c.companyName, q.quotationId, q.quotationNumber, q.quotationDate, q.quotationDescription, v.vendorsName, r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, q.createDate, q.editDate');
        $this->db->from(TBL_QUOTATIONS.' as q ');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=q.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=q.editorId', 'left');
        return $this->db->get();
    }

    function get_data($filters= array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $reqFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
            $array[$row->quotationId]= array(
                ++$i,
                $row->quotationNumber,
                $row->quotationDate,
                $row->requisitionNumber,
                $row->requisitionTitle,
                $row->requisitionCreateDate,
                $row->vendorsName,
                $row->quotationDescription,
                $row->companyName,
                $reqFor,
                $row->cFirstName.' '.$row->cMiddleName.' '.$row->cLastName,
                $row->createDate,
                $row->eFirstName.' '.$row->eMiddleName.' '.$row->eLastName,
                $row->editDate
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Quo. No.',
            'Quo. Date',
            'Req. No.',
            'Req. Title',
            'Req. Date',
            'Vendor Name',
            'Description',
            'Company Name',
            'Req. For',
            'Created By',
            'Create Date',
            'Edited By',
            'Edit Date'
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
    public function get_filters(){
        return array(
            'From Date'     => 'date',
            'To Date'       => 'date',
            'Company'       => array('select', $this->get_company_list()),
            'Department'    => array('select', $this->get_department_list()),
            'Category'      => array('select', $this->get_categories_list()),
            'Item'          => array('select', $this->get_item_list()),
            'Vendor'        => array('select', $this->get_vendor_list())
        );
    }
}
?>