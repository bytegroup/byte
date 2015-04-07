<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends MX_Controller {
    var $damageDetailId= 0;
    var $isAlreadyInRepair= false;
    var $isRepairComplete = false;
    var $stockId= 0;
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
       $this->isAlreadyInRepair= $this->is_already_in_repair($damageDetailId);
       $this->isRepairComplete = $this->is_repair_complete($damageDetailId);
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

            $crud->add_fields('damageDetailId', 'repairTypeId', 'repairAmount', 'vendorsId', 'repairDate', 'repairDetails', 'active', 'creatorId', 'createDate');
            $crud->edit_fields('damageDetailId', 'repairTypeId', 'repairAmount', 'vendorsId', 'repairDate', 'repairDetails', 'editorId', 'editDate');
            $crud->unset_texteditor('repairDetails');
            $crud->required_fields('repairTypeId', 'repairAmount', 'vendorsId', 'repairDate');
            $crud->field_type('damageDetailId', 'hidden', $damageDetailId);
            if($crud->getState()==='add')$crud->field_type('repairTypeId', 'dropdown', $this->repairModel->get_repair_types($damageDetailId));
            if($crud->getState()==='edit')$crud->field_type('repairTypeId', 'dropdown', $this->repairModel->get_repair_types($damageDetailId, $crud->getStateInfo()->primary_key));
            $crud->field_type('active', 'hidden', true);
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            //$crud->field_type('repairAmount', array($this, 'callback_field_repairAmount'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_repair'));

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
            $output->repairComplete= $this->isRepairComplete;
            $output->alreadyInRepair=$this->isAlreadyInRepair;
            $output->css = "";            
            $output->js = "";
            $output->pageTitle = "Repair";
            $output->base_url = base_url();
            $output->backToDamageList= base_url(IT_MODULE_FOLDER.'damage_list');
            $output->body_template = "repair_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            redirect(base_url(IT_MODULE_FOLDER.'repair_list'));
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/
    function callback_after_insert_repair($post, $key){
        $this->db->update(TBL_DAMAGE_DETAIL, array('active'=>false), array('damageDetailId'=>$this->damageDetailId));
        if($this->isAlreadyInRepair)
            $this->db->insert(TBL_REPAIR_STATUS, array('damageDetailId'=>$this->damageDetailId, 'repairId'=>$key, 'active'=>true));
        else
            $this->db->update(TBL_REPAIR_STATUS, array('repairId'=>$key), array('damageDetailId'=>$this->damageDetailId));
    }

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
    function ajax_permanent_damage_declare($damageDetailId){
        $this->db->update(TBL_DAMAGE_DETAIL, array('damageType'=>'Permanent-Damage'), array('damageDetailId'=>$damageDetailId));
        $this->db->update(TBL_REPAIR_STATUS, array('active'=>false), array('damageDetailId'=>$damageDetailId));
        $this->db->update(TBL_REPAIR, array('active'=>false), array('damageDetailId'=>$this->damageDetailId));
        $array= array(
            'redirectURL'=>base_url(IT_MODULE_FOLDER.'damage_list').'',
            'productCode'=> $this->get_product_code($damageDetailId)
        );
        echo json_encode($array);
        exit;
    }
    function ajax_add_to_stock($damageDetailId){
        $details= $this->get_stock_details($damageDetailId);

        $this->db->where('stockDetailId', $details[0]['stockDetailId']);
        $this->db->set('activeAmount', 'activeAmount + '.$details[0]['damageQty'], false);
        $this->db->set('active', true, false);
        $this->db->update(TBL_STOCK_DETAIL);

        $this->db->where('stockId', $details[0]['stockId']);
        $this->db->set('stockQuantity', 'stockQuantity + '.$details[0]['damageQty'], false);
        $this->db->update(TBL_STOCK);

        $this->db->update(TBL_REPAIR_STATUS, array('active'=>false), array('damageDetailId'=>$damageDetailId));
        $this->db->update(TBL_REPAIR, array('active'=>false), array('damageDetailId'=>$this->damageDetailId));
        $array= array(
            'redirectURL'=>base_url(IT_MODULE_FOLDER.'stock').'',
            'productCode'=> $details[0]['productCode']
        );
        echo json_encode($array);
        exit;
    }
    /*************************************************************************/

    function is_already_in_repair($damageDetailId=0){
        $db = $this->db->get_where(TBL_REPAIR_STATUS, array('damageDetailId'=>$damageDetailId));
        if(!$db->num_rows())return false;
        else return true;
    }
    function is_repair_complete($damageDetailId=0){
        $db = $this->db->get_where(TBL_REPAIR_STATUS, array('damageDetailId'=>$damageDetailId, 'active'=>false));
        if(!$db->num_rows())return false;
        else return true;
    }
    function get_stock_details($damageDetailId=0){
        $this->db->select('sd.productCode, sd.stockDetailId, s.stockId, dd.damageQuantity');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->where('dd.damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[]=array('productCode'=>$row->productCode, 'stockDetailId'=>$row->stockDetailId, 'stockId'=>$row->stockId, 'damageQty'=>$row->damageQuantity);
        }
        return $array;
    }
    function get_product_code($damageDetailId=0){
        $this->db->select('sd.productCode');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->where('dd.damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows())return '';
        else return $db->result()[0]->productCode;
    }
    function is_countable($damageDetailId){
        $this->db->select('im.itemType');
        $this->db->from(TBL_DAMAGE_DETAIL.' as dd ');
        $this->db->join(TBL_STOCK_DETAIL.' as sd ', 'sd.stockDetailId=dd.stockDetailId');
        $this->db->join(TBL_STOCK.' as s ', 's.stockId=sd.stockId');
        $this->db->join(TBL_ITEMS_MASTER.' as im ', 'im.itemMasterId=s.itemMasterId');
        $this->db->where('dd.damageDetailId', $damageDetailId);
        $db= $this->db->get();
        if(!$db->num_rows())return false;
        if($db->result()[0]->itemType==='Countable')return true;
        else return false;
    }
}