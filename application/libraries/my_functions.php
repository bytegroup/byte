<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class My_functions {
    /**
     * Return header css string
     * @param string $filename
     * @param boolean $set_base_folder
     * @return string 
     */
    function addCss($filename,$set_base_folder = false){
        $css = '';
        if(!$set_base_folder){
            $css = "<link rel = \"stylesheet\" media = \"screen\" href=\"".base_url().ASSETS_FOLDER."css/".$filename."\" charset=\"utf-8\" />";
        }else{
            $css = "<link rel = \"stylesheet\" media = \"screen\" href=\"".base_url().ASSETS_FOLDER.$filename."\" charset=\"utf-8\" />";
        }
        return $css;
    }
    
    /**
     * Return header js string
     * @param string $filename
     * @param boolean $set_base_folder
     * @return string 
     */
    function addJs($filename, $set_base_folder = false){
        $js ="";
        if(!$set_base_folder){
            $js = "<script type=\"text/javascript\" src=\"".base_url().ASSETS_FOLDER."js/".$filename."\" charset=\"utf-8\"></script>";
        }else{
            $js = "<script type=\"text/javascript\" src=\"".base_url().ASSETS_FOLDER.$filename."\" charset=\"utf-8\"></script>";
        }        
        return $js;
    }
    
    public function filterParameters($array) {
            /** get CI instances for ci library usage **/
            $CI =& get_instance();
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = $this->filterParameters($array[$key]);
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node
                        $array[$key] = htmlspecialchars(trim($array[$key]), ENT_QUOTES, $CI->config->item('charset'));
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                $array = htmlspecialchars(trim($array), ENT_QUOTES, $CI->config->item('charset'));
           
            // Return the filtered result
            return $array;       
    }
    
    public function filterParameters2($array) {
            /** get CI instances for ci library usage **/
            $CI =& get_instance();
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = $this->filterParameters($array[$key]);
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node
                        $array[$key] = htmlspecialchars(trim($array[$key]), ENT_COMPAT , $CI->config->item('charset'));
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                $array = htmlspecialchars(trim($array), ENT_COMPAT , $CI->config->item('charset'));
           
            // Return the filtered result
            return $array;       
    }
    
    /**
     * return file extention name
     * @param type $file_name
     * @param array $allowed_ext
     * @return boolen
     */
    function check_file_extension($file_name ,$allowed_ext = null){
        if($allowed_ext == null):
            $allowed_ext = array('docx','doc','xlsx','xls','ppt','pptx','zip','rar','pdf');
        endif;        
        
        $file = explode('.',$file_name);
        $ext = strtolower(array_pop($file));
        
        if(in_array($ext,$allowed_ext))
        {
            return $ext;
        }
        else
        {
            return false;
        }
    }
    
    function getFileExtention($filename){
         $array = explode(".", $filename);
         $count = count($array);         
         return strtolower($array[$count-1]);
    }
    
    /**
     * convert date formt dd/mm/yyyy to yyyy-mm-dd
     * @param type $date 
     */
    function convertDateDDMMYYYY($date)
    {
        if($date == null || $date == ""):
            return "0000-00-00";
        endif;
        $date = explode("/", $date);
        $date = $date[2]."-".$date[1]."-".$date[0];
        return $date;
    }
    
}
/*?>*/