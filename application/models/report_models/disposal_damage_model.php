<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/21/15
 * Time: 7:21 PM
 */
?>
<?php
class Disposal_Damage_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $fromDate= !$filters['from_date'] ? '': mdate('%y-%m-%d', strtotime($filters['from_date']));
        $toDate  = !$filters['to_date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['to_date']));
        $this->db->select('dis.disposalDate, dd.damageDetailId, i.issueDate, cat.categoryName, im.itemName, sd.productCode, dd.damageQuantity, dam.damageDate, qd.unitPrice, rec.receiveDate, c.companyName, d.departmentName, issueFor.userId, issueFor.firstName rFirstName, issueFor.middleName rMiddleName, issueFor.lastName rLastName, checked.firstName bFirstName, checked.middleName bMiddleName, checked.lastName bLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, hand.firstName hFirstName, hand.middleName hMiddleName, hand.lastName hLastName, dam.createDate');
        $this->db->from(TBL_DISPOSAL.' as dis ');
        $this->db->join(TBL_DISPOSAL_DETAILS.' as dDetail', 'dDetail.disposalId=dis.disposalId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=dDetail.damageDetailId');
        $this->db->join(TBL_DAMAGE.' as dam ', 'dam.damageId=dd.damageId');
        $this->db->join(TBL_ISSUES.' as i ', 'i.issueId=dam.issueId', 'left');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=dam.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as rec ', 'rec.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId and qd.itemMasterId=im.itemMasterId');

        if(!$filters['department'])$this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId', 'left');
        else $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId and d.departmentId = '.$filters['department']);

        $this->db->join(TBL_USERS.' as issueFor ', 'issueFor.userId=i.issueUserId', 'left');
        $this->db->join(TBL_USERS.' as hand ', 'hand.userId=dis.handoverTo', 'left');
        $this->db->join(TBL_USERS.' as checked ', 'checked.userId=dam.checkedById', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=dam.creatorId', 'left');
        $this->db->where('dd.damageType', 'Permanent-Damage');

        if(!$fromDate)$this->db->where('dam.damageDate <= ', $toDate);
        else $this->db->where('dam.damageDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" ');

        if($filters['company'])$this->db->where('c.companyId', $filters['company']);
        if($filters['category'])$this->db->where('cat.categoryId', $filters['category']);
        if($filters['item']) $this->db->where('im.itemMasterId', $filters['item']);
        if($filters['tracking_no'])$this->db->where('sd.productCode', $filters['tracking_no']);

        return $this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('dis.disposalDate, dd.damageDetailId, i.issueDate, cat.categoryName, im.itemName, sd.productCode, dd.damageQuantity, dam.damageDate, qd.unitPrice, rec.receiveDate, c.companyName, d.departmentName, issueFor.userId, issueFor.firstName rFirstName, issueFor.middleName rMiddleName, issueFor.lastName rLastName, checked.firstName bFirstName, checked.middleName bMiddleName, checked.lastName bLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, hand.firstName hFirstName, hand.middleName hMiddleName, hand.lastName hLastName, dam.createDate');
        $this->db->from(TBL_DISPOSAL.' as dis ');
        $this->db->join(TBL_DISPOSAL_DETAILS.' as dDetail', 'dDetail.disposalId=dis.disposalId');
        $this->db->join(TBL_DAMAGE_DETAIL.' as dd ', 'dd.damageDetailId=dDetail.damageDetailId');
        $this->db->join(TBL_DAMAGE.' as dam ', 'dam.damageId=dd.damageId');
        $this->db->join(TBL_ISSUES.' as i ', 'i.issueId=dam.issueId', 'left');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=dam.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as cat ', 'cat.categoryId=im.categoryId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=s.companyId');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.receiveDetailId=sd.receiveDetailId');
        $this->db->join(TBL_RECEIVES.' as rec ', 'rec.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=q.quotationId and qd.itemMasterId=im.itemMasterId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=i.departmentId', 'left');
        $this->db->join(TBL_USERS.' as issueFor ', 'issueFor.userId=i.issueUserId', 'left');
        $this->db->join(TBL_USERS.' as hand ', 'hand.userId=dis.handoverTo', 'left');
        $this->db->join(TBL_USERS.' as checked ', 'checked.userId=dam.checkedById', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=dam.creatorId', 'left');
        $this->db->where('dd.damageType', 'Permanent-Damage');
        return $this->db->get();
    }

    function get_data($filters=array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $lastUser= $row->departmentName ? $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->departmentName : '';
            $array[$row->damageDetailId]= array(
                ++$i, //SL no.
                $row->categoryName, //Product Category
                $row->itemName, //product Name
                $row->productCode, //Tracking no.
                '', /*TODO // serial no.*/
                $row->damageQuantity, // Quantity
                $row->companyName, // company Name
                $lastUser, // last user
                $row->receiveDate, // purchase date
                $row->issueDate, // issue date
                $row->damageDate, //damage date
                $row->unitPrice * $row->damageQuantity, //purchase price
                '', /*TODO*/ // no. of repair
                '', /*TODO*/ // total repair cost
                $row->disposalDate, //disposal date
                $row->hFirstName.' '.$row->hMiddleName.' '.$row->hLastName, //handover to
                $row->bFirstName.' '.$row->bMiddleName.' '.$row->bLastName, //checked by
                $row->cFirstName.' '.$row->cMiddleName.' '.$row->cLastName, //created by
                $row->createDate, //create date
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Product Category',
            'Product Name',
            'Tracking No.',
            'Serial No.',
            'Quantity',
            'Company Name',
            'Last User',
            'Purchase Date',
            'Issue Date',
            'Damage Date',
            'Purchase Price',
            'No. Of Repair',
            'Total Repair Cost',
            'Disposal Date',
            'Handover To',
            'Checked By',
            'Created By',
            'Create Date',
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
            'Item'          => array('select', $this->get_item_list()),
            'Tracking No'   => 'text'
        );
    }
}
?>