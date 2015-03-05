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

    public function get_repair_types($damageDetailId=0, $repairId=0){
        if(!$damageDetailId)return array('');
        $this->db->select('rt.repairTypeId, rt.serviceType');
        $this->db->from(TBL_REPAIR_TYPE.' as rt ');
        $this->db->where(
            'rt.categoryId IN ('
            .'SELECT i.categoryId FROM '.TBL_DAMAGE_DETAIL.' as dd '
            .'INNER JOIN '.TBL_STOCK_DETAIL.' as sd ON sd.stockDetailId=dd.stockDetailId '
            .'INNER JOIN '.TBL_STOCK.' as s ON s.stockId=sd.stockId '
            .'INNER JOIN '.TBL_ITEMS_MASTER.' as i ON i.itemMasterId=s.itemMasterId '
            .')'
        );
        if(!$repairId)$this->db->where("rt.serviceEndDate >= '".mdate("%y-%m-%d", time())."'");
        else $this->db->where("rt.serviceEndDate >= (SELECT repairDate FROM ".TBL_REPAIR." where repairId=".$repairId.")");

        $db= $this->db->get();
        if(!$db->num_rows())return array('');
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
        $this->db->select('r.repairId, r.repairAmount, r.repairDate, r.billId, rt.serviceType, v.vendorsName');
        $this->db->from(TBL_REPAIR.' as r ');
        $this->db->join(TBL_REPAIR_TYPE.' as rt ', 'rt.repairTypeId=r.repairTypeId');
        $this->db->join(TBL_VENDORS.' as v ', 'v.vendorsId=r.vendorsId');
        if($damageDetailId)$this->db->where('r.damageDetailId', $damageDetailId);
        $db=$this->db->get(TBL_REPAIR);
        if(!$db->num_rows())return array();
        $array= array();
        foreach($db->result() as $row){
            $array[$row->repairId]=array(
                'billId'        => $row->billId,
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