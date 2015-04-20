<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/20/15
 * Time: 7:17 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_Disposal extends MX_Controller {
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
            $crud->set_table(TBL_DISPOSAL);
            $crud->set_relation('handoverTo', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_relation('checkedBy', TBL_USERS, '{firstName} {middleName} {lastName}');
            /*$crud->where('damageType', 'Permanent-Damage');*/
            $crud->set_subject('Disposal of Damage');

            $crud->columns('handoverTo', 'checkedBy', 'disposalDate');
            $crud->display_as('handoverTo','Handover To')
                ->display_as('checkedBy', 'Checked By')
                ->display_as('disposalDate','Disposal Date');

            $crud->add_fields('handoverTo', 'checkedBy', 'disposalDate', 'creatorId', 'createDate');
            $crud->edit_fields('handoverTo', 'checkedBy', 'disposalDate', 'editorId', 'editDate');

            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            //$crud->callback_field('itemName', array($this, 'callback_field_itemName'));

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
            $output->pageTitle = "Disposed Product List";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'damage_disposal');
            $output->body_template = "damage_disposal_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/


    /*********************************************************************************************/

}