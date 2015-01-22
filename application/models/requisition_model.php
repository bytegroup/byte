<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Requisition_model extends CI_Model {
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
   
    function saveRequisition($requisitionId,$data)
    {
        $time = time();
        if($requisitionId > 0)
        {
            $this->db->where("requisitionId",$requisitionId)->update(TBL_REQUISITIONS,$data);
            return true;
        }
        
        $this->db->insert(TBL_REQUISITIONS,$data);
        $insertId = $this->db->insert_id();
        if($insertId > 0)
        {
            return (int)$insertId;
        }
        return false;
    }
    
    function addRequisitionDetails($data,$requisitionId=''){
        $this->db->insert(TBL_REQUISITIONS_DETAIL,$data);
        if($this->db->affected_rows() >= 0)
        {
            $value['success'] = true;
            return $value;
        }
        $value['success'] = false;
        return $value;    
    }
    function delete_requisition($id){        
        $result = $this->db->delete(TBL_REQUISITIONS, array('requisitionId' => $id));
        $resultDetails = $this->db->delete(TBL_REQUISITIONS_DETAIL, array('requisitionId' => $id));   
    }
}

?>