<?php
Class Menu_helper
{
    var $permissions = array();
    
    /** feed ugpermissions - mysql result **/
    function createPermissionArray($parray)
    {
        $permissionArray = $this->permissions;
        $pstring = explode(";",$parray);
        
        foreach($pstring as $key => $val)
        {
            $permissionArray['permissions'][$val] = $val;
        }
        array_pop($permissionArray['permissions']);
        return $permissionArray['permissions'];   
    }
    
    function makeUserGroupMenu($usergroupId)
    {
        $CI =& get_instance();
        
        $result = $CI->db->where("groupId",$usergroupId)->get(TBL_USER_GROUP);
		
        if($result)
        {
            $data['permissions'] = $this->createPermissionArray($result->row()->permissions);
                        
            $data['base_url'] = base_url();
            $menu = $CI->load->view(TEMPLATES_FOLDER."user_group_menu.php",$data,true);
            
            $fileDirectory = ABS_SERVER_PATH.SITE_FOLDER.APPPATH.'views/'.TEMPLATES_FOLDER.USERGROUP_MENU_FOLDER;
            $fileName = ABS_SERVER_PATH.SITE_FOLDER.APPPATH.'views/'.TEMPLATES_FOLDER.USERGROUP_MENU_FOLDER.$result->row()->groupId.".html";

            $CI->load->helper("file");

            if(!file_exists($fileDirectory)):                
                mkdir($fileDirectory);
            endif;

            $t = write_file($fileName, $menu,'w');        
			
        }
            
    }
    
}
?>
