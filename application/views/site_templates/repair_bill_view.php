<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 2:53 PM
 */
?>
    <h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script type="text/javascript">
    $(document).ready(function(e) {
        var companyId= '<?php echo $companyId; ?>';
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        if($("#field-billingDate").val()=='')$("#field-billingDate").datepicker('setDate', new Date());
        if($("#field-billReceiveDate").val()=='')$("#field-billReceiveDate").datepicker('setDate', new Date());
        $('form#crudForm div.buttons-box').before('<input type="hidden" name="preBillAmount" value="'+$('#field-billAmount').val()+'"/>');
        $(".form-content form div.buttons-box").prepend(
            '<div class="form-button-box">'
            + '<input type="button" value="Back to Bill List" class="ui-input-button" id="cancel-bill">'
            + '</div>'
        );
        $("input#cancel-bill").click(function(){
            window.location = "<?php echo $cancelURL;?>";
        });

        var budgetHeadURL= '<?php echo base_url(IT_MODULE_FOLDER.'repair_bill/ajax_get_budget_head');?>/'+companyId+'/';
        filterOptions('budgetType', 'budgetId', budgetHeadURL+$('#field-budgetYear').val()+'/');
        $('#field-budgetType').change(function(){
            filterOptions('budgetType', 'budgetId', budgetHeadURL+$('#field-budgetYear').val()+'/');
        });
        $('#field-budgetYear').change(function(){
            filterOptions('budgetType', 'budgetId', budgetHeadURL+$(this).val()+'/');
        });
    });

    function filterOptions(source, target, url){
        var ID= $('#field-' + source).val();
        var $el = $('#field-' + target);
        var preValue= $el.val();
        if(ID==null || ID=='')ID='';
        $('#field'+target).append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+target+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#'+target+'_ajax_loader').show();
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
                    $el.val(preValue);
                }
            },
            'json'
        )
            .fail(function() {
                //alert( "error" );
            })
            .always(function() {
                $('#'+target+'_ajax_loader').hide();
                $el.chosen().trigger('liszt:updated');
            });
    }
</script>