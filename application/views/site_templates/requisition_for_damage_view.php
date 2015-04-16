<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/15/15
 * Time: 12:23 PM
 */
?>
<style type="text/css">
    #items_input_box ul{list-style: none;margin: 0;}
    #items_input_box ul li ul li{display: inline-block; width: 220px;}
    #items_input_box ul li ul{border-bottom: 1px #a9dba9 solid;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
</style>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>