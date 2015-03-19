<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/3/15
 * Time: 2:37 PM
 */
?>
<?php
class Company_Budget_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $this->db->select('c.companyName, b.budgetId, b.budgetHead, b.budgetQuantity, b.budgetDescription, b.BudgetQuantity, b.budgetAmount, b.budgetUtilization');
        $this->db->from(TBL_BUDGET.' as b ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=b.companyId');

        if($filters['year']) $this->db->where('b.budgetYear', $filters['year']);
        if($filters['company'])$this->db->where('b.companyId', $filters['company']);
        if($filters['budget_head'])$this->db->where('b.budgetHead', $filters['budget_head']);
        if($filters['budget_type'])$this->db->where('b.budgetType', $filters['budget_type']);

        return $db=$this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('c.companyName, b.budgetId, b.budgetHead, b.budgetQuantity, b.budgetDescription, b.BudgetQuantity, b.budgetAmount, b.budgetUtilization');
        $this->db->from(TBL_BUDGET.' as b ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=b.companyId');
        return $db=$this->db->get();
    }

    function get_data($filters= array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[$row->budgetId]= array(
                ++$i,
                $row->companyName,
                $row->budgetHead,
                $row->budgetQuantity,
                $row->budgetDescription,
                $row->budgetQuantity,
                $row->budgetAmount,
                $row->budgetUtilization,
                $row->budgetAmount - $row->budgetUtilization
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Company Name',
            'Budget Head',
            'Budget Quantity',
            'Budget Purpose',
            'Purchase Quantity',
            'Budget Amount',
            'Utilization',
            'Remaining Budget'
        );
    }

    function get_company_list(){
        $db= $this->db->get(TBL_COMPANIES);
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->companyId]= $row->companyName;
        }
        return $array;
    }
    function get_budget_head_list($companyId=0){
        $db= !$companyId ?
            $this->db->group_by('budgetHead')->get(TBL_BUDGET)
            :
            $this->db->group_by('budgetHead')->get_where(TBL_BUDGET, array('companyId'=>$companyId));
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->budgetHead]= $row->budgetHead;
        }
        return $array;
    }

    public function get_filters(){
        return array(
            'Year'       => array('select', $this->year_generator(2010, 20)),
            'Budget Type'      => array('select', array('Capital'=> 'CAPITAL', 'Revenue'=> 'REVENUE')),
            'Company'       => array('select', $this->get_company_list()),
            'Budget Head'    => array('select', $this->get_budget_head_list())
        );
    }
    function year_generator($start, $noOfYear){
        $year= array();
        for($i=0; $i<=$noOfYear; $i++){$year[$start + $i]=$start+$i;}
        return $year;
    }
}
?>