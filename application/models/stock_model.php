<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Stock_model extends CI_Model {
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

    function saveStock($stockId,$data)
    {
        $time = time();
        if($stockId > 0)
        {
            $this->db->where("stockId",$stockId)->update(TBL_STOCK,$data);
            return true;
        }

        $this->db->insert(TBL_STOCK,$data);
        $insertId = $this->db->insert_id();
        if($insertId > 0)
        {
            return (int)$insertId;
        }
        return false;
    }

    function addStockDetails($data,$receiveId=''){
        $this->db->insert(TBL_RECEIVES_DETAIL,$data);
        if($this->db->affected_rows() >= 0)
        {
            $value['success'] = true;
            return $value;
        }
        $value['success'] = false;
        return $value;
    }
    function deleteStock($id){
        $result = $this->db->delete(TBL_STOCK, array('stockId' => $id));
        //$resultDetails = $this->db->delete(TBL_RECEIVES_DETAIL, array('receiveId' => $id));
    }
}

?>