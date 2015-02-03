<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/3/15
 * Time: 3:26 PM
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_Damage extends MX_Controller {
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
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

    function index($stockId){
        if(!$stockId){
            redirect(base_url().IT_MODULE_FOLDER.'stock');
            die();
        }
        try{
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time= mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_DAMAGE);
            $crud->set_relation("checkedById", TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->where('stockId', $stockId);
            $crud->set_subject('Damage');

            $crud->columns('itemMasterId','damageType','damageQuantity','damageDate');
            $crud->display_as('itemMasterId','Product')
                ->display_as('stockNumber', 'Stock No.')
                ->display_as('damageType','Damage Type')
                ->display_as('damageQuantity','Damage Quantity')
                ->display_as('damageDate','Damage Date')
                ->display_as('checkedById', 'Checked By')
                ->display_as('damageDetails', 'Details')
                ->display_as('damageRemarks', 'Remarks');

            $crud->add_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity', 'items', 'creatorId', 'createDate');
            $crud->edit_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity', 'items', 'editorId', 'editDate');
            $crud->set_read_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageDetails', 'damageRemarks', 'damageQuantity');
            $crud->required_fields('stockId', 'damageType', 'damageDate', 'checkedById', 'damageQuantity');
            $crud->unset_texteditor('damageDetails', 'damageRemarks');
            $crud->field_type('stockId', 'hidden', $stockId);
            $crud->field_type('damageType', 'enum', array('Repairable-Damage', 'Permanent-Damage'));
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('items', array($this, 'callback_field_items'));

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
            $crud->add_action('Repair', "", IT_MODULE_FOLDER.'repair/index', 'ui-icon-wrench');

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->stockInfo= $this->get_stock_info($stockId);
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Damaged Products From Stock";
            $output->base_url = base_url();
            $output->backToStockList= base_url(IT_MODULE_FOLDER.'stock');
            $output->body_template = "stock_damage_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /***  callback functions  ***/
    /*****************************/
    function callback_field_items($row, $key){
        $items= array();

        $html='';

        $html .= '<ul>';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>&nbsp;</li><li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html .= '</ul>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<ul>';
        $html .= '<li></li>';
        $html .= '<li></li>';
        $html .= '<li></li>';
        $html .= '</ul>';
        $html .= '</li>';

        $html .= '</ul>';

        return $html;
    }

    /*************************************************************************/
    function get_stock_info($stockId){
        if(!$stockId) return array();
        $this->db->select('s.stockNumber, s.stockQuantity, i.itemName, c.categoryName');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=s.itemMasterId');
        $this->db->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId');
        $this->db->where('stockId', $stockId);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(
            'stockNumber' => $db->result()[0]->stockNumber,
            'qty'=> $db->result()[0]->stockQuantity,
            'item' => $db->result()[0]->itemName,
            'category'=>$db->result()[0]->categoryName
        );

        return $array;
    }
    function get_stock_items($stockId){
        if(!$stockId) return array();
        $this->db->select('rd.productCode, rd.warrantyEndDate');
        $this->db->from(TBL_STOCK.' as s ');
        $this->db->join(TBL_RECEIVES_DETAIL.' as rd ', 'rd.itemMasterId=s.itemMasterId');
        $this->db->where(array('stockId'=> $stockId, 'rd.issueId>'=>0));
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row):

        endforeach;
    }
}