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

    function get_data(){
        $this->db->select('c.companyName, b.budgetHead, b.budgetQuantity, b.budgetDescription, b.BudgetQuantity, b.budgetAmount, b.budgetUtilization');
        $this->db->from(TBL_BUDGET.' as b ');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=b.companyId');
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[]= array(
                'SL'                => ++$i,
                'Company Name'      => $row->companyName,
                'Budget Head'       => $row->budgetHead,
                'Budget Quantity'   => $row->budgetQuantity,
                'Budget Purpose'    => $row->budgetDescription,
                'Purchase Quantity' => $row->budgetQuantity,
                'Budget Amount'     => $row->budgetAmount,
                'Utilization'       => $row->budgetUtilization,
                'Remaining Budget'  => $row->budgetAmount - $row->budgetUtilization
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
}
?>