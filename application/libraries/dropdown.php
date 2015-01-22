<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of dropdown
 *
 * @author Arif
 */
Class Dropdown {
    
    var $CI;
    function __construct() {
        $this->CI =& get_instance();
    }
    
    function printUserLevel($id = null)
    {
        $userlevel = array(ADMIN_LEVEL => "Super Admin", SUBADMIN_LEVEL => "Sub Admin");
        $list = "";
        foreach($userlevel as $key => $val):
            $selected = "";
                if($id != null){
                    $selected = ($id == $key) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        endforeach;
                
        return $list;
    }
    
    function printStudentUserGroupList($id = null)
    {
        $this->CI->load->model("ug_model");
        $query = $this->CI->ug_model->getStudentUserGroups();
        
        $list = "";
        if($query):            
            foreach($query->result() as $row):
                $selected = "";
                    if($id != null){
                        $selected = ($id == $row->groupId) ? "selected = 'selected'" : "";
                    }
                    $list .='<option value="'.$row->groupId.'" '.$selected.'>'.$row->groupName.'</option>';
            endforeach;
        endif;
        
        return $list;
    }
    
    
    function printSessionList($id = null)
    {
        $filename = APPPATH."cache/".DROPDOWN_CACHE_FOLDER."sessionDD.php";
        if(!file_exists($filename)){
            return false;
        }
        include($filename);
        
        $list = "";
        foreach($sessionArray as $key => $val):
            $selected = "";
                if($id != null){
                    $selected = ($id == $key) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        endforeach;
        return $list;
    }
    
    
    
    function printTermList($sessionid = null,$termid = null)
    {
        $filename = APPPATH."cache/".DROPDOWN_CACHE_FOLDER."termDD".$sessionid.".php";
        if(!file_exists($filename)){
            return false;
        }
        include($filename);
        
        $list = "";
        foreach($termArray as $key => $val):
            $selected = "";
            if($termid != null){
                $selected = ($termid == $key) ? "selected = 'selected'" : "";
            }
            $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        endforeach;
        return $list;
    }
    
    function printLevelList($id = null)
    {
        $filename = APPPATH."cache/".DROPDOWN_CACHE_FOLDER."levelDD.php";
        if(!file_exists($filename)){
            return false;
        }
        include($filename);
        
        $list = "";
        foreach($levelArray as $key => $val):
            $selected = "";
            if($id != null){
                $selected = ($id == $key) ? "selected = 'selected'" : "";
            }
            $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        endforeach;
        return $list;
    }
    
    function printClassList($levelId = null, $sessionId = null, $fromCache = true,$id = null)
    {
        if($fromCache)
        {
            $filename = APPPATH."cache/".DROPDOWN_CACHE_FOLDER."classDD_lid".$levelId."_sid".$sessionId.".php";
            if(!file_exists($filename)){
                return false;
            }
            include($filename);
            $list = "";
            foreach($classArray as $key => $val):
                $selected = "";
                if($id != null){
                    $selected = ($id == $key) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
            endforeach;
            return $list;
        }
        else
        {
            $list = "";
            
            $this->CI->load->model("class_model");
            $query = $this->CI->class_model->getAllClasses($levelId,$sessionId);
            if($query)
            {
                foreach($query->result() as $row):
                    $selected = "";
                    if($id != null){
                        $selected = ($id == $row->classId) ? "selected = 'selected'" : "";
                    }
                    $list .='<option value="'.$row->classId.'" '.$selected.'>'.ucfirst($row->className.' ('.$row->classCode.')').'</option>';
                endforeach;
            }

            return $list;            
        }
            
    }
    
    function printSubjectList($levelId = null,$id = null)
    {
        $filename = APPPATH."cache/".DROPDOWN_CACHE_FOLDER."subjectDD".$levelId.".php";
        if(!file_exists($filename)){
            return false;
        }
        include($filename);
        
        $list = "";
        foreach($subjectArray as $key => $val):
            $selected = "";
            if($id != null){
                $selected = ($id == $key) ? "selected = 'selected'" : "";
            }
            $list .='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        endforeach;
        return $list;
    }
    
    function printTeacherList($id = null)
    {
        $CI =& get_instance();
        $CI->load->model("users_model");
        $query = $CI->users_model->getAllTeachers();
        $list = "";
        if($query)
        {
            foreach($query->result() as $row):
                $selected = "";
                if($id != null){
                    $selected = ($id == $row->userId) ? "selected = 'selected'" : "";
                }
                $list .='<option value="'.$row->userId.'" '.$selected.'>'.ucfirst($row->firstName.' '.$row->lastName.' '.$row->middleName).'</option>';
            endforeach;
        }
        
        return $list;
    }
    
    function getStudentListForEmailing($classId)
    {
        $CI =& get_instance();
        $CI->load->model("student_model");
        $query = $CI->student_model->getStudentByClass($classId);
        $list = "";
        if($query)
        {
            foreach($query->result() as $row):
                $list .='<option value="'.$row->email.'" >'.ucfirst($row->fullName).'</option>';
            endforeach;
        }
        
        return $list;
    }
}

?>
