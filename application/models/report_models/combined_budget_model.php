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
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        return $db=$this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('b.*');
        $this->db->from(TBL_BUDGET.' as b ');
        return $db=$this->db->get();
    }

    function get_data($filters= array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            $array[$row->budgetId]= array(
                ++$i,
                
            );
        endforeach;
        return $array;
    }

    function get_headers(){
        $headers= array();
        foreach($this->get_associative_headers() as $header){
            $headers = array_merge($headers, $header);
        }
        return $headers;
    }
    function get_associative_headers(){
        $companies= $this->get_company_list();
        $headers= array('SL', 'Budget Head', 'Budget Purpose');
        foreach($this->common_headers() as $common) {
            foreach ($companies as $company) {
                $headers[$common] = $company;
            }
            $headers[] = 'Total';
        }
        return $headers;
    }
    function common_headers(){
        return array(
            '',
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