<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/18/15
 * Time: 1:08 PM
 */
?>
<?php
class Bill_Report_Model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    private function _data_with_filter($filters){
        $fromDate= !$filters['from_date'] ? '': mdate('%y-%m-%d', strtotime($filters['from_date']));
        $toDate  = !$filters['to_date'] ? mdate('%y-%m-%d', time()): mdate('%y-%m-%d', strtotime($filters['to_date']));

        if($filters['vendor'])
            $this->db->select('b.billId, b.billNumber, b.billReceiveDate, b.billingDate, b.budgetType, bgt.budgetHead, b.billParticulars,
         (select group_concat(repV.vendorsName) from '.TBL_REPAIR.' as rep join '.TBL_VENDORS.' as repV ON repV.vendorsId=rep.vendorsId where rep.billId=b.billId and repV.vendorsId = '.$filters['vendor'].') AS repairVendor,
         v.vendorsName, b.billType, b.billAmount, c.companyName, b.billPaymentType, u.firstName, u.middleName, u.lastName');
        else
            $this->db->select('b.billId, b.billNumber, b.billReceiveDate, b.billingDate, b.budgetType, bgt.budgetHead, b.billParticulars,
         (select group_concat(repV.vendorsName) from '.TBL_REPAIR.' as rep join '.TBL_VENDORS.' as repV ON repV.vendorsId=rep.vendorsId where rep.billId=b.billId) AS repairVendor,
         v.vendorsName, b.billType, b.billAmount, c.companyName, b.billPaymentType, u.firstName, u.middleName, u.lastName');

        $this->db->from(TBL_BILL.' as b');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=b.companyId');
        $this->db->join(TBL_BUDGET.' as bgt ', 'bgt.budgetId=b.budgetId');
        $this->db->join(
            TBL_VENDORS.' as v ',
            'v.vendorsId=b.vendorsId OR v.vendorsId= (SELECT ven.vendorsId FROM '.TBL_RECEIVES.' as r JOIN '.TBL_QUOTATIONS.' as q ON q.quotationId=r.quotationId JOIN '.TBL_VENDORS.' as ven ON ven.vendorsId=q.vendorsId WHERE r.receiveId=b.receiveId)',
            'left'
        );
        $this->db->join(TBL_USERS.' as u ', 'u.userId=b.billCheckedById', 'left');

        if(!$fromDate)$this->db->where('b.billReceiveDate <= ', $toDate);
        else $this->db->where('b.billReceiveDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" ');

        if($filters['company'])$this->db->where('b.companyId', $filters['company']);
        if($filters['budget_head']) $this->db->where('bgt.budgetHead', $filters['budget_head']);
        if($filters['budget_type']) $this->db->where('b.budgetType', $filters['budget_type']);
        if($filters['vendor'])$this->db->where('v.vendorsId = '.$filters['vendor'].' OR (b.billType = "Product Repair Bill")');

        return $this->db->get();
    }
    private function _data_without_filter(){
        $this->db->select('b.billId, b.billNumber, b.billReceiveDate, b.billingDate, b.budgetType, bgt.budgetHead, b.billParticulars,
         (select group_concat(repV.vendorsName) from '.TBL_REPAIR.' as rep join '.TBL_VENDORS.' as repV ON repV.vendorsId=rep.vendorsId where rep.billId=b.billId) AS repairVendor,
         v.vendorsName, b.billType, b.billAmount, c.companyName, b.billPaymentType, u.firstName, u.middleName, u.lastName');
        $this->db->from(TBL_BILL.' as b');
        $this->db->join(TBL_COMPANIES.' as c ', 'c.companyId=b.companyId');
        $this->db->join(TBL_BUDGET.' as bgt ', 'bgt.budgetId=b.budgetId');
        $this->db->join(
            TBL_VENDORS.' as v ',
            'v.vendorsId=b.vendorsId OR v.vendorsId= (SELECT ven.vendorsId FROM '.TBL_RECEIVES.' as r JOIN '.TBL_QUOTATIONS.' as q ON q.quotationId=r.quotationId JOIN '.TBL_VENDORS.' as ven ON ven.vendorsId=q.vendorsId WHERE r.receiveId=b.receiveId)',
            'left'
        );
        $this->db->join(TBL_USERS.' as u ', 'u.userId=b.billCheckedById', 'left');
        return $this->db->get();
    }

    function get_data($filters= array()){
        $db = !count($filters) ? $this->_data_without_filter():$this->_data_with_filter($filters);
        if(!$db->num_rows()) return array();
        $array= array(); $i=0;
        foreach($db->result() as $row):
            if($row->billType==='Product Repair Bill') $vendor =$row->repairVendor;
            else $vendor = $row->vendorsName;
            if($vendor)
                $array[$row->billId]= array(
                    ++$i,
                    $row->billNumber,
                    $row->billReceiveDate,
                    $row->billingDate,
                    $row->budgetType,
                    $row->budgetHead,
                    $row->billParticulars,
                    $vendor,
                    $row->billType,
                    $row->billAmount,
                    $row->companyName,
                    $row->billPaymentType,
                    $row->firstName.' '.$row->middleName.' '.$row->lastName,
                    '',
                    '',
                    '',
                    ''
                );
        endforeach;
        return $array;
    }

    function get_headers(){
        return array(
            'SL',
            'Bill No.',
            'Bill Receive Date',
            'Billing Date',
            'Budget Type',
            'Budget Head',
            'Particulars',
            'Vendor',
            'Bill Type',
            'Amount',
            'Company Name',
            'Payment Type',
            'Bill Checked By',
            'Bill Submit Date',
            'Bill Prepared Date',
            'Bill Payment Date',
            'Bill Payment By'
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
    function get_budget_head($companyId=0){
        $db= !$companyId ?
            $this->db->get(TBL_BUDGET)
            :
            $this->db->get_where(TBL_BUDGET, array('companyId'=>$companyId));
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->budgetId]= $row->budgetHead;
        }
        return $array;
    }

    function get_vendor_list(){
        $db= $this->db->get(TBL_VENDORS);
        if(!$db->num_rows())return array();
        $array=array();
        foreach($db->result() as $row){
            $array[$row->vendorsId]= $row->vendorsName;
        }
        return $array;
    }

    public function get_filters(){
        return array(
            'From Date'     => 'date',
            'To Date'       => 'date',
            'Company'       => array('select', $this->get_company_list()),
            'Budget Type'   => array('select', array('Capital'=> 'CAPITAL', 'Revenue'=> 'REVENUE')),
            'Budget Head'   => array('select', $this->get_budget_head()),
            'Vendor'        => array('select', $this->get_vendor_list()),
            'Bill No.'      => 'text'
        );
    }
}
?>