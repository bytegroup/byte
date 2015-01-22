<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Organizations_model extends CI_Model {
    function __construct() {
        parent::__construct();
		$this->load->library("my_helper");
    }
    
   
    function getAllOrganizations()
    {
        $result = $this->db->get(TBL_ORGANIZATIONS);
        if($result->num_rows() > 0){
            return $result;
        }
        return false;        
    }
 	function getOrganizationNames(){
		$orgList = $this->my_helper->printOrganizationsList();
		return $orgList;
	}
    
}

?>