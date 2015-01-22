<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Quotation_model extends CI_Model {
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
   
    function saveQuotation($quotationId,$data)
    {
        $time = time();
        if($quotationId > 0)
        {
            $this->db->where("quotationId",$quotationId)->update(TBL_QUOTATIONS,$data);
            return true;
        }
        
        $this->db->insert(TBL_QUOTATIONS,$data);
        $insertId = $this->db->insert_id();
        if($insertId > 0)
        {
            return (int)$insertId;
        }
        return false;
    }
    
    function addQuotationDetails($data,$quotationId=''){
        $this->db->insert(TBL_QUOTATIONS_DETAIL,$data);
        if($this->db->affected_rows() >= 0)
        {
            $value['success'] = true;
            return $value;
        }
        $value['success'] = false;
        return $value;    
    }
    function delete_quotation($id){        
        $result = $this->db->delete(TBL_QUOTATIONS, array('quotationId' => $id));
        $resultDetails = $this->db->delete(TBL_QUOTATIONS_DETAIL, array('quotationId' => $id));   
    }
}

?>