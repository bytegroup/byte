<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/17/15
 * Time: 7:05 PM
 */
?>
<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
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
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Stock List" onclick="window.location='<?php echo $backToStockList?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<h6><?php echo "Stock No.: " ?><i style="color: #005580;"><?php echo $issueHeader->stockNumber; ?></i></h6>
<h6><?php echo "Item Name: " ?><i style="color: #005580;"><?php echo $issueHeader->itemName; ?></i><small>(<?php echo $issueHeader->unitName;?>)</small></h6>
<h6><?php echo "Category : " ?><i style="color: #005580;"><?php echo $issueHeader->categoryName; ?></i></h6>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        var state= '<?php echo $state;?>';
        var stockId=<?php echo $stockId ?>;

        $("form#crudForm #form-button-save").hide();
        if(state==='add')$("form#crudForm #save-and-go-back-button").val('Save');
        else $("form#crudForm #save-and-go-back-button").val('Update Changes');

        $("#field-issueQuantity").prop("readonly",true);
        $("#items_input_box ul").append('<input type="hidden" name="preIssueQty" value="'+$("#field-issueQuantity").val()+'"/>');

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
            $("#field-issueQuantity").val(total);
        });

        $("#items_input_box input[type='checkbox']").change(function(){
            if(!$(this).is('checked')){
                var currentId= $(this).attr('id').split('-')[1];
                $('#items_input_box input#qty-'+currentId).val(0);
            }
        });

        var departmentURL= '<?php echo base_url(IT_MODULE_FOLDER);?>/issue_uncountable/ajax_get_department/'+stockId+'/';
        var employeeURL  = '<?php echo base_url(IT_MODULE_FOLDER);?>/issue_uncountable/ajax_get_employee/'+stockId+'/';
        var currentDpt= $('#field-departmentId').val();
        var currentEmp= $('#field-issueUserId').val();
        if(state==='add' || state==='edit'){
            filterOptions('', 'departmentId', departmentURL, currentDpt);
            filterOptions('', 'issueUserId', employeeURL, currentEmp);
        }

        if(state==='read') toggleField($("#field-issueTo").text());
        else toggleField($("#issueTo_input_box input[type='radio']:checked").val());

        $("#issueTo_input_box input[type='radio']").change(function(){
            toggleField($(this).val());
        });
        $("#field-departmentId").change(function(){
            filterOptions('departmentId', 'issueUserId', employeeURL, 0);
        });
    });

    /*************************************************************************/
    function toggleField(source){
        if (source === 'Department') {
            $("#issueUserId_field_box").hide();
            $("#departmentId_field_box").show();
        } else if (source === 'Employee') {
            $("#departmentId_field_box").show();
            $("#issueUserId_field_box").show();
        } else {
            $("#departmentId_field_box").hide();
            $("#issueUserId_field_box").hide();
        }
    }
    function filterOptions(source, target, url, currentValue){
        var ID= source!==''?$('#field-' + source).val():0;
        var $el = $('#field-' + target);
        if(ID==null || ID=='')ID=0;
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
                    if(currentValue!=0)$el.val(currentValue);
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

    /*function items(items, numOfItems){
        var $html= '';
        $html += '<ul>';
        $html += '<li>';
        $html += '<ul class="items-table-header">';
        $html += '<li>&nbsp;</li><li>Product Code</li><li>Rem. Quantity</li><li>Issue Quantity</li><li>Warranty</li><li>Vendor</li>';
        $html += '</ul>';
        $html += '</li>';
        numOfItems= items.length;
        if(numOfItems>0)
            for(var i=0; i<numOfItems; i++){
                $html += '<li>';
                $html += '<ul>';
                $html += '<li><input type="checkbox" id="items-'+items[i].issuedId+'" name="selectedItems[]" value="'+items[i].issuedId+'"/></li>';
                $html += '<li>'+items[i].productCode +'</li>';
                $html += '<li id="remQty-'+items[i].issuedId+'">'+items[i].remQty+'</li>';
                $html += '<li><input type="number" id="qty-'+items[i].issuedId+'" name="qty[]" min="0" max="'+items[i].remQty+'" value="'+items[i].issueQty+'"/></li>';
                $html += '<li>'+items[i].warranty +'</li>';
                $html += '<li>'+items[i].vendor +'</li>';
                $html += '<input type="hidden" name="issuedIds[]" value="'+items[i].issuedId+'"/>';
                $html += '</ul>';
                $html += '</li>';
            }

        $html += '</ul>';

        return $html;
    }*/

</script>