<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phonebook extends MX_Controller {

    function __construct()
    {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */	
        $this->load->library("my_session");
        $this->my_session->checkSession();     
    }
    

   function index()
    {
        try{
            $this->load->library('grocery_CRUD');
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_PHONEBOOK);
            $crud->set_subject('Phone Book');
            $crud->columns('contactName','contactPhoneNo','contactMobileNo','contactEmail');
            $crud->display_as('contactName','Contact Name')  
                 ->display_as('contactPhoneNo','Phone')
                 ->display_as('contactMobileNo','Mobile')   
                 ->display_as('contactEmail','Contact Email')
                 ->display_as('contactAddress','Address');

            
            $time = time();
            $crud->add_fields('contactName','contactPhoneNo','contactMobileNo','contactEmail','createdUserId','contactAddress','contactCreated');
            $crud->change_field_type('contactCreated', 'hidden', $time);
            $crud->change_field_type('createdUserId', 'hidden', $this->my_session->userId);
            $crud->unset_texteditor('contactAddress','text');
            $crud->set_rules("conatctEmail", "Email", "required|valid_email|callback_checkContactEmail");
			
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Phone Book";
            //die($output->base_url);
            $output->base_url = base_url();
            
            $output->body_template = "gc_common.php";
            $this->load->view("vcard_layout.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
	
    /*****************************/
    /*** call back validations ***/
    /*****************************/
  	
    function checkContactEmail($str){
        
        $id = (int)$this->uri->segment(5);        
        $old_name = "";
        $result = null;
        
        if(!empty($id) && is_numeric($id) && $id > 0 )
        {
            $this->db->where("contactId", $id); 
            $result = $this->db->get(TBL_PHONEBOOK);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->contactEmail;                    
                }
                
                $this->db->where_not_in("contactEmail", array($old_name));                
                $this->db->where_not_in("contactId", array($id));                
                $this->db->where("contactEmail", $str);
                $num_rows = $this->db->get(TBL_PHONEBOOK)->num_rows();
                
                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkContactEmail","The %s already Exist. Please try a different contact email.");
                    return false;
                }
            }
        }
        else
        {
            $this->db->where("contactEmail", $str);            
            $num_rows = $this->db->get(TBL_PHONEBOOK)->num_rows();
            
            if($num_rows > 0)
            {
                $this->form_validation->set_message("checkContactEmail","The %s already Exist. Please try a different contact email.");
                return false;
            }
        }        
    }
    
    
}