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

    function get_data(){
        $this->db->select('c.companyName, rec.receiveNumber, rec.receiveDate, r.requisitionNumber, r.requisitionCreateDate, r.requisitionTitle, q.quotationNumber, q.quotationDate, v.vendorsName, b.billAmount, b.billNumber,  creator.firstName cFirstName, creator.middleName cMiddleName, creator.lastName cLastName, editor.firstName eFirstName, editor.middleName eMiddleName, editor.lastName eLastName, rec.createDate, rec.editDate');
        $this->db->from(TBL_RECEIVES.' as rec ');
        $this->db->join(TBL_BILL.' as b ', 'b.receiveId= rec.receiveId', 'left');
        $this->db->join(TBL_QUOTATIONS.' as q ', 'q.quotationId=rec.quotationId');
        $this->db->join(TBL_REQUISITIONS.' as r ', 'r.requisitionId=q.requisitionId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=r.companyId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=q.vendorsId');
        $this->db->join(TBL_USERS.' as creator ', 'creator.userId=rec.creatorId', 'left');
        $this->db->join(TBL_USERS.' as editor ', 'editor.userId=rec.editorId', 'left');
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'=> ++$i,
                'Company Name' =>$row->companyName,
                'Receive No.'=> $row->receiveNumber,
                'Receive Date'=> $row->receiveDate,
                'Req. No.' => $row->requisitionNumber,
                'Req. Date' => $row->requisitionCreateDate,
                'Req. Title' => $row->requisitionTitle,
                'Quo. No.' => $row->quotationNumber,
                'Quo. Date' => $row->quotationDate,
                'Vendor Name' => $row->vendorsName,
                'Invoice Name' => '',
                'Invoice No.' => 0,
                'Invoice Date' => '',
                'Total Bill Amount' => $row->billAmount,
                'Bill No.' => $row->billNumber,
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
            'Company Name',
            'Receive No.',
            'Receive Date',
            'Req. No.',
            'Req. Date',
            'Req. Title',
            'Quo. No.',
            'Quo. Date',
            'Vendor Name',
            'Invoice Name',
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
}
?>