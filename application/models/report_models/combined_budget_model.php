<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/20/15
 * Time: 2:44 PM
 */
?>
<?php
class Combined_Budget_Model extends CI_Model {
    var $companyList= null;
    function __construct(){
        parent::__construct();
    }

    public function set_companyList(){
        $this->companyList= $this->get_company_list();
    }
    public function get_companyList(){
        return $this->companyList;
    }

    private function _common_data($year=0){
        $this->db->select('b.budgetId, bh.budgetHead, bh.budgetPurpose');
        $this->db->from(TBL_BUDGET.' as b ');
        $this->db->join(TBL_BUDGET_HEAD.' as bh ', 'bh.budgetHeadId=b.budgetHeadId');
        if($year)$this->db->where('b.budgetYear', $year);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->budgetId]= array('head'=> $row->budgetHead, 'purpose'=> $row->budgetPurpose);
        }
        return $array;
    }
    private function _comparison_data($companyId, $year=0){
        $this->db->select('b.budgetId, bd.budgetAmount, bd.budgetUtilization');
        $this->db->from(TBL_BUDGET.' as b ');
        $this->db->join(TBL_BUDGET_DETAIL.' as bd ', 'bd.budgetId=b.budgetId');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=bd.companyId');
        $this->db->where('bd.companyId', $companyId);
        if($year)$this->db->where('b.budgetYear', $year);
        $db=$this->db->get();
        if(!$db->num_rows()) return null;
        $array= array();
        foreach($db->result() as $row){
            $array[$row->budgetId]= array('amount'=> $row->budgetAmount, 'utilization'=> $row->budgetUtilization, 'remaining'=>$row->budgetAmount - $row->budgetUtilization);
        }
        return $array;
    }

    function get_data($filters= array()){
        $tempArray= null;
        $companyRows= null;
        $commonRows= $this->_common_data(isset($filters['year'])? $filters['year']:0);
        $i = 0;
        foreach ($this->companyList as $id=>$company):
            $companyRows[$id]= $this->_comparison_data($id, isset($filters['year'])? $filters['year']:0);
        endforeach;
        foreach($commonRows as $budgetId=>$row){
            $total= 0.0;
            $tempArray[$budgetId]=array();
            array_push($tempArray[$budgetId], ++$i);
            array_push($tempArray[$budgetId], $row['head']);
            array_push($tempArray[$budgetId], $row['purpose']);
            foreach($companyRows as $companyId=>$data){
                if(isset($companyRows[$companyId][$budgetId])){
                    array_push($tempArray[$budgetId], $companyRows[$companyId][$budgetId]['amount']);
                    $total += $companyRows[$companyId][$budgetId]['amount'];
                }else{
                    array_push($tempArray[$budgetId], '');
                }
            }
            array_push($tempArray[$budgetId], $total);
            $total=0.0;
            foreach($companyRows as $companyId=>$data){
                if(isset($companyRows[$companyId][$budgetId])){
                    array_push($tempArray[$budgetId], $companyRows[$companyId][$budgetId]['utilization']);
                    $total += $companyRows[$companyId][$budgetId]['utilization'];
                }else{
                    array_push($tempArray[$budgetId], '');
                }
            }
            array_push($tempArray[$budgetId], $total);
            $total=0.0;
            foreach($companyRows as $companyId=>$data){
                if(isset($companyRows[$companyId][$budgetId])){
                    array_push($tempArray[$budgetId], $companyRows[$companyId][$budgetId]['remaining']);
                    $total += $companyRows[$companyId][$budgetId]['remaining'];
                }else{
                    array_push($tempArray[$budgetId], '');
                }
            }
            array_push($tempArray[$budgetId], $total);
        }
        return $tempArray;
    }

    function get_headers(){
        $headers= array();
        foreach($this->get_associative_headers() as $header){
            $headers = array_merge($headers, $header);
        }
        return $headers;
    }
    function get_associative_headers(){
        $headers['']= array('SL', 'Budget Head', 'Budget Purpose');
        foreach($this->comparison_headers() as $head) {
            $headers[$head]= array();
            foreach ($this->companyList as $company) {
                array_push($headers[$head], $company);
            }
            array_push($headers[$head], 'Total');
        }
        return $headers;
    }
    function comparison_headers(){
        return array(
            'Budgeted Amount',
            'Utilization',
            'Remaining Budget',
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

    public function get_filters(){
        return array(
            'Year'=> array('select', $this->year_generator(2010, 20))
        );
    }
    function year_generator($start, $noOfYear){
        $year= array();
        for($i=0; $i<=$noOfYear; $i++){$year[$start + $i]=$start+$i;}
        return $year;
    }
}
?>