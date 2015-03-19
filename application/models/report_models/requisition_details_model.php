<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/6/15
 * Time: 3:23 PM
 */
?>
<?php
class Requisition_Details_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    public function get_meta_data($id){
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
        $array= array();
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

    public function get_data($id){
        $this->db->select('c.categoryName, i.itemName, i.itemCode, i.minimumQuantity, rd.orderedQuantity, u.unitName, s.stockQuantity');
        $this->db->from(TBL_REQUISITIONS_DETAIL.' as rd ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=rd.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId');
        $this->db->join(TBL_STOCK.' as s ', 's.itemMasterId=i.itemMasterId');
        $this->db->where('requisitionId', $id);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'            => ++$i,
                'Category'      => $row->categoryName,
                'Item Name'     => $row->itemName,
                'Item Code'     => $row->itemCode,
                'Qty'           => $row->orderedQuantity,
                'Unit'          => $row->unitName,
                'Minimum Stock' => $row->minimumQuantity,
                'Current Stock' => $row->stockQuantity
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
            'Minimum Stock',
            'Current Stock'
        );
    }
}
?>