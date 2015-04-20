<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/17/15
 * Time: 12:29 PM
 */
?>
<style type="text/css">
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul li{display: inline-block; width: 250px;text-align: left;}
    #items_input_box ul li ul li:nth-child(3){width: 100px; text-align: center;}
    #items_input_box ul li ul li:last-child{width: 100px;}
    #items_input_box ul li:last-child{font-weight: bold; text-align: right; color: #356635;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #items_input_box ul ul li input[type="number"]{width: 73px; margin-top: 5px;}
    #items_input_box ul li ul.items-table-header{text-align: left; background-color: #9acc9a;}
    #items_input_box ul li span{border-bottom: 3px #356635 double; }
    td span{display: inline-block!important;}
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Requisition List" onclick="window.location='<?php echo $backToRequisitionList?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<h6><?php echo "Requisition: " ?><i style="color: #005580;"><?php echo $quotationFor; ?></i></h6>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        var state= '<?php echo $state;?>';
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $('#save-and-go-back-button').hide();

        var numberbox= $('#items_input_box input[type="number"]');
        $(numberbox).numeric();

        $('span#items-grand-total').html(total_price());
        numberbox.on('input', function(){
            $('span#items-grand-total').html(total_price());
        });

        function total_price(){
            var total= 0.0;
            $(numberbox).each(function(){
                total+= parseFloat($(this).val()? $(this).val():0);
            });
            return total;
        }
    });

</script>
