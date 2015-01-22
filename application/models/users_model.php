<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Users_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function checkUser($username, $password, $organizationId = null){
        $this->db->select('u.*, a.userName, a.password, a.permissions');
        $this->db->from(TBL_USERS.' as u');
        $this->db->join(TBL_ADMINS.' as a ', 'u.adminId=a.adminId');
        //$this->db->join(TBL_ADMINS.' as ad ', 'u.adminId= ad.adminId');
        $this->db->where('a.userName',$username);
        $this->db->where('a.password', $password);

        $result = $this->db->get();
        if($result->num_rows() > 0)
        {
            return $result;
        }
        return false;
    }
    
    function getUserInfo($username){
        $this->db->where("userName", $username);
        $result = $this->db->get(TBL_USERS);
        
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
    
    function getUserInfoById($userid){
        $this->db->select("U.*, OG.*, D.departmentName, DES.designationName ",false)
                ->from(TBL_USERS." U
                LEFT JOIN ".TBL_ORGANIZATIONS." OG ON U.organizationId = OG.organizationId
                LEFT JOIN ".TBL_DEPARTMENTS." D ON D.departmentId= U.departmentId
                LEFT JOIN ".TBL_DESIGNATIONS." DES ON DES.designationId= U.designationId" )
                ->where("U.userId", $userid);
        
		$result = $this->db->get();
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
    
    function addUserInfo($data,$userid)
    {
        $this->db->where("userId",$userid);
        $this->db->insert(TBL_USER_INFO,$data);        
    }
    
    function updateUserInfo($data,$userid){
        $this->db->where("userId",$userid);
        $this->db->update(TBL_USERS,$data);
        
        if($this->db->affected_rows() >= 0)
        {
            return true;
        }
        return false;        
    }
    
    function updateUserOtherInfo($data, $userid)
    {
        $this->db->where("userId",$userid);
        $this->db->update(TBL_USERS,$data);
        
        if($this->db->affected_rows() >= 0)
        {
            return true;
        }
        return false; 
    }
    
    function getAllUserGroups()
    {
        $result = $this->db->get(TBL_USER_GROUP);
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
    
    function getUserAndUserGroup()
    {
        $sql = "SELECT UG.*,U.userId FROM ".TBL_USER_GROUP." UG LEFT JOIN ".TBL_USERS." U ON U.groupId = UG.groupId";
        $result = $this->db->query($sql); 
        
        if($result->num_rows() > 0)
        {
            return $result;
        }        
        return false;
    }
    
    function addNewUserData($udata,$addressdata,$userId)
    {
        $time = time();
        if($userId > 0)
        {
            $udata['updateDtTm'] = $time;
            $addressdata['updateDtTm'] = $time;
            $this->db->where("userId",$userId)->update(TBL_SITE_USERS,$udata);
            $this->db->where("userId",$userId)->update(TBL_VCARD_ADDRESS,$addressdata);
            return true;
        }
        
        $udata['creationDtTm'] = $time;
        $udata['updateDtTm'] = $time;
        $this->db->insert(TBL_SITE_USERS,$udata);
        $insertId = $this->db->insert_id();
        if($insertId > 0)
        {
            $addressdata['creationDtTm'] = $time;
            $addressdata['updateDtTm'] = $time;            
            $addressdata["userId"] = $insertId;
            
            $this->db->insert(TBL_VCARD_ADDRESS,$addressdata);
            return (int)$insertId;
        }
        return false;
    }
    
    function getAllVcard($page = null)
    {
        
        $this->db->select("VC.*,VCD.*")
                 ->from(TBL_SITE_USERS." VC")
                 ->join(TBL_VCARD_ADDRESS." VCD","VCD.userId = VC.userId","left");
        
        if(is_array($page))
        {
            if((int)$page['offset'] <= 0 )
            {
                $page['offset'] = 0;
            }
            $this->db->limit($page['limit'],$page['offset']);
        }
        
        $this->db->order_by("VC.creationDtTm","DESC");
        $result = $this->db->get();
        if($result->num_rows() > 0)
        {
            return $result;
        }
                 
        return false;
    }
    
    function getVcardInfo($userId)
    {
        $this->db->select("VC.*,VCD.*")
                 ->from(TBL_SITE_USERS." VC")
                 ->join(TBL_VCARD_ADDRESS." VCD","VCD.userId = VC.userId","left")
                 ->where("VC.userId",$userId)
                 ->limit(1);
        $result = $this->db->get();
        if($result->num_rows() > 0)
        {
            return $result;
        }
                 
        return false; 
    }
    
}

?>