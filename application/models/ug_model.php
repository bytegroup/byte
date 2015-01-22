<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 *
 * @author USER
 */
Class Ug_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function getStudentUserGroups()
    {
        $result = $this->db->where("userLevel",STUDENT_LEVEL)->get(TBL_USER_GROUP);
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
    
    function getUGinfo($gpid)
    {
        $this->db->where("groupId",$gpid);
        $result = $this->db->get(TBL_USER_GROUP);
        
        if($result->num_rows() > 0)
        {
            return $result;
        }
        return false;
    }
    
    function updatePermission($ugid,$permission_string)
    {        
        $data['permissions'] = $permission_string;
        $this->db->where("groupId",$ugid)->update(TBL_USER_GROUP,$data);
        return true;
    }
}

?>