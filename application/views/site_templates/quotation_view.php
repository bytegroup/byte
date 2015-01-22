<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/19/14
 * Time: 5:51 PM
 */
?>
<style type="text/css">
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 100px; }
    #items_input_box ul li ul li:first-child{width: 200px;}
    #items_input_box ul li:last-child{font-weight: bold; text-align: right;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #items_input_box ul li ul.items-table-header{text-align: center;}
    #items_input_box ul ul li input[type="number"]{width: 90px; margin-top: 8px;}
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
    var state= '<?php echo $state;?>';
    var itemIds= [];
    $('#save-and-go-back-button').hide();
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        var checkbox=$('#items_input_box input[type="checkbox"]');
        var numberbox= $('#items_input_box input[type="number"]');
        $(numberbox).numeric();
        checkbox.each(function(){
            if(state==='add')$(this).attr("checked","checked");
            itemIds.push($(this).val());
        });
        for(var i=0; i<itemIds.length; i++){calculate_price(i, itemIds);}
        checkbox.change(function(){
            var currentId= $(this).val();
            if(!$(this).is(':checked')) {
                //$('#items_input_box ul#'+currentId+' input[type="number"]').hide();
                $('#totalprice-item-'+currentId).val('');
                $('#unitprice-item-'+currentId).val('');
                var grandTotal=0;
                for(var j=0; j<itemIds.length; j++){
                    grandTotal+= parseFloat($('#totalprice-item-'+itemIds[j]).val() ? $('#totalprice-item-'+itemIds[j]).val(): 0 );
                }
                $('span#items-grand-total').html((grandTotal).toFixed(2));
            }
            else $('#items_input_box ul#'+currentId+' input[type="number"]').show();
        });



        window.trigger_submit=false;
        var error= false;

        $('#form-button-save').click(function(){
            $('div#report-error').hide();
            $('div#report-success').hide();
            error= false;
            window.trigger_submit= false;
            checkbox.each(function(){
                if($(this).is(':checked')){
                    var val= $(this).val();
                    var unitElem=$('input#unitprice-item-'+val);
                    var unitprice= unitElem.val();
                    if(!unitprice || unitprice==0) {
                        error= true;
                        unitElem.focus();
                        $('div#report-error').show();
                        $('div#report-error').html('<p>Check unit price field</p>');
                        return false;
                    }
                }
            });
            if(!error){
                window.trigger_submit= true;
                //$('#form-button-save').trigger('click');
            }
        });

    });
    function calculate_price(index, itemIds){
        var ids= itemIds[index];
        $(
            '#unitprice-item-'+ids
            +', #totalprice-item-'+ids
        ).on('input',function() {
                var grandTotal=0.0;
                var qty = parseInt($('#quantity-item-'+ids).val());
                var price = parseFloat($('#unitprice-item-'+ids).val());
                var total = qty * price ? qty * price : 0;
                $('#totalprice-item-'+ids).val((total).toFixed(2));
                for(var j=0; j<itemIds.length; j++){
                    grandTotal+= parseFloat($('#totalprice-item-'+itemIds[j]).val() ? $('#totalprice-item-'+itemIds[j]).val(): 0 );
                }
                $('span#items-grand-total').html((grandTotal).toFixed(2));
            });
    }
</script>
