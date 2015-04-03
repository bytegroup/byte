<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/13/15
 * Time: 4:01 PM
 */
?>
<style type="text/css">
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 100px; text-align: center;}
    #items_input_box ul li ul li.item{width: 170px; text-align: left;}
    #items_input_box ul li ul li.category{width: 150px; text-align: left;}
    #items_input_box ul li ul li.recQuantity{width: 140px;}
    #items_input_box ul li ul li.ordQuantity{width: 70px;}
    #items_input_box ul li ul li.remQuantity{width: 70px;}
    #items_input_box ul li ul li.warranty{width: 90px;}
    #items_input_box ul li ul li.warranty input{width: 75px;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #items_input_box ul li ul.items-table-header{text-align: center;}
    #items_input_box ul ul li input{width: 70px; margin-top: 8px;}
    #items_input_box ul li span{display: inline-block!important; width: 50px;}

    #stock_input_box ul{width: 100%;list-style: none; margin: 0;}
    #stock_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #stock_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 100px; text-align: center;}
    #stock_input_box ul li ul li.item{width: 300px; text-align: left;}
    #stock_input_box ul li ul li.code{width: 250px;}
    #stock_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
</style>
<h3><?=$pageTitle?></h3>
<?=$output?>

<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>