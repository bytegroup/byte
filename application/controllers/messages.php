<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends MX_Controller {

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
            $crud->set_table(TBL_MESSAGES);
            $crud->where('messageReceiverId',$this->my_session->userId);
            $crud->set_subject('Inbox');
            $crud->set_relation('messageSenderId','ocl_users','userName'); 
            $crud->set_relation('messageReceiverId','ocl_users','userName');           
            //$this->my_session->userOrganizationId $this->my_session->userId
            $crud->columns('messageSenderId','messageSubject','messageCreated','messageOpened');
            $crud->display_as('messageSenderId','Sender')  
                 ->display_as('messageSubject','Subject')
                 ->display_as('messageCreated','Creation Date')   
                 ->display_as('messageOpened','Status');

            $time = date("Y-m-d H:i:s");
            $crud->add_fields('messageSenderId','messageReceiverId','messageSubject','messageBody','messageCreated','organizationId');
            $crud->change_field_type('messageCreated', 'hidden', $time);
            $crud->change_field_type('messageSenderId', 'hidden', $this->my_session->userId);
            $crud->change_field_type('organizationId', 'hidden', $this->my_session->userOrganizationId);
            $crud->unset_texteditor('messageSubject','text');
			
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->add_action('View', "", 'messages/view_message');                         
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Inbox";
            //die($output->base_url);
            $output->base_url = base_url();
            
            $output->body_template = "gc_common.php";
            $this->load->view("vcard_layout.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
    }
    function sentMessages()
    {
        try{
            $this->load->library('grocery_CRUD');
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();
            
            $crud->set_theme(TABLE_THEME);            
            $crud->set_table(TBL_MESSAGES);
            $crud->where('messageSenderId',$this->my_session->userId);
            $crud->set_subject('Outbox');
            $crud->set_relation('messageReceiverId','ocl_users','userName');            
            $crud->columns('messageReceiverId','messageSubject','messageCreated','messageOpened');
            $crud->display_as('messageReceiverId','Receiver')  
                 ->display_as('messageSubject','Subject')
                 ->display_as('messageCreated','Creation Date')   
                 ->display_as('messageOpened','Status');
            
            $time = time();
            $crud->add_action('View', "", 'messages/view_message');                         
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();

            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $this->load->library("my_functions");
        	
            $output->pageTitle = "Outbox";
            //die($output->base_url);
            $output->base_url = base_url();
            
            $output->body_template = "gc_common.php";
            $this->load->view("vcard_layout.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
    }
    function testEntry()
    {
        $this->db->query("INSERT INTO Games (player1, player2,status,score1,score2,score3,score4,score5,score6) VALUES ('qqq','www', '0', '-1','-1','-1','-1','-1','-1')");
        $insertId = $this->db->insert_id();
        echo $insertId." || Query ".$this->db->last_query();
    }
    function view_message($primary_key)
    {
        $this->load->library("my_functions");
        $data['css'] = "";
        $data['css'] .= $this->my_functions->addCss("grocery_crud/themes/datatables/css/datatables.css",true);
        $data['css'] .= $this->my_functions->addCss("grocery_crud/css/jquery_plugins/chosen/chosen.css",true);
        $data['css'] .= $this->my_functions->addCss("grocery_crudcss/ui/simple/jquery-ui-1.8.23.custom.css",true);;
        $data['js'] = "";

        $this->db->select('m.*, u1.userName, og.organizationName,u2.userName as senderName');
        $this->db->from(TBL_MESSAGES." as m ");
        $this->db->join(TBL_USERS." as u1 ", 'u1.userId = m.messageReceiverId');
        $this->db->join(TBL_ORGANIZATIONS . " as og ", 'og.organizationId = m.organizationId');
        $this->db->join(TBL_USERS . " as u2 ", 'u2.userId = m.messageSenderId');
        $this->db->where('m.messageId = '.$primary_key);
        $result = $this->db->get();

        if($result->num_rows() > 0):
            $data['messageInfo'] = $result->result();
        endif;
        //var_dump($data['messageInfo'][0]);die();
        $data['css_files'] = array();
        $data['js_files'] = array();
        $data['pageTitle'] = "View Messages";
        $data['base_url'] = base_url();
        if($data['messageInfo'][0]->messageSenderId != $this->my_session->userId)
            $this->changeMessageStatus($primary_key);
		
        $data['body_template'] = "message_view.php";   
    	$this->load->view("vcard_layout.php",$data); 
    }
	
    function changeMessageStatus($mid){
        $data = array(
           'messageOpened' => 'Read'
        );

        $this->db->where('messageId', $mid);
        $this->db->update(TBL_MESSAGES, $data); 
    }
	
	
    /*****************************/
    /*** call back validations ***/
    /*****************************/
  	
    function checkUnitName($str){
        
        $id = (int)$this->uri->segment(5);        
        $old_name = "";
        $result = null;
        
        if(!empty($id) && is_numeric($id) && $id > 0 )
        {
            $this->db->where("unitId", $id); 
            $result = $this->db->get(TBL_UNITS);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->unitName;                    
                }
                
                $this->db->where_not_in("unitName", array($old_name));                
                $this->db->where_not_in("unitId", array($id));                
                $this->db->where("unitName", $str);
                $num_rows = $this->db->get(TBL_UNITS)->num_rows();
                
                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkUnitName","The %s already Exist. Please try a different unit name.");
                    return false;
                }
            }
        }
        else
        {
            $this->db->where("unitName", $str);            
            $num_rows = $this->db->get(TBL_UNIT)->num_rows();
            
            if($num_rows > 0)
            {
                $this->form_validation->set_message("checkUnitName","The %s already Exist. Please try a different unit name.");
                return false;
            }
        }        
    }
    
    
}