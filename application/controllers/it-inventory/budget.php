<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends MX_Controller{
    function __construct(){
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');

        /* ------------------ */
        $this->load->library("my_session");
        $this->my_session->checkSession();

        if (!isset($this->my_session->permissions['HEADER_IT-Inventory'])) {
            die("not allowed");
        }
    }

    function index(){
        try {
            $this->load->library('grocery_CRUD');
            //$this->load->library('gc_extended_unique_validation');

            $crud = new grocery_CRUD($this);
            //$crud = new GC_Extended_unique_validation($this);
            $crud->unset_jquery();

            $dateString = "%d-%m-%y :: %h:%i %a";
            $time = time();
            $time = mdate($dateString, $time);

            $crud->set_theme(TABLE_THEME);
            $crud->set_table(TBL_BUDGET);
            $crud->set_relation('budgetHeadId', TBL_BUDGET_HEAD, '{budgetHead}');
            $crud->set_subject('Budget');

            $crud->columns('budgetHeadId', 'budgetYear');
            $crud->display_as('budgetHeadId', 'Budget Head')
                ->display_as('budgetYear', 'Budget Year')
                ->display_as('companies', 'Companies')
                ->display_as('budgetUtilization', 'Utilization');

            $crud->add_fields('budgetHeadId', 'budgetYear', 'companies', 'creatorId', 'createDate');
            $crud->edit_fields('budgetHeadId', 'budgetYear', 'companies', 'editorId', 'editDate');
            $crud->set_read_fields('budgetHeadId', 'budgetYear');
            $crud->required_fields(array('budgetHeadId', 'budgetYear'));
            //$crud->unique_field_in_group('budgetHeadId/companyId');
            $crud->field_type('budgetYear', 'dropdown', $this->year_generator(2010, 20));
            $crud->field_type('creatorId', 'hidden', $this->my_session->userId);
            $crud->field_type('createDate', 'hidden', $time);
            $crud->field_type('editorId', 'hidden', $this->my_session->userId);
            $crud->field_type('editDate', 'hidden', $time);
            $crud->callback_field('companies', array($this, 'callback_field_companies'));
            $crud->callback_after_insert(array($this, 'callback_after_insert_budget'));
            $crud->callback_after_update(array($this, 'callback_after_update_budget'));

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
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Budget List";
            $output->base_url = base_url();

            $output->body_template = "budget_view.php";
            $this->load->view(MAIN_TEMPLATE_FILE, $output);

        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /*****************************/
    /*** call back functions ***/
    /*****************************/
    function callback_field_companies($row, $key){
        $companies= $this->get_company_list();
        $budgets= $key ? $this->get_budget_details($key) : array();
        $html = '';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= '<ul class="company-table-header">';
        $html .= '<li>Company</li><li>Quantity</li><li>Amount</li>';
        $html .= '</ul>';
        $html .= '</li>';
        foreach ($companies as $id => $company) {

             if(isset($budgets[$id])) {
                 $amount= $budgets[$id]['amount'];
                 $qty   = $budgets[$id]['qty'];
             } else{$amount='';  $qty   = '';}

            $html .= '<li><ul>';
            $html .= '<li>' . $company . '</li>';
            $html .= '<li><input type="number" name="qty[]" maxlength="21" min="0" value="'.$qty.'"/></li>';
            $html .= '<li><input type="number" name="amount[]" maxlength="21" min="0" value="'.$amount.'"/></li>';
            $html .= '<input type="hidden" name="companyId[]" value="' . $id . '"/>';
            $html .= '</ul></li>';
        }
        $html .= '<li>';
        $html .= '<ul>';
        $html .= '<li>&nbsp;</li><li>&nbsp;</li><li>Total = <span id="total-amount">0.0</span></li>';
        $html .= '</ul>';
        $html .= '</li>';
        $html .= '</ul>';

        return $html;
    }

    function callback_after_insert_budget($post, $key){
        $companyIds= $post['companyId'];
        foreach ($companyIds as $index => $companyId) {
            $this->db->insert(
                TBL_BUDGET_DETAIL,
                array(
                    'budgetId' => $key,
                    'companyId' => $companyId,
                    'budgetQuantity'=> $post['qty'][$index],
                    'budgetAmount' => $post['amount'][$index]
                )
            );
        }
    }
    function callback_after_update_budget($post, $key){
        $companyIds= $post['companyId'];
        foreach ($companyIds as $index => $companyId) {
            $this->db->update(
                TBL_BUDGET_DETAIL,
                array(
                    'budgetQuantity'=> $post['qty'][$index],
                    'budgetAmount' => $post['amount'][$index]
                ),
                array(
                    'budgetId'=>$key,
                    'companyId'=>$companyId
                )
            );
            if(!$this->db->affected_rows()){
                $this->db->insert(
                    TBL_BUDGET_DETAIL,
                    array(
                        'budgetId' => $key,
                        'companyId' => $companyId,
                        'budgetQuantity'=> $post['qty'][$index],
                        'budgetAmount' => $post['amount'][$index]
                    )
                );
            }
        }
    }

    /*********************************************************************************/
    function get_company_list(){
        $db= $this->db->get(TBL_COMPANIES);
        if(!$db->num_rows()) return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->companyId]= $row->companyName;
        }
        return $array;
    }
    function get_budget_details($budgetId){
        if(!$budgetId)return array();
        $db= $this->db->get_where(TBL_BUDGET_DETAIL, array('budgetId'=> $budgetId));
        if(!$db->num_rows()) return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->companyId]= array('qty'=>$row->budgetQuantity, 'amount'=> $row->budgetAmount);
        }
        return $array;
    }
    /**
     * @param $start
     * @param $noOfYear
     * @return array
     */
    function year_generator($start, $noOfYear){
        $year= array();
        for($i=0; $i<=$noOfYear; $i++){$year[$start + $i]=$start+$i;}
        return $year;
    }
}
?>