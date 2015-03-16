<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/9/15
 * Time: 3:59 PM
 */
?>
<?php
class Purchase_Details_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    public function get_meta_data($id){
        if(!$id)return array();
        $this->db->select('rec.receiveNumber, rec.receiveDate, b.billNumber, q.quotationNumber, q.quotationDate, v.vendorsName, r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, c.companyName, r.requisitionFor, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, r.createDate, r.editDate');
        $this->db->from(TBL_RECEIVES.' as rec ');
        $this->db->join(TBL_BILL.' as b ', 'b.receiveId=rec.receiveId', 'left');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=r.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=r.editorId', 'left');
        $this->db->where('rec.receiveId', $id);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $row = $db->result()[0];
        $reqFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
        return array(
            'Purchase No.'      => $row->receiveNumber,
            'Purchase Date'     => $row->receiveDate,
            'Bill No.'          => $row->billNumber,
            'Requisition No.'   => $row->requisitionNumber,
            'Requisition Date'  => $row-> requisitionCreateDate,
            'Quotation No.'     => $row->quotationNumber,
            'Quotation Date'    => $row->quotationDate,
            'Vendor'            => $row->vendorsName,
            'Company Name'      => $row->companyName,
            'Requisition For'   => $reqFor,
            'Created By'        =>$row->cFirstName.' '.$row->cMiddleName.' '.$row->cLastName,
            'Create Date'       => $row->createDate,
            'Edited By'         => $row->eFirstName.' '.$row->eMiddleName.' '.$row->eLastName,
            'Edit Date'         => $row->editDate
        );
    }

    public function get_meta_data_title(){
        return array(
            'Purchase No.',
            'Purchase Date',
            'Bill No.',
            'Requisition No.',
            'Requisition Date',
            'Quotation No.',
            'Quotation Date',
            'Vendor',
            'Company Name',
            'Requisition For',
            'Created By',
            'Create Date',
            'Edited By',
            'Edit Date'
        );
    }

    public function get_data($id){
        $this->db->select('c.categoryName, i.itemName, i.itemCode, rd.receiveQuantity, qd.unitPrice, u.unitName');
        $this->db->from(TBL_RECEIVES_DETAIL.' as rd ');
        $this->db->join(TBL_RECEIVES.' as r ', 'r.receiveId=rd.receiveId');
        $this->db->join(TBL_QUOTATIONS_DETAIL.' as qd ', 'qd.quotationId=r.quotationId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=rd.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId');
        $this->db->where('rd.receiveId', $id);
        //$this->db->group_by('rd.receiveId');
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'=> ++$i,
                'Category'          => $row->categoryName,
                'Item Name'         => $row->itemName,
                'Item Code'         => $row->itemCode,
                'Qty'               => $row->receiveQuantity,
                'Unit'              => $row->unitName,
                'Unit Price'        => $row->unitPrice,
                'Total Amount'      => $row->unitPrice * $row->receiveQuantity,
                'Product Brand'     => '',
                'Product Origin'    => '',
                'Product Type'      => '',
                'Product Warranty'  => '',
                'Payment Type'      => '',
                'Product Remarks'   => ''
            );
        endforeach;
        return $array;
    }

    public function get_headers(){
        return array(
            'SL',
            'Category',
            'Item Name',
            'Item Code',
            'Qty',
            'Unit',
            'Unit Price',
            'Total Amount',
            'Product Brand',
            'Product Origin',
            'Product Type',
            'Product Warranty',
            'Payment Type',
            'Product Remarks'
        );
    }
}
?>