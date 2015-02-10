<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/10/15
 * Time: 12:16 PM
 */
?>
<?php
class Report_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    function get_data($table, $fields='*', $conditions=null){
        if(!$table && !$fields)return array();
        $columns= is_array($fields) ? implode(',', $fields):$fields;
        $array= array();
        $this->db->select($columns);
        $this->db->from($table);
        if($conditions)$this->db->where($conditions);
        $db=$this->db->get();
        if(!$db->num_rows()) return array();
        foreach($db->result() as $row):
            $array[]= $row;
        endforeach;
        return $array;
    }
}
?>