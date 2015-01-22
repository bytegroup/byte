<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of home
 *
 * @author USER
 */
Class Report extends MX_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library('my_session');        
        $this->my_session->checkSession();
    }
    
    function vcard_list()
    {  
        $this->load->model("users_model");
        $this->load->library('pagination');

        $conf['base_url'] = base_url().ADMIN_FOLDER.'report/vcard_list/';
        $query = $this->users_model->getAllVcard();
        
        $num_row = 0;
        if($query)
        {
            $num_row = $query->num_rows();
        }
        
        $conf['total_rows'] = ($num_row > 0) ? $num_row:0; 
        
        $result_per_page = (int)$this->input->get("rpp",true);
        $conf['per_page'] = ($result_per_page <= 0)? 10: $result_per_page;
        
        $conf['num_links'] = 4;                
        $conf['uri_segment'] = 4;
        
        /********/
        $conf['prev_tag_open'] = '<li>';
        $conf['prev_tag_close'] = '</li>';
        $conf['next_tag_open'] = '<li>';
        $conf['next_tag_close'] = '</li>';
        
        $conf['cur_tag_open'] = '<li class="active"><a href="#">';
        $conf['cur_tag_close'] = '</a></li>';
        
        $conf['num_tag_open'] = '<li>';
        $conf['num_tag_close'] = '</li>';
        /*******/
        $page['limit'] = $conf['per_page'];
        
        $this->pagination->initialize($conf);
        
        $data['si'] = $page['offset'] = (int)$this->uri->segment(4);        
        
        $result = $this->users_model->getAllVcard($page);        
        
        //echo $this->db->last_query();
        $data['query'] = $result;       
        $data['num_rows'] = $num_row;        
        
        $this->load->library("my_functions");        
        /** initialization **/
        $data['css'] = "";      
        $data['css'] .= $this->my_functions->addCss("table_sorter.css");
        
        $data['js'] = "";        
        $data['js'] .= $this->my_functions->addJs("jquery.tablesorter.min.js");        
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        $data['pageTitle'] = "vCard List";
        
        $data['body_template'] = "vcard_list.php";
        $this->load->view(MAIN_TEMPLATE_FILE,$data);
    }
    
}

?>