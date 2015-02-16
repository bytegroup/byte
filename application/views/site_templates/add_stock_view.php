<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/16/15
 * Time: 3:27 PM
 */
?>
<style type="text/css">
    input[type="checkbox"]{margin: 0;}
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
        <input type="button" value="Back to Receive" onclick="window.location='<?php echo $backToReceive;?>'" class="ui-input-button" id="">
    </div>
</div>
<h3><?=$pageTitle?></h3>
<?=$output?>


<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        var state= '<?php echo $state;?>';
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        $('form#crudForm div.buttons-box div.form-button-box:first-child').after(
            '<div class="form-button-box">' +
            ' <input type="button" value="Cancel" class="ui-input-button" id="cancel-add-to-stock"/>' +
            '</div>');
        $("input#cancel-add-to-stock").click(function(){
            if(confirm(
                'The items for this receive may not be added to stock.\n'
                + 'Are you sure you want to go back?'
            ))window.location='<?php echo $backToReceive;?>';
        });
        //$('form#crudForm #save-and-go-back-button').val('Add To Stock');
    });
</script>