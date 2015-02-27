<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/27/15
 * Time: 7:11 PM
 */
?>
<?php
require_once APPPATH."third_party/PHPExcel/PHPExcel.php";

class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>