<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/10/14
 * Time: 9:13 PM
 */
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VendorContacts extends MX_Controller {
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if(!isset($this->my_session->permissions['HEADER_Admin'])){
            die("not allowed");
        }
    }

    function index($id){
        $this->id=$id;
        if(!$id){
            redirect(base_url().ADMIN_FOLDER.'vendors');
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
            $crud->set_table(TBL_VENDORS_CONTACTS);
            //$crud->set_relation('vendorsId', TBL_VENDORS, 'vendorsName');
            $crud->where('vendorsId',$id);
            $crud->set_subject('Vendor Contact Person');

            $crud->columns('vendorsName', 'vendorContactsPersonName', 'vendorContactsPersonalEmail', 'vendorContactsPersonalNumber');
            $crud->callback_column('vendorsName',array($this, 'callback_vendorsName'));
            $crud->display_as('vendorsId','Vendor Name')
                ->display_as('vendorContactsPersonName','Person Name')
                ->display_as('vendorContactsPersonDesignation','Person Designation')
                ->display_as('vendorContactsOfficialEmail','Official Email')
                ->display_as('vendorContactsPersonalEmail','Personal Email')
                ->display_as('vendorContactsOfficialNumber','Official Number')
                ->display_as('vendorContactsPersonalNumber','Personal Number')
                ->display_as('vendorContactsSkypeId','Skype ID');

            $crud->unique_fields('vendorContactsPersonalEmail');
            $crud->field_type('vendorsId', 'hidden', $id);
            $crud->set_rules('vendorContactsOfficialEmail', 'Official Email', 'trim|valid_email');
            $crud->set_rules('vendorContactsPersonalEmail', 'Personal Email', 'trim|valid_email');
            $crud->set_rules('vendorContactsPersonalNumber', 'Contact Number', 'numeric');

            /*if(!isset($this->my_session->permissions['canAddVendors'])){
                $crud->unset_add();
            }
            if(!isset($this->my_session->permissions['canEditVendors'])){
                $crud->unset_edit();
            }
            if(!isset($this->my_session->permissions['canDeleteVendors'])){
                $crud->unset_delete();
            }*/

            $output = $crud->render();

            $output->state = $crud->getState();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Vendor contact persons";
            $output->base_url = base_url();
            $output->backToVendorList= base_url().ADMIN_FOLDER.'vendors';
            $output->body_template = "vendor_contacts_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE,$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_vendorsName($value, $row){
        $this->db->select('vendorsName');
        $this->db->from(TBL_VENDORS);
        $this->db->where('vendorsId', $row->vendorsId);
        $result=$this->db->get();
        if($result->num_rows()){
            return $result->row()->vendorsName;
        }
        else return '';
    }

}