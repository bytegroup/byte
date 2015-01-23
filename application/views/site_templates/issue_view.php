<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/31/14
 * Time: 3:54 PM
 */
?>
<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li:last-child{width: 250px;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 150px;text-align: left;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Stock List" onclick="window.location='<?php echo $backToStockList?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<h6><?php echo "Stock No.: " ?><i style="color: #005580;"><?php echo $issueHeader->stockNumber; ?></i></h6>
<h6><?php echo "Item Name: " ?><i style="color: #005580;"><?php echo $issueHeader->itemName; ?></i></h6>
<h6><?php echo "Category : " ?><i style="color: #005580;"><?php echo $issueHeader->categoryName; ?></i></h6>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        var state= '<?php echo $state;?>';
        var stockId=<?php echo $stockId ?>;
        var stockItems= '<?php echo $issuedItems; ?>';
        var remStockItems= '<?php echo $toBeIssuedItems;?>';

        $("form#crudForm #form-button-save").hide();
        if(state==='add')$("form#crudForm #save-and-go-back-button").val('Save');
        else $("form#crudForm #save-and-go-back-button").val('Update Changes');

        stockItems= $.parseJSON(stockItems);
        remStockItems= $.parseJSON(remStockItems);
        var stockQty=0;
        stockQty= parseInt($("#stockQuantity_input_box").text());

        /*$('#field-issueQuantity').spinner({incremental: true, min: 0, max:stockQty});
        var incremental = $( "#field-issueQuantity" ).spinner( "option", "incremental" );
        $("#field-issueQuantity").spinner( "option", "incremental", true );*/

        var issueQty= $("#field-issueQuantity").val()?parseInt($("#field-issueQuantity").val()):0;
        if(state==='read')issueQty= $("#field-issueQuantity").text()?parseInt($("#field-issueQuantity").text()):0;
        $("#items_input_box").html(items(stockItems, issueQty));
        issueQty = $('#field-issueQuantity').val()?parseInt($('#field-issueQuantity').val()):0;
        $("#field-issueQuantity").on('input', function() {
            if(state==='add'){
                issueQty = parseInt($(this).val());
                if (issueQty > stockQty)$(this).val('');
                if(issueQty ==0)$(this).val('');
                $("#items_input_box").html(items(stockItems, $(this).val() ? parseInt($(this).val()) : 0));
            }
            else{
                if ((stockQty + issueQty) < parseInt($(this).val()))$(this).val(issueQty);
                if(issueQty < $(this).val())
                    $("#items_input_box").html(items($.merge($.merge([],stockItems),remStockItems), $(this).val() ? parseInt($(this).val()) : 0));
                else
                    $("#items_input_box").html(items(stockItems, $(this).val() ? parseInt($(this).val()) : 0));

            }
        });

        var departmentURL= '<?php echo base_url(IT_MODULE_FOLDER);?>/issue/ajax_get_department/'+stockId+'/';
        var employeeURL  = '<?php echo base_url(IT_MODULE_FOLDER);?>/issue/ajax_get_employee/'+stockId+'/';
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

    function items(items, numOfItems){
        var $html= '';
        $html += '<ul>';
        $html += '<li>';
        $html += '<ul class="items-table-header">';
        $html += '<li>Product Code</li><li>Warranty</li><li>Vendor</li>';
        $html += '</ul>';
        $html += '</li>';
        if(items.length>0)
        for(var i=0; i<numOfItems; i++){
            $html += '<li>';
            $html += '<ul>';
            $html += '<li>'+items[i].productCode +'</li>';
            $html += '<li>'+items[i].warranty +'</li>';
            $html += '<li>'+items[i].vendor +'</li>';
            $html += '<input type="hidden" name="issuedIds[]" value="'+items[i].issuedId+'"/>';
            $html += '</ul>';
            $html += '</li>';
        }

        $html += '</ul>';

        return $html;
    }

</script>