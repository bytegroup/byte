<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 2:11 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_List extends MX_Controller {
    function __construct(){
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_IT-Inventory'])){
            die("not allowed");
        }
    }

    function index(){
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DAMAGE);
            $crud->set_relation('stockId', TBL_STOCK, '{stockNumber}');
            $crud->set_subject('Damage');

            $crud->field_type('creatorId', 'hidden');
            $crud->field_type('createDate', 'hidden');
            $crud->field_type('editorId', 'hidden');
            $crud->field_type('editDate', 'hidden');

            $crud->columns('stockId', 'damageFrom', 'damageType','damageQuantity');
            $crud->callback_column('damageFrom', array($this, 'callback_column_damageFrom'));
            $crud->display_as('stockId','Stock No.')
                ->display_as('damageFrom', 'Damage From')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('checkedById', 'Checked By')
                ->display_as('damageDetails', 'Details')
                ->display_as('damageRemarks', 'Remarks');

            $crud->unset_add()->unset_edit()->unset_read();

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
            $crud->add_action('Repair', '', '', 'ui-icon-wrench', array($this, 'callback_action_repair'));
            //$crud->add_action('Sell', '', IT_MODULE_FOLDER.'sell/index', 'ui-icon-calculator');
            //$crud->add_action('Garbage', '', IT_MODULE_FOLDER.'garbage/index', 'ui-icon-trash');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Damage List";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $output->body_template = "damage_list_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/
    function callback_column_damageFrom($value, $row){
        return $this->get_damage_from($row->damageId);
    }
    function callback_action_repair($key, $row){
        if($this->is_repairable($key)){
            return base_url(IT_MODULE_FOLDER.'repair/index').'/'.$key;
        }
        else return '#';
    }

    /*********************************************************************************************/
    function get_damage_from($damageId){
        if(!$damageId) return '';
        $this->db->select('issueId');
        $this->db->from(TBL_DAMAGE);
        $this->db->where('damageId', $damageId);
        $db= $this->db->get();
        if(!$db->num_rows()) return '';
        if(!$db->result()[0]->issueId) return 'Stock';
        return 'Issue';
    }
    function is_repairable($damageId){
        if(!$damageId) return false;
        $this->db->select('damageType');
        $this->db->from(TBL_DAMAGE);
        $this->db->where('damageId', $damageId);
        $db= $this->db->get();
        if(!$db->num_rows()) return false;
        if($db->result()[0]->damageType==='Repairable-Damage') return true;
        return false;
    }
}