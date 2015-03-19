<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/9/15
 * Time: 2:31 PM
 */
?>
<?php
class Quotation_Details_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    public function get_meta_data($id){
        if(!$id)return array();
        $this->db->select('q.quotationNumber, q.quotationDate, v.vendorsName, r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, c.companyName, r.requisitionFor, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, r.createDate, r.editDate');
        $this->db->from(TBL_QUOTATIONS.' as q ');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=r.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=r.editorId', 'left');
        $this->db->where('quotationId', $id);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        $row = $db->result()[0];
        $reqFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
        return array(
            'Quotation No.'     => $row->quotationNumber,
            'Quotation Date'    => $row->quotationDate,
            'Vendor'            => $row->vendorsName,
            'Requisition No.'   => $row->requisitionNumber,
            'Requisition Date'  => $row-> requisitionCreateDate,
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
            'Quotation No.',
            'Quotation Date',
            'Vendor',
            'Requisition No.',
            'Requisition Date',
            'Company Name',
            'Requisition For',
            'Created By',
            'Create Date',
            'Edited By',
            'Edit Date'
        );
    }

    public function get_data($id){
        $this->db->select('c.categoryName, i.itemName, i.itemCode, qd.productBrand, q.paymentType, qd.productOrigin, qd.productType, qd.productWarranty, qd.productRemarks, , qd.orderedQuantity, qd.unitPrice, qd.quotationPrice, u.unitName');
        $this->db->from(TBL_QUOTATIONS_DETAIL.' as qd ');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=qd.quotationId');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=qd.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId');
        $this->db->where('qd.quotationId', $id);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'=> ++$i,
                'Category'          => $row->categoryName,
                'Item Name'         => $row->itemName,
                'Item Code'         => $row->itemCode,
                'Qty'               => $row->orderedQuantity,
                'Unit'              => $row->unitName,
                'Unit Price'        => $row->unitPrice,
                'Total Amount'      => $row->quotationPrice,
                'Product Brand'     => $row->productBrand,
                'Product Origin'    => $row->productOrigin,
                'Product Type'      => $row->productType,
                'Product Warranty'  => $row->productWarranty,
                'Payment Type'      => $row->paymentType,
                'Product Remarks'   => $row->productRemarks
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