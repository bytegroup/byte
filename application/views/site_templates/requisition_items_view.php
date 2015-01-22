<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/17/14
 * Time: 8:16 PM
 */
?>

<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    input#field-orderedQuantity{width: 200px;}
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Requisition List" onclick="window.location='<?php echo $backToRequisitionList?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?=$pageTitle?></h3>

<?=$output?>

<script language="JavaScript">
    var state= '<?php echo $state;?>';
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        if(state=='add' || state=='edit'){
            $('#field-orderedQuantity').spinner({incremental: true, min: 0});
            var incremental = $( "#field-orderedQuantity" ).spinner( "option", "incremental" );
            $( "#field-orderedQuantity" ).spinner( "option", "incremental", true );
        }

        var itemMasterURL= '<?php echo base_url();?>it-inventory/requisition_items/ajax_get_itemMaster/';
        $('#field-categoryId').change(function(){
            filterOptions('categoryId', 'itemMasterId', itemMasterURL);
        });
    });

    function filterOptions(source, target, url){
        var ID= $('#field-' + source).val();
        var $el = $('#field-' + target);
        if(ID==null || ID=='')ID=0;
        $('#'+source+'_input_box').append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+source+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
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
