<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/30/14
 * Time: 8:42 PM
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
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Quotation List" onclick="window.location='<?php echo $backToQuotationList?>'" class="ui-input-button" id="">
    </div>
</div>
<h3><?=$pageTitle?></h3>
<?=$output?>


<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        var state= '<?php echo $state;?>';
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $('#items_input_box input[type="text"]').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('#items_input_box input[type="number"]').numeric();
        $('form#crudForm #form-button-save').hide();
        $('form#crudForm #save-and-go-back-button').val(state==='edit'?'Update changes':'Save');
        $('li.recQuantity input').each(function(){
            var row= $(this).attr('id').split('-')[1];
            var recQ= parseInt($(this).val());
            remQuantity(row, isNaN(recQ)? 0: recQ);
        });

    });

    function remQuantity(id, recValue){
        $(
            'ul#rowNum-'+id +' li input#item-'+id+'-recQuantity'
        ).on('input',function() {
                var ordQ = parseInt($('ul#rowNum-'+id +' li.ordQuantity').text());
                var recQ = parseInt($(this).val());
                var alreadyRec= parseInt($('ul#rowNum-'+id +' li span.already-received').text());
                recQ = isNaN(recQ) ? 0: recQ;
                if(recQ < 0 || (recQ+alreadyRec) > ordQ){recQ= recValue; $(this).val(recValue);}
                recQ += alreadyRec;
                $('ul#rowNum-'+id +' li.remQuantity').html((ordQ - recQ) < 0 ? 0 : (ordQ - recQ));
            });
    }
</script>