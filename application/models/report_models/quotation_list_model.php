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

    function get_data(){
        $this->db->select('c.companyName, q.quotationNumber, q.quotationDate, q.quotationDescription, v.vendorsName, r.requisitionNumber, r.requisitionTitle, r.requisitionCreateDate, r.departmentId, r.userId, d.departmentName, reqFor.firstName rFirstName, reqFor.middleName rMiddleName, reqFor.lastName rLastName, creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, q.createDate, q.editDate');
        $this->db->from(TBL_QUOTATIONS.' as q ');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_DEPARTMENTS.' as d ', 'd.departmentId=r.departmentId', 'left');
        $this->db->join(TBL_USERS.' as reqFor ', 'reqFor.userId=r.userId', 'left');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=q.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=q.editorId', 'left');
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $reqFor= $row->departmentId ? $row->departmentName : $row->userId ? ($row->rFirstName.' '.$row->rMiddleName.' '.$row->rLastName): $row->companyName;
            $array[]= array(
                'SL'=> ++$i,
                'Quo, No.'=> $row->quotationNumber,
                'Quo. Date'=> $row->quotationDate,
                'Req. No.'=> $row->requisitionNumber,
                'Req. Title'=> $row->requisitionTitle,
                'Req. Date'=> $row->requisitionCreateDate,
                'Vendor name' => $row->vendorsName,
                'Description'=> $row->quotationDescription,
                'Company Name'=> $row->companyName,
                'Req. For'=> $reqFor,
                'Created By'=>$row->cFirstName.' '.$row->cMiddleName.' '.$row->cLastName,
                'Create Date'=> $row->createDate,
                'Edited By'=> $row->eFirstName.' '.$row->eMiddleName.' '.$row->eLastName,
                'Edit Date'=> $row->editDate
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
}
?>