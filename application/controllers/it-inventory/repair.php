<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends MX_Controller {
    var $damageDetailId= 0;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->model('it_inventory_model', 'itModel');
        $this->load->model('repair_model', 'repairModel');
        $this->load->library("my_session");
        $this->my_session->checkSession();
        
       if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

   function index($damageDetailId){
       if(!$damageDetailId){
           redirect(base_url().IT_MODULE_FOLDER.'damage_list');
           die();
       }
       $this->damageDetailId= $damageDetailId;
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);
            
            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_REPAIR);
            $crud->set_relation("vendorsId", TBL_VENDORS, '{vendorsName}');
            $crud->where('damageDetailId', $damageDetailId);
            $crud->set_subject('Repair');

            $crud->columns('repairTypeId','repairDate','vendorsId', 'repairAmount');
            $crud->display_as('repairName','Name')
                ->display_as('repairDate','Date')
                ->display_as('repairTypeId','Repair Type')
                ->display_as('vendorsId','Vendor')
                ->display_as('repairVendorsId','Repair vendor')
                ->display_as('repairDetails','Details')
                ->display_as('repairAmount', 'Repair Amount');

            $crud->add_fields('damageDetailId', 'repairTypeId', 'repairAmount', 'vendorsId', 'repairDate', 'repairDetails', 'creatorId', 'createDate');
            $crud->edit_fields('damageDetailId', 'repairTypeId', 'repairAmount', 'vendorsId', 'repairDate', 'repairDetails', 'editorId', 'editDate');
            //var_dump($this->repairModel->get_repair_types($damageDetailId, 5));
            $crud->unset_texteditor('repairDetails');
            $crud->required_fields('repairTypeId', 'repairAmount', 'vendorsId', 'repairDate');
            $crud->field_type('damageDetailId', 'hidden', $damageDetailId);
            if($crud->getState()==='add')$crud->field_type('repairTypeId', 'dropdown', $this->repairModel->get_repair_types($damageDetailId));
            if($crud->getState()==='edit')$crud->field_type('repairTypeId', 'dropdown', $this->repairModel->get_repair_types($damageDetailId, $crud->getStateInfo()->primary_key));
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            //$crud->field_type('repairAmount', array($this, 'callback_field_repairAmount'));

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
            if(isset($this->my_session->permissions['HEADER_IT-Inventory'])){
                //$crud->add_action('Complete', "", IT_MODULE_FOLDER.'repair/complete_Repair');
            }

            $crud->unset_list()->unset_print()->unset_export()->unset_back_to_list();
            //$crud->unset_back_to_list();
            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'repair/index/'.$damageDetailId.'/add').'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $key= isset($crud->getStateInfo()->primary_key) ? $crud->getStateInfo()->primary_key : 0;
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'repair/index/'.$damageDetailId.'/edit/'.$key).'";'
                .'</script>'
                .'<div style="display:none">'
            );

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->damageDetailId= $damageDetailId;
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Repair";
            $output->base_url = base_url();
            $output->backToDamageList= base_url(IT_MODULE_FOLDER.'damage_list');
            $output->body_template = "repair_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/

    /*****************************/
    /***  ajax call functions  ***/
    /*****************************/
    /**
     * @param $repairTypeId
     */
    function ajax_get_repair_amount($repairTypeId){
        echo json_encode($this->repairModel->get_repair_amount($repairTypeId));
        exit;
    }
    function ajax_get_repair_list($damageDetailId){
        echo json_encode($this->repairModel->get_repair_list($damageDetailId));
        exit;
    }
    /*************************************************************************/

}