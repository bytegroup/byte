<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/29/14
 * Time: 12:09 PM
 */
?>
<style type="text/css">
    #companies_input_box ul{width: 100%;list-style: none; margin: 0;}
    #companies_input_box ul li ul.company-table-header{background-color: #a9dba9;}
    #companies_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 200px; text-align: center;}
    #companies_input_box ul li ul li:first-child{width: 250px;text-align: left;}
    #companies_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #companies_input_box ul li:last-child ul{border: none;font-weight: bold;}
    #companies_input_box ul ul li input{width: 150px; margin-top: 8px;}
    #companies_input_box ul li ul li span{display: inline-block!important; border-bottom: 4px #9acc9a double;}
</style>
<h3><?=$pageTitle?></h3>
<?=$output?>

<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        var state= '<?php echo $state;?>';
        var amountField= $('#companies_input_box input[name="amount[]"]');
        var totalAmountField= $('#companies_input_box ul li span#total-amount');
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        $('form#crudForm #form-button-save').hide();
        if(state==='edit')$('form#crudForm #save-and-go-back-button').val('Update Changes');
        else $('form#crudForm #save-and-go-back-button').val('Save Budget');

        $('#companies_input_box input[type="number"]').numeric();
        totalAmountField.html(totalAmount());
        amountField.on('input', function(){
            totalAmountField.html(totalAmount());
        });
        
        function totalAmount(){
            var total= 0.0;
            amountField.each(function(){
                total += $(this).val() ? parseFloat($(this).val()) : 0 ;
            });
            return total;
        }


    });
</script>