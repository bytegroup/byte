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
    <?php if($isCountable){?>
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li:first-child{width: 20px;}
    #items_input_box ul li ul li:last-child{width: 250px;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 150px;text-align: left;}
    #items_input_box ul li li input[type="checkbox"]{margin-bottom: 3px;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    <?php }else {?>
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li:first-child{width: 20px;}
    #items_input_box ul li ul li:last-child{width: 250px;text-align: left;}
    #items_input_box ul li ul li:nth-child(2){text-align: left;}
    #items_input_box ul li ul li:nth-child(3){width: 80px;}
    #items_input_box ul li ul li:nth-child(4){width: 80px;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 150px;text-align: center;}
    #items_input_box ul li li input[type="checkbox"]{margin-bottom: 3px;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #items_input_box ul li ul li input[type='number']{width: 70px;margin: 5px 0;}
    <?php } ?>
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Issue List" onclick="window.location='<?php echo $backToIssueList; ?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<h6><?php echo "Stock No.: " ?><i style="color: #005580;"><?php echo $stockInfo['stockNumber']; ?></i></h6>
<h6><?php echo "Item: " ?><i style="color: #005580;"><?php echo $stockInfo['item']; ?></i><small> (<?php echo $stockInfo['unit'];?>)</small></h6>
<h6><?php echo "Category: " ?><i style="color: #005580;"><?php echo $stockInfo['category']; ?></i></h6>
<h6><?php echo "Issued Quantity: " ?><i style="color: #005580;"><?php echo $stockInfo['qty']; ?></i></h6>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        var isCountable=<?php if($isCountable){ ?>true;<?php } else{ ?> false;<?php } ?>

        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $("#field-damageQuantity").prop("readonly",true);
        $("#items_input_box ul").append('<input type="hidden" name="preDamageQty" value="'+parseInt($("#field-damageQuantity").val())+'"/>');

        if(isCountable){
            $("#items_input_box input[type='checkbox']").change(function(){
                var damageQty= $("#items_input_box input[type='checkbox']:checked").length;
                if(damageQty==0)$('#field-damageQuantity').val('');
                else $('#field-damageQuantity').val(damageQty);
            });
        }else{
            $("#items_input_box input[type='number']").on('input', function(){
                var max= parseInt($(this).attr('max'));
                if(!$.isNumeric($(this).val()))$(this).val(0);
                var currentId= $(this).attr('id').split('-')[1];
                var remQty= parseInt($('#items_input_box li#remQty-'+currentId).text());
                var issueQty= parseInt($(this).val());
                if(max<issueQty){$(this).val(0); issueQty=0;}
                if(issueQty>0)$('#items-'+currentId).prop('checked', true);
                else $('#items-'+currentId).prop('checked', false);
                var total=0;
                $("#items_input_box input[type='number']").each(function(){
                    total += parseInt($(this).val()? $(this).val():0);
                });
                $("#field-damageQuantity").val(total);
            });

            $("#items_input_box input[type='number']").focus( function() {
                if ( $(this).val()=="0") {$(this).val('');}
            });
            $("#items_input_box input[type='number']").blur( function() {
                if ( $(this).val()=="") {$(this).val('0');}
            });

            $("#items_input_box input[type='checkbox']").change(function(){
                if(!$(this).is('checked')){
                    var currentId= $(this).attr('id').split('-')[1];
                    $('#items_input_box input#qty-'+currentId).val(0);
                }
            });
        }
    });
</script>