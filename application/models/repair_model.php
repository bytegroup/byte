<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author USER
 */
Class Repair_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_repair_types(){
        $this->db->select('repairTypeId, serviceType');
        $this->db->from(TBL_REPAIR_TYPE);
        $this->db->where("serviceEndDate > '".mdate("%y-%m-%d", time())."'");
        $db= $this->db->get();
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->repairTypeId]=$row->serviceType;
        }
        return $array;
    }
    public function get_repair_amount($repairTypeId){
        if (!$repairTypeId) return 0;
        $this->db->select("serviceRate")
            ->from(TBL_REPAIR_TYPE)
            ->where('repairTypeId', $repairTypeId);
        $db = $this->db->get();
        if(!$db->num_rows())return 0;
        return $db->result()[0]->serviceRate;
    }
    public function get_repair_list($damageDetailId=0){
        $this->db->select('r.repairId, r.repairAmount, r.repairDate, rt.serviceType, v.vendorsName');
        $this->db->from(TBL_REPAIR.' as r ');
        $this->db->join(TBL_REPAIR_TYPE.' as rt ', 'rt.repairTypeId=r.repairTypeId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=r.vendorsId');
        if($damageDetailId)$this->db->where('r.damageDetailId', $damageDetailId);
        $db=$this->db->get(TBL_REPAIR);
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->repairId]=array(
                'repairType'    => $row->serviceType,
                'amount'        => $row->repairAmount,
                'vendor'        => $row->vendorsName,
                'date'          => $row->repairDate
            );
        }
        return $array;
    }
}

?>