<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Upload_model extends CI_Model {
    /***
     * 
     * @params $postFileName = form file name;
     ***/
    function do_upload($postFileName, $upload_folder,$allowedExt = null,$maxFileSize = '1050')
    {
        $json = array();
        
        if(!isset($_FILES[$postFileName]['name']))
        {            
            $json['msg'] = "You did not select any file to upload.";
            $json['success'] = false;
            return $json;            
        }
        
        /*******************************/
        /** file upload configuration **/
        /*******************************/
        $this->load->helper("string_helper");
        $newFileName = random_string('alpha',12);        

        if(!file_exists($upload_folder)){
            mkdir($upload_folder);
        }

        $this->load->library("my_functions");
        /*$fileExt = (string)$this->my_functions->check_file_extension($_FILES[$postFileName]['name'],$allowedExt);
        
        if(!$fileExt)
        {
            $json['msg'] = "File type not allowed.";
            $json['success'] = false;
            return $json;
        }*/

        $config['file_name'] = $_FILES[$postFileName]['name'];//$newFileName;
        $config['upload_path'] = $upload_folder;
        $config['allowed_types'] = '*';                
        $config['max_size'] = $maxFileSize;


        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($postFileName))
        {
            $json['success'] = false;
            $json['msg'] = $this->upload->display_errors(); 
            return $json;
        }
        else
        {  
           $json['success'] = true;
           $json['upload_data'] = $this->upload->data();
           return $json; 
        }
        
    }
    
    
    function resize_image($fileName,$folder,$creatThumb = false,$width,$height)
    {
        $this->load->library('image_lib');
        $conf['image_library'] = 'gd2';
        $conf['source_image'] = $folder.$fileName;
        $conf['create_thumb'] = $creatThumb;
        $conf['maintain_ratio'] = TRUE;
        $conf['width'] = $width;                        
        $conf['height'] = $height;

        $this->image_lib->initialize($conf);

        if ( !$this->image_lib->resize() )
        {
            $json['success'] = false;
            $json['msg'] = "File could not be resized";
        }
        else
        {
            $json['success'] = true;
            $json['msg'] = "File has been updated";            
        }
        
        return $json;
        
    }
    
    
}
?>