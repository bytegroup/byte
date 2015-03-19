<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/6/15
 * Time: 12:47 PM
 */
?>
<?php
class Purchase_List_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $fromDate= !$filters['from_date'] ? '': mdate('%y-%m-%d', strtotime($filters['from_date']));
        $toDate  = !$filters['to_date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['to_date']));
        $this->db->select('c.companyName, rec.receiveId, rec.receiveNumber, rec.receiveDate, r.requisitionNumber, r.requisitionCreateDate, r.requisitionTitle, q.quotationNumber, q.quotationDate, v.vendorsName, b.billAmount, b.billNumber, b.invoiceNumber, b.invoiceDate,  creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, rec.createDate, rec.editDate');
        $this->db->from(TBL_RECEIVES.' as rec ');

        if(!$filters['bill_no'])$this->db->join(TBL_BILL.' as b ', 'b.receiveId= rec.receiveId', 'left');
        else $this->db->join(TBL_BILL.' as b ', 'b.receiveId=rec.receiveId and b.billNumber="'.$filters['bill_no'].'"');

        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');

        if($filters['department']) $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId and d.departmentId = '.$filters['department']);

        if($filters['category'] || $filters['item']){
            $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId');
            if(!$filters['item'])$this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId');
            else $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId and i.itemMasterId='.$filters['item']);
            if($filters['category'])$this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=i.categoryId and cat.categoryId='.$filters['category']);
        }

        if($filters['tracking_no']){
            $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveId=rec.receiveId');
            $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.receiveDetailId=rd.receiveDetailId and sd.productCode="'.$filters['tracking_no'].'"');
        }

        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=rec.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=rec.editorId', 'left');

        if(!$fromDate)$this->db->where('rec.receiveDate <= ', $toDate);
        else $this->db->where('rec.receiveDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" ');

        if($filters['company'])$this->db->where('c.companyId', $filters['company']);

        return $db=$this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('c.companyName, rec.receiveId, rec.receiveNumber, rec.receiveDate, r.requisitionNumber, r.requisitionCreateDate, r.requisitionTitle, q.quotationNumber, q.quotationDate, v.vendorsName, b.billAmount, b.billNumber, b.invoiceNumber, b.invoiceDate,  creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, rec.createDate, rec.editDate');
        $this->db->from(TBL_RECEIVES.' as rec ');
        $this->db->join(TBL_BILL.' as b ', 'b.receiveId= rec.receiveId', 'left');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=rec.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=rec.editorId', 'left');
        return $db=$this->db->get();
    }

    function get_data($filters= array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[$row->receiveId]= array(
                ++$i,
                $row->companyName,
                $row->receiveNumber,
                $row->receiveDate,
                $row->requisitionNumber,
                $row->requisitionCreateDate,
                $row->requisitionTitle,
                $row->quotationNumber,
                $row->quotationDate,
                $row->vendorsName,
                $row->invoiceNumber,
                $row->invoiceDate,
                $row->billAmount,
                $row->billNumber,
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
            'Company Name',
            'Receive No.',
            'Receive Date',
            'Req. No.',
            'Req. Date',
            'Req. Title',
            'Quo. No.',
            'Quo. Date',
            'Vendor Name',
            'Invoice No.',
            'Invoice Date',
            'Total Bill Amount',
            'Bill No.',
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

    public function get_filters(){
        return array(
            'From Date'     => 'date',
            'To Date'       => 'date',
            'Company'       => array('select', $this->get_company_list()),
            'Department'    => array('select', $this->get_department_list()),
            'Category'      => array('select', $this->get_categories_list()),
            'Item'          => array('select', $this->get_item_list()),
            'Tracking No'  => 'text',
            'Bill No'      => 'text'
        );
    }
}
?>