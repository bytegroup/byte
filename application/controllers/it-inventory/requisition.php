<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition extends MX_Controller{

    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library("my_functions");
        $this->load->model("requisition_model");

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index(){
        try {
            $this->load->library('grocery_CRUD');
            $crud = new grocery_CRUD($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_REQUISITIONS);
            $crud->set_relation('requisitionDetailId', TBL_REQUISITIONS_DETAIL, '{itemMasterId}');
            $crud->set_relation('userId', TBL_USERS, '{firstName} {middleName} {lastName}');
            $crud->set_relation("departmentId", TBL_DEPARTMENTS, '{departmentName}');
            $crud->set_relation("organizationId", TBL_ORGANIZATIONS, '{organizationName}');
            $crud->set_relation('companyId', TBL_COMPANIES, '{companyName}');
            $crud->where(TBL_REQUISITIONS.'.requisitionType', 'Buy');
            $crud->set_subject('Requisition');

            $crud->columns('requisitionTitle', 'requisitionCreateDate', 'requisitionFor');
            $crud->display_as('requisitionTitle', 'Title')
                ->display_as('requisitionNumber', 'Requisition Number')
                ->display_as('organizationId', 'Organization')
                ->display_as('companyId', 'Company')
                ->display_as('departmentId', 'Department')
                ->display_as('requisitionDetailId', 'Item')
                ->display_as('requisitionCreateDate', 'Date')
                ->display_as('requisitionDescription', 'Description')
                ->display_as('requisitionItems', 'Items')
                ->display_as('userId', 'Staff')
                ->display_as('requisitionFor', 'Requisition For')
                ->display_as('requisitionApproved', 'Approved');

            $crud->add_fields('requisitionTitle', 'requisitionFor', 'companyId', 'departmentId', 'userId', 'requisitionCreateDate', 'requisitionDescription', 'requisitionItems', 'creatorId', 'createDate');
            $crud->edit_fields('requisitionNumber', 'requisitionTitle', 'requisitionFor', 'companyId', 'departmentId', 'userId', 'requisitionCreateDate', 'requisitionDescription', 'requisitionItems', 'editorId', 'editDate');
            $crud->set_read_fields('requisitionTitle', 'requisitionFor', 'companyId', 'departmentId', 'userId', 'requisitionCreateDate', 'requisitionDescription', 'requisitionItems');
            $crud->required_fields(array('requisitionTitle', 'companyId', 'requisitionFor'));
            $crud->unset_texteditor('requisitionDescription');
            $crud->field_type('requisitionNumber', 'readonly');
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('requisitionFor', array($this, 'field_callback_requisitionFor'));
            $crud->callback_field('requisitionItems', array($this, 'field_callback_requisitionItems'));
            $crud->callback_read_field('requisitionItems', array($this, 'callback_read_field_requisitionItems'));
            $crud->callback_after_insert(array($this, 'callback_to_insert_requisitionNumberAndDetails'));
            $crud->callback_after_update(array($this, 'callback_to_update_requisitionDetails'));


            //$crud->add_action('Items', "", IT_MODULE_FOLDER . 'requisition_items/index', 'ui-icon-clipboard');
            $crud->add_action('Quotations', "", IT_MODULE_FOLDER . 'quotation/index', 'ui-icon-notice');

            //$crud->unset_back_to_list();
            $crud->set_lang_string(
                'insert_success_message',
                'Data stored successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'requisition').'";'
                .'</script>'
                .'<div style="display:none">'
            );
            $crud->set_lang_string(
                'update_success_message',
                'Data updated successfully.'
                .'Please wait while you are redirecting to the list page.'
                .'<script type="text/javascript">'
                .'window.location = "'.base_url(IT_MODULE_FOLDER.'requisition').'";'
                .'</script>'
                .'<div style="display:none">'
            );

            if (!isset($this->my_session->permissions['canIT-InventoryAdd'])) {
                $crud->unset_add();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryEdit'])) {
                $crud->unset_edit();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryDelete'])) {
                $crud->unset_delete();
            }
            if (!isset($this->my_session->permissions['canIT-InventoryView'])) {
                $crud->unset_read();
            }



            $output = $crud->render();

            $output->state = $crud->getState();
            if($crud->getState()=='edit'){
                $output->itemDetails = $this->get_requisition_item_details($crud->getStateInfo()->primary_key);
            }
            else $output->itemDetails= json_encode(array());
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Requisition List";
            $output->base_url = base_url();
            $output->cancel=base_url(IT_MODULE_FOLDER.'requisition');

            $output->body_template = "requisition_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function field_callback_requisitionFor($requisitionFor){
        $checkedCompany = $requisitionFor == 'Company' ? 'checked="true"' : '';
        $checkedDepartment = $requisitionFor == 'Department' ? 'checked="true"' : '';
        $checkedStaff = $requisitionFor == 'Staff' ? 'checked="true"' : '';

        return ' <input type="radio" name="requisitionFor" value="Company" ' . $checkedCompany . ' /> Company
                 <input type="radio" name="requisitionFor" value="Department" ' . $checkedDepartment . '/> Department
                 <input type="radio" name="requisitionFor" value="Staff" ' . $checkedStaff . '/> Staff';
    }

    function field_callback_requisitionItems($value = '', $primary_key = null){
        $html = '';
        $html .= '<ul class="add_requisitionItems">';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Category</li><li>Item</li><li>Quantity</li><li>&nbsp;</li>';
        $html .= '</ul>';
        $html .= '</li>';

        /*$html .= '<li class="itemRow-1">';
        $html .= '<ul>';
        $html .= '<li><select id="field-1-cat" data-placeholder="Select Category" name="item-1-cat"></select></li>';
        $html .= '<li><select id="field-1-item" data-placeholder="Select Item" name="item-1-item"></select></li>';
        $html .= '<li><input type="number" id="field-1-quantity" min="0" name="item-1-quantity"/></li>';
        $html .= '<li>
                <a role="button" id="1" class="close_row_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary">
                    <span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>
                    <span class="ui-button-text">Delete</span>
                </a>
            </li>';
        $html .= '</ul>';
        $html .= '</li>';*/

        $html .= '</ul>';
        $html .= '<input type="hidden" name="numberOfItems" value="0"/>';
        $html .= '
            <a role="button" class="add_more_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary">
                <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
                <span class="ui-button-text">Add More Item</span>
            </a>
                    ';
        return $html;
    }
    function callback_read_field_requisitionItems($row, $key){
        $items= $this->get_requisition_items($key);

        $html = '';
        $html .= '<ul class="read_requisitionItems">';

        $html .= '<li>';
        $html .= '<ul class="items-table-header">';
        $html .= '<li>Category</li><li>Item</li><li>Quantity</li>';
        $html .= '</ul>';
        $html .= '</li>';

        foreach($items as $item):
        $html .= '<li>';
        $html .= '<ul>';
        $html .= '<li>'.$item['cat'].'</li>';
        $html .= '<li>'.$item['item'].' <small>('.$item['unit'].')</small></li>';
        $html .= '<li>'.$item['quantity'].'</li>';
        $html .= '</ul>';
        $html .= '</li>';
        endforeach;

        $html .= '</ul>';

        return $html;
    }

    function callback_to_insert_requisitionNumberAndDetails($postData, $requisitionId){
        $this->db->select("companyCode")
            ->from(TBL_COMPANIES)
            ->where('companyId', $postData['companyId']);
        $db = $this->db->get();
        $row = $db->result();
        $code = $row[0]->companyCode;
        $this->db->update(
            TBL_REQUISITIONS,
            array('requisitionNumber' => '' . $code . '/REQ/' . mdate("%y", time()) . '/' . $requisitionId),
            array('requisitionId' => $requisitionId)
        );

        $items= array();
        $numberOfItems= $postData['numberOfItems'];
        for($i= 1; $i<= $numberOfItems; $i++){
            if(
                isset($postData['item-'.$i.'-cat'])
                && isset($postData['item-'.$i.'-item'])
                && isset($postData['item-'.$i.'-quantity'])
            ){
                $this->db->insert(
                    TBL_REQUISITIONS_DETAIL,
                    array(
                        "requisitionId"     => $requisitionId,
                        "itemMasterId"      => $postData['item-'.$i.'-item'],
                        "categoryId"        => $postData['item-'.$i.'-cat'],
                        "orderedQuantity"   => $postData['item-'.$i.'-quantity']
                    )
                );
            }
        }
    }
    function callback_to_update_requisitionDetails($postData, $requisitionId){
        $this->db->delete(TBL_REQUISITIONS_DETAIL, array('requisitionId' => $requisitionId));
        $numberOfItems= $postData['numberOfItems'];
        for($i= 1; $i<= $numberOfItems; $i++){
            if(
                isset($postData['item-'.$i.'-cat'])
                && isset($postData['item-'.$i.'-item'])
                && isset($postData['item-'.$i.'-quantity'])
            ){
                $this->db->insert(
                    TBL_REQUISITIONS_DETAIL,
                    array(
                        "requisitionId"     => $requisitionId,
                        "itemMasterId"      => $postData['item-'.$i.'-item'],
                        "categoryId"        => $postData['item-'.$i.'-cat'],
                        "orderedQuantity"   => $postData['item-'.$i.'-quantity']
                    )
                );
            }
        }
    }

    /*****************************/
    /***  ajax call functions  ***/
    /*****************************/
    function ajax_get_department($sourceId){
        if ($sourceId <= 0) {
            echo json_encode(null);
            exit;
        }
        $this->db->select("departmentId, departmentName")
            ->from(TBL_DEPARTMENTS)
            ->where('companyId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->departmentId, "property" => $row->departmentName);
        endforeach;
        echo json_encode($array);
        exit;
    }

    function ajax_get_staff($sourceId){
        if ($sourceId <= 0) {
            echo json_encode(null);
            exit;
        }
        $this->db->select("userId, firstName, middleName, lastName")
            ->from(TBL_USERS)
            ->where('userId', $sourceId);
        $db = $this->db->get();
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->userId, "property" => $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName);
        endforeach;
        echo json_encode($array);
        exit;
    }

    function ajax_get_category(){
        $this->db->select("categoryId, categoryName")
            ->from(TBL_CATEGORIES);
        $db = $this->db->get();
        $array = array();
        if(!$db->num_rows()) {echo json_encode($array); exit;}
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->categoryId, "property" => $row->categoryName);
        endforeach;
        echo json_encode($array);
        exit;
    }

    function ajax_get_items($catId=0){
        $this->db->select("itemMasterId, itemName");
        $this->db->from(TBL_ITEMS_MASTER);
        if($catId)
            $this->db->where('categoryId', $catId);
        $db = $this->db->get();
        $array = array();
        if(!$db->num_rows()) {echo json_encode($array); exit;}
        foreach ($db->result() as $row):
            $array[] = array("value" => $row->itemMasterId, "property" => $row->itemName);
        endforeach;
        echo json_encode($array);
        exit;
    }
    /***********************************************************************************************************/
    function get_requisition_item_details($requisitionId=0){
        if(!$requisitionId){return json_encode(array());}
        $this->db->select("*")
            ->from(TBL_REQUISITIONS_DETAIL)
            ->where('requisitionId', $requisitionId);
        $db = $this->db->get();
        if(!$db->num_rows()){return json_encode(array());}
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array(
                "cat"       => $row->categoryId,
                "item"      => $row->itemMasterId,
                "quantity"  => $row->orderedQuantity
            );
        endforeach;
        return json_encode($array);
    }
    function get_requisition_items($reqId){
        if(!$reqId){return array();}
        $this->db->select("rd.orderedQuantity, c.categoryName, i.itemName, u.unitName")
            ->from(TBL_REQUISITIONS_DETAIL.' as rd ')
            ->join(TBL_ITEMS_MASTER.' as i ', 'i.itemMasterId=rd.itemMasterId')
            ->join(TBL_CATEGORIES.' as c ', 'c.categoryId=i.categoryId')
            ->join(TBL_UNITS.' as u ', 'u.unitId=i.unitId')
            ->where('requisitionId', $reqId);
        $db = $this->db->get();
        if(!$db->num_rows()){return array();}
        $array = array();
        foreach ($db->result() as $row):
            $array[] = array(
                "cat"       => $row->categoryName,
                "item"      => $row->itemName,
                "unit"      => $row->unitName,
                "quantity"  => $row->orderedQuantity
            );
        endforeach;
        return $array;
    }
}
?>