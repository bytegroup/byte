<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/17/15
 * Time: 12:45 PM
 */
?>
<?php
class Quotation_Comparison_Model extends CI_Model {
    var $quotations= array();
    var $requisitionId= 0;
    function __construct(){
        parent::__construct();
    }

    public function get_meta_data($id){
        $this->requisitionId= $id;
        if(!$id)return array();
        $this->db->select('r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, c.companyName, r.requisitionFor, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, r.createDate, r.editDate');
        $this->db->from(TBL_REQUISITIONS.' as r ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=r.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=r.editorId', 'left');
        $this->db->where('requisitionId', $id);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $row = $db->result()[0];
        $reqFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
        return array(
            'Requisition No.'   => $row->requisitionNumber,
            'Requisition Title' => $row->requisitionTitle,
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
            'Requisition No.',
            'Requisition Title',
            'Requisition Date',
            'Company Name',
            'Requisition For',
            'Created By',
            'Create Date',
            'Edited By',
            'Edit Date'
        );
    }

    private function _common_data($reqId){
        $this->db->select('rd.itemMasterId, c.categoryName, i.itemName, i.itemCode');
        $this->db->from(TBL_REQUISITIONS_DETAIL.' as rd ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=rd.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->where('requisitionId', $reqId);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array();$i= 0;
        foreach($db->result() as $row):
            $array[$row->itemMasterId]= array(
                ++$i,
                $row->categoryName,
                $row->itemName,
                $row->itemCode
            );
        endforeach;
        return $array;
    }
    private function _comparison_data($quotId){
        $this->db->select('qd.itemMasterId, qd.orderedQuantity, qd.unitPrice, qd.quotationPrice');
        $this->db->from(TBL_QUOTATIONS_DETAIL.' as qd ');
        $this->db->where('quotationId', $quotId);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[$row->itemMasterId]= array(
                '',
                '',
                '',
                '',
                '',
                '',
                $row->orderedQuantity,
                $row->unitPrice,
                $row->quotationPrice
            );
        endforeach;
        return $array;
    }
    private function _empty_array(){
        return array('', '', '', '', '', '', '', '', '');
    }

    public function get_data($reqId){
        $quotations= $this->quotations;
        $commonData= $this->_common_data($reqId);
        $array= array();
        foreach($quotations as $quotId => $vendor):
            $comparisonData= $this->_comparison_data($quotId);
            foreach($commonData as $itemId => $row){
                $quotData= !isset($comparisonData[$itemId]) ? $this->_empty_array() : $comparisonData[$itemId];
                $commonData[$itemId]= array_merge($row, $quotData);
            }
        endforeach;
        foreach($commonData as $itemId=> $row):
            $array[]= $row;
        endforeach;
        return $array;
    }

    private function _quotations($reqId){
        if(!$reqId) return array();
        $this->db->select('q.quotationId, v.vendorsName');
        $this->db->from(TBL_QUOTATIONS.' as q ');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->where('q.requisitionId', $reqId);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->quotationId]= $row->vendorsName;
        }
        return $array;
    }

    public function set_quotations($reqId){
        $this->quotations= $this->_quotations($reqId);
    }
    public function get_quotations(){
        return $this->quotations;
    }

    public function get_headers(){
        $array= $this->_common_headers();
        $numOfQuot= count($this->quotations);
        for($i=0; $i<$numOfQuot; $i++){
            $array = array_merge($array, $this->_comparison_headers());
        }
        return $array;
    }

    private function _common_headers(){
        return array(
            'SL',
            'Category',
            'Item Name',
            'Item Code'
        );
    }
    private function _comparison_headers(){
        return array(
            'Product Brand',
            'Product Origin',
            'Product Type',
            'Product Warranty',
            'Payment Type',
            'Product Remarks',
            'Qty',
            'Unit Price',
            'Total Amount'
        );
    }
}
?>