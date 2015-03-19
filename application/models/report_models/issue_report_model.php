<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/19/15
 * Time: 7:38 PM
 */
?>
<?php
class Issue_report_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $fromDate= !$filters['from_date'] ? '': mdate('%y-%m-%d', strtotime($filters['from_date']));
        $toDate  = !$filters['to_date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['to_date']));
        $this->db->select('c.companyName, r.requisitionId, r.requisitionNumber, r.requisitionTitle, r.requisitionDescription, r.requisitionCreateDate, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, r.createDate, r.editDate');
        $this->db->from(TBL_REQUISITIONS.' as r ');

        return $this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('i.issueNumber, i.issueDate, cat.categoryName, im.itemName, sd.productCode, iud.issueQuantity, rec.receiveDate, rd.warrantyEndDate, c.companyName, d.departmentName, issueFor.firstName rFirstName, issueFor.middleName rMiddleName, issueFor.lastName rLastName, issueBy.firstName bFirstName, issueBy.middleName bMiddleName, issueBy.lastName bLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, r.createDate, r.editDate');
        $this->db->from(TBL_ISSUES.' as i ');
        $this->db->join(TBL_ISSUE_DETAIL.' as id ', 'id.issueId=i.issueId', 'left');
        $this->db->join(TBL_ISSUE_UNCOUNTABLE_DETAIL.' as iud ', 'iud.issueId=i.issueId', 'left');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=id.stockDetailId OR sd.stockDetailId=iud.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as rec ', 'rec.receiveId=rd.receiveId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=i.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId', 'left');
        $this->db->join(TBL_USERS.' as issueFor ', 'issueFor.userId=i.issueUserId', 'left');
        $this->db->join(TBL_USERS.' as issueBy ', 'issueBy.userId=i.issueById', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=r.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=r.editorId', 'left');
        return $this->db->get();
    }

    function get_data($filters=array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $issueFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
            $array[$row->requisitionId]= array(
                'SL' => ++$i,
                'Issue No.' => $row->issueNumber,
                'Issue Date' => $row->issueDate,
                'Product Category' => $row->categoryName,
                'Product Name' => $row->itemName,
                'Tracking No.' => $row->productCode,
                'Quantity' => $row->issueQuantity,
                'Product Serial No.' => '',
                'Purchase Date' => $row->receiveDate,
                'Warranty End Date' => $row->warrantyEndDate,
                'Company Name' => $row->companyName,
                'Department Name' => $row->departmentName,
                'Issue To' => $row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName,
                'Issue By' => $row->bFirstName.' '.$row->bMiddleName.' '.$row->bLastName,
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
            'Issue No.',
            'Issue Date',
            'Product Category',
            'Product Name',
            'Tracking No.',
            'Quantity',
            'Product Serial No.',
            'Purchase Date',
            'Warranty End Date',
            'Company Name',
            'Department Name',
            'Issue To',
            'Issue By',
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

    function get_filters(){
        return array(
            'From Date'     => 'date',
            'To Date'       => 'date',
            'Company'       => array('select', $this->get_company_list()),
            'Department'    => array('select', $this->get_department_list()),
            'Category'      => array('select', $this->get_categories_list()),
            'Item'          => array('select', $this->get_item_list())
        );
    }
}
?>