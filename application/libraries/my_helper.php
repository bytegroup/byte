<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of my_helper
 *
 * @author USER
 */
Class My_helper {
    function calculateAge($dob)
    {
        list($year,$month,$day) = explode("-",$dob);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
          $year_diff--;
        return $year_diff;
    }
    
    function printUserGroupList($id = null)
    {
        $CI =& get_instance();
        $CI->load->model("users_model");
        
        $query = $CI->users_model->getAllUserGroups();        
        
        $list = "";
        if($query != false){
            foreach($query->result() as $row){
                $selected = "";
                if($id != null){
                    $selected = ($id == $row->groupId) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$row->groupId.'" '.$selected.'>'.$row->groupName.'</option>';
            }
        }
        return $list;        
    }
    
    function printUserGroupListForUser($id = null)
    {
        $CI =& get_instance();
        $CI->load->model("users_model");
        
        $query = $CI->users_model->getUserAndUserGroup();        
        
        $list = "";
        if($query != false){
            foreach($query->result() as $row){
                $selected = "";
                if($id != null){
                    $selected = ($id == $row->userId) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$row->groupId.'" '.$selected.'>'.$row->groupName.'</option>';
            }
        }
        return $list;        
    }    
    function printOrganizationsList($id = null)
    {
        $CI =& get_instance();
        $CI->load->model("organizations_model");
        
        $query = $CI->organizations_model->getAllOrganizations();        
        
        $list = "";
        if($query != false){
            foreach($query->result() as $row){
                $selected = "";
                if($id != null){
                    $selected = ($id == $row->organizationId) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$row->organizationId.'" '.$selected.'>'.$row->organizationName.'</option>';
            }
        }
        return $list;        
    }

    function isPermitted($userID=0, $option=null){
        if(isset($this->my_session->permissions[$option])){
            return true;
        }
        else return false;
    }
}

?>
