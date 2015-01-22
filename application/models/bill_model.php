<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Bill_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function getRequisition($userid){
        $this->db->select("U.*, OG.* ",false)
                 ->from(TBL_USERS." U LEFT JOIN ".TBL_ORGANIZATIONS." OG ON U.organizationId = OG.organizationId ")        ->where("U.userId", $userid);
        $result = $this->db->get();
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
   
    function saveBill($billId,$data)
    {
        $time = time();
        if($billId > 0)
        {
            $this->db->where("billId",$billId)->update(TBL_BILL,$data);
            return true;
        }
        
        $this->db->insert(TBL_BILL,$data);
        $insertId = $this->db->insert_id();
        if($insertId > 0)
        {
            return (int)$insertId;
        }
        return false;
    }
    
    function addBillDetails($data,$billId=''){
        $this->db->insert(TBL_BILL_DETAIL,$data);
        if($this->db->affected_rows() >= 0)
        {
            $value['success'] = true;
            return $value;
        }
        $value['success'] = false;
        return $value;    
    }
    function delete_Buget($id){
        $result = $this->db->delete(TBL_BILL, array('billId' => $id));
       // $resultDetails = $this->db->delete(TBL_BILL_DETAIL, array('billId' => $id));
    }
}

?>