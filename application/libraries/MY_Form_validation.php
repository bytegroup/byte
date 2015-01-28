<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    private $_standard_date_format = 'Y-m-d H:i:s';
    private $mime_types;
    public function __construct()
    {
        parent::__construct();
    }
    /*public function is_unique_in_group($table, $groupByField, $groupByFieldValue, $uniqueField, $uniqueFieldValue)
    {
        $query = $this->CI->db
            ->limit(1)
            ->where($table, array($uniqueField => $uniqueFieldValue))
            ->group_by($groupByField, $groupByFieldValue);

        return $query->num_rows() === 0;
    }*/

    /*function is_unique_in_group($str=null, $field=null)
    {
        $this->set_message('is_unique_in_group', 'The field should be 1234');
        return FALSE;
    }*/
}
// END MY Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
