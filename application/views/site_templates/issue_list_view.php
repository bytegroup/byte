<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/23/15
 * Time: 3:10 PM
 */
?>

<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li:last-child{width: 250px;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 150px;text-align: left;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
</style>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>