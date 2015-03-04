<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 2:18 PM
 */
?>
    <h3><?php echo $pageTitle;?></h3>
<?php echo $output;?>

<script language="JavaScript">
    $(document).ready(function(e) {
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        if($("#field-billingDate").val()=='')$("#field-billingDate").datepicker('setDate', new Date());
        if($("#field-billReceiveDate").val()=='')$("#field-billReceiveDate").datepicker('setDate', new Date());
        $('#field-billType').val($("#field-serviceAgreementId option:selected").text());
        $('form#crudForm div.buttons-box').before('<input type="hidden" name="preBillAmount" value="'+$('#field-billAmount').val()+'"/>');

        var budgetHeadURL= '<?php echo base_url(IT_MODULE_FOLDER.'/others_bill/ajax_get_budget_head');?>/';
        $('#field-companyId').change(function(){
            filterOptions('budgetType', 'budgetId', budgetHeadURL+$(this).val()+'/');
        });
        $('#field-budgetType').change(function(){
            filterOptions('budgetType', 'budgetId', budgetHeadURL+$("#field-companyId").val()+'/');
        });
        $('#field-serviceAgreementId').change(function(){
            populate_data($(this).val());
            $('#field-billType').val($("option:selected", this).text());
        });
    });

    function filterOptions(source, target, url){
        var ID= $('#field-' + source).val();
        var $el = $('#field-' + target);
        var preValue= $el.val();
        if(ID==null || ID=='')ID='';
        $('#field'+source).append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+source+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#'+source+'_ajax_loader').show();
        $el.empty();
        $.post(
            url + ID,
            {},
            function(data){
                $el.append('<option value=""></option>');
                if(data != null){
                    $.each(data, function(key, val) {
                        $el.append($('<option></option>')
                            .attr('value', val.value).text(val.property));
                    });
                    //$el.val(preValue);
                }
            },
            'json'
        )
            .fail(function() {
                //alert( "error" );
            })
            .always(function() {
                $('#'+source+'_ajax_loader').hide();
                $el.chosen().trigger('liszt:updated');
            });
    }

    function populate_data(serviceAgreementId){
        serviceAgreementId= serviceAgreementId==='' ? 0: serviceAgreementId;
        $.post(
            '<?php echo base_url(IT_MODULE_FOLDER.'others_bill/ajax_get_agreement_details');?>/'+serviceAgreementId,
            {},
            function(data){
                if(data != null){
                    $('#field-billAmount').val(data.amount);
                    $('#field-vendorsId').val(data.vendor);
                }
            },
            'json'
        )
            .fail(function() {
                //alert( "error" );
            })
            .always(function() {
                $('#field-vendorsId').chosen().trigger('liszt:updated');
            });
    }
</script>