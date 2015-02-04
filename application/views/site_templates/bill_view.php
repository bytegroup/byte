<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/29/14
 * Time: 12:47 PM
 */
?>

<h3><?=$pageTitle?></h3>
<?=$output?>


<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        var companyId= '<?php echo $companyId; ?>';
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $(".form-content form div.buttons-box").prepend(
            '<div class="form-button-box">'
            + '<input type="button" value="Back to Receive" class="ui-input-button" id="cancel-bill">'
            + '</div>'
        );
        $("input#cancel-bill").click(function(){
            window.location = "<?php echo $cancelURL;?>";
        });

        if($("#field-billingDate").val()=='')$("#field-billingDate").datepicker('setDate', new Date());
        if($("#field-billReceiveDate").val()=='')$("#field-billReceiveDate").datepicker('setDate', new Date());

        var budgetHeadURL= '<?php echo base_url(IT_MODULE_FOLDER);?>/bill/ajax_get_budget_head/'+companyId+'/';
        filterOptions('budgetType', 'budgetId', budgetHeadURL);
        $('#field-budgetType').change(function(){
            filterOptions('budgetType', 'budgetId', budgetHeadURL);
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
                    $el.val(preValue);
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
</script>