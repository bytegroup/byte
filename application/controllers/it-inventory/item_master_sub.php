<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Master_Sub extends MX_Controller {

    function __construct()
    {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        /* ------------------ */	
        $this->load->library("my_session");
        $this->my_session->checkSession();     
        
        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }
    
    function index()
    {
        try{
            $this->load->library('grocery_CRUD');
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();
            
            $crud->set_theme(TABLE_THEME);            
            //$crud->where(TBL_USERS.".groupId = ".COORDINATOR_LEVEL." OR ".TBL_USERS.".groupId = ", ADMIN_LEVEL);$crud->set_relation('user_id','users','username',array('status' => 'active'));
            $crud->set_table(TBL_ITEMS_MASTER_SUB);
			//$crud->where('ocl_items_master.unitId','1');
            $crud->set_relation("itemMasterId", TBL_ITEMS_MASTER, '{itemName}');
            /*$crud->set_relation("unitId", TBL_UNITS, '{unitName}');
            $crud->set_relation("categoryId", TBL_CATEGORIES, '{categoryName}');     
            $crud->set_relation("itemTypeId", TBL_ITEM_TYPES, '{itemTypeName}');    */

            $crud->set_subject('Sub Item Master');

            $crud->field_type('userId', 'hidden');
            $crud->field_type('createDate', 'hidden');
            $crud->field_type('editorId', 'hidden');
            $crud->field_type('editDate', 'hidden');
            $crud->field_type('productId', 'hidden');

            $crud->callback_add_field('active',array($this,'add_field_callback_1'));

            $crud->required_fields(array('itemMasterId','itemNameSub'));
            $crud->columns('itemMasterId','itemNameSub');
            $crud->display_as('itemNameSub','Sub Item Name')
                 ->display_as('itemMasterId','Item Name')
                ->display_as('productCode','Code ')
                ->display_as('itemDescription','Description')
                ->display_as('serialNumber','Serial Number');
                 
           // $crud->set_rules('itemName','Item Name','trim|required|xss_clean|callback_checkItemName');
            
            $time = time();
            if(!isset($this->my_session->permissions['canIT-InventoryAdd'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryEdit'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryDelete'])){
                $crud->unset_delete();
            }
            if(!isset($this->my_session->permissions['canIT-InventoryView'])){
                $crud->unset_read();
            }
            
            $output = $crud->render();
            
            $output->state = $crud->getState();
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Sub Item Master List";
			//die($output->base_url);
            $output->base_url = base_url();
            $msg=null;
            $output->msg = $msg;
            $output->body_template = "gc_it_inventory_index.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
    }

    function add_field_callback_1()
    {
        return ' <input type="radio" name="active" value="Active" checked="true"/> Active
        <input type="radio" name="active" value="Inactive" /> Inactive';
    }
    
    /*****************************/
    /*** call back validations ***/
    /*****************************/
    
    
    function checkItemName($str){
        
        $id = (int)$this->uri->segment(5);        
        $old_name = "";
        $result = null;
        
        if(!empty($id) && is_numeric($id) && $id > 0 )
        {
            $this->db->where("itemId", $id); 
            $result = $this->db->get(TBL_ITEMS_MASTER);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->itemName;                    
                }
                
                $this->db->where_not_in("itemName", array($old_name));                
                $this->db->where_not_in("itemId", array($id));                
                $this->db->where("itemName", $str);
                $num_rows = $this->db->get(TBL_ITEMS_MASTER)->num_rows();
                
                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkItemName","The %s already Exist. Please try a different Item Master name.");
                    return false;
                }
            }
        }
        else
        {
            $this->db->where("itemName", $str);            
            $num_rows = $this->db->get(TBL_ITEMS_MASTER)->num_rows();
            
            if($num_rows > 0)
            {
                $this->form_validation->set_message("checkItemName","The %s already Exist. Please try a different item master name.");
                return false;
            }
        }        
    }
    
    
    function upload_picture()
    {
        $json['success'] = false;        
        $action = $this->input->post("action",true);               
        
        if($action == "upload_picture")
        {
            $this->load->library("my_functions");            
            $this->load->library("form_validation");
                
            $userid = $this->input->post("userid",true);
            $oldpic = $this->input->post("old_picture",true);
            $this->form_validation->set_rules('userid', 'User Id', 'trim|xssclean|required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $json['msg'] = validation_errors();
                $json['success'] = false;
                echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>';
                die();
            }
            else
            {
                /***************                  
                 * UPLOAD HERE *                 
                 **************/
                $this->load->model("upload_model");
                
                $postFileName = "filename";
                $allowedExt = array('gif','jpg','jpeg','png');
                
                $upload_folder = ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER.PHOTOS_FOLDER;
                $upload_data = $this->upload_model->do_upload($postFileName, $upload_folder, $allowedExt, $maxFileSize = '1050');
                
                if($upload_data['success'] == false)
                {
                    echo '<script>parent.showUploadSuccess('.json_encode($upload_data).')</script>';                    
                    die();
                }
                
                $upload_data = $upload_data['upload_data'];
                /***** Run your Query to update rows ******/
                $data = array("profilePicture" => $upload_data['file_name']);
                $result = $this->db->where("userId",$userid)->update(TBL_USERS,$data);
                
                if(!$result)
                {
                    @unlink($upload_folder.$upload_data['file_name']);
                    $json['success'] = false;
                    $json['msg'] = "There was an error during photo upload.Please reload page and try again.";
                    echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
                    die();
                }
                else
                {
                    @unlink($upload_folder.$oldpic);
                }
                
                /***************                  
                 * Resize HERE *                 
                 **************/
                
                $resize = true;
                if($resize)
                {
                    $width = 200;
                    $height = 300;
                    $result = $this->upload_model->resize_image($upload_data['file_name'],$upload_folder,$creatThumb = false,$width,$height);
                    
                    if($result['success']== false):
                        $json['success'] = true;
                        $json['resize_success'] = false;
                        $json['msg'] = "File Uploaded but could not be resized.";
                        echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
                        die();
                    endif;
                    
                    $json['success'] = true;
                    $json['picture'] = $upload_data['file_name'];
                    $json['msg'] = "File has been uploaded.";
                    echo '<script>parent.showUploadSuccess('.json_encode($json).')</script>'; 
                    die();
                    
                }
            }
        }
    }
    
    
    
}
?>