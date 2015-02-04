<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/4/15
 * Time: 7:35 PM
 */
?>

<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/3/15
 * Time: 3:26 PM
 */
?>
<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li:first-child{width: 20px;}
    #items_input_box ul li ul li:last-child{width: 250px;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 150px;text-align: left;}
    #items_input_box ul li li input[type="checkbox"]{margin-bottom: 3px;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Stock List" onclick="window.location='<?php echo $backToStockList; ?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<h6><?php echo "Stock No.: " ?><i style="color: #005580;"><?php echo $stockInfo['stockNumber']; ?></i></h6>
<h6><?php echo "Item: " ?><i style="color: #005580;"><?php echo $stockInfo['item']; ?></i></h6>
<h6><?php echo "Category: " ?><i style="color: #005580;"><?php echo $stockInfo['category']; ?></i></h6>
<h6><?php echo "Stock Quantity: " ?><i style="color: #005580;"><?php echo $stockInfo['qty']; ?></i></h6>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $("#field-damageQuantity").prop("readonly",true);
        $("#items_input_box input[type='checkbox']").change(function(){
            var damageQty= $("#items_input_box input[type='checkbox']:checked").length;
            if(damageQty==0)$('#field-damageQuantity').val('');
            else $('#field-damageQuantity').val(damageQty);
        });
    });
</script>