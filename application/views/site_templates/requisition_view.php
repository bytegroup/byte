<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/17/14
 * Time: 12:12 PM
 */
?>

<!--suppress InfiniteLoopJS -->
<style type="text/css">
    input[type='radio']{margin-left: 20px;}
    input[type='radio']:first-child{margin-left: 0;}
    #requisitionItems_input_box ul{width: 100%;list-style: none; margin: 0;}
    #requisitionItems_input_box ul li ul.items-table-header li{width: 210px}
    #requisitionItems_input_box ul li ul.items-table-header li:nth-child(3){width: 100px;}
    #requisitionItems_input_box ul li ul.items-table-header li:last-child{width: 50px;}
    #requisitionItems_input_box ul li ul li:last-child a{margin-top: -20px;}
    #requisitionItems_input_box ul li ul li{display: inline-block;padding: 0 4px;width: auto;}
    #requisitionItems_input_box ul li ul li div li{clear:both; display: block;}
    #requisitionItems_input_box ul li ul li div a{height: 25px; margin-top: 10px;}
    #requisitionItems_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #requisitionItems_input_box ul li ul.items-table-header{text-align: center;}
    #requisitionItems_input_box ul ul li input[type="number"]{width: 90px; margin-top: -10px;}
    #requisitionItems_input_box a.add_more_button{margin-top: 5px; color: #3b3b1f;}
</style>

<h3><?=$pageTitle?></h3>
<?=$output?>



<script language="JavaScript">
    var state= '<?php echo $state; ?>';
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $('#save-and-go-back-button').hide();

        if(state=='add' || state=='edit') {
            $('#companyId_field_box').hide();
            $('#departmentId_field_box').hide();
            $('#userId_field_box').hide();
            var sourceElem = $('input[name="requisitionFor"]');
            var checked = sourceElem.filter(':checked').val();
            targetField(checked);
            sourceElem.change(function () {
                var checked = $(this).filter(':checked').val();
                targetField(checked)
            });

            var departmentURL = '<?php echo base_url(IT_MODULE_FOLDER);?>/requisition/ajax_get_department/';
            var staffURL = '<?php echo base_url(IT_MODULE_FOLDER);?>/requisition/ajax_get_staff/';
            $('#field-companyId').change(function () {
                filterOptions('companyId', 'departmentId', departmentURL);
                filterOptions('departmentId', 'userId', staffURL);
            });
            $('#field-departmentId').change(function () {
                filterOptions('departmentId', 'userId', staffURL);
            });

            /*script for items part*/
            var categoryURL = '<?php echo base_url(IT_MODULE_FOLDER.'requisition/');?>/ajax_get_category/';
            var itemsURL = '<?php echo base_url(IT_MODULE_FOLDER.'requisition/');?>/ajax_get_items/';
            var countItem = $("#requisitionItems_input_box input[name='numberOfItems']").val();

            $('#requisitionItems_input_box a.add_more_button').click(function () {
                countItem++;
                $('#requisitionItems_input_box ul.add_requisitionItems').append(itemRow(countItem));
                populateItemFields(countItem, 'cat', categoryURL);
                populateItemFields(countItem, 'item', itemsURL);
                $('#field-' + countItem + '-cat').change(function () {
                    var currentElemID = $(this).attr('id');
                    var currentRowID = currentElemID.split('-');
                    filterOptions(currentRowID[1] + '-cat', currentRowID[1] + '-item', itemsURL);
                });
                $("#requisitionItems_input_box input[name='numberOfItems']").val(countItem);
                $("li.itemRow-" + countItem + " a#" + countItem).click(function () {
                    deleteRow($(this));
                });
                $('#requisitionItems_input_box input[type="number"]').numeric();
            });

            if(state === 'edit'){
                var items= $.parseJSON('<?php echo $itemDetails;?>');
                var numberOfItems= items.length;
                if(numberOfItems > 0){
                    for(var i=1; i<=numberOfItems; i++){
                        $('#requisitionItems_input_box a.add_more_button').trigger('click');
                    }
                    $.each(items, function(id, value){
                        var rowNumber= id + 1;
                        $('[name="item-'+rowNumber+'-quantity"]').val(value.quantity);
                    });
                }
            }
            else {$('#requisitionItems_input_box a.add_more_button').trigger('click');}

            window.trigger_submit = false;
            var error = false;

            $('#form-button-save').click(function () {
                $('div#report-error').hide();
                $('div#report-success').hide();
                error = false;
                window.trigger_submit = false;

                $("#requisitionItems_input_box select").each(function() {
                    if ($(this).val() == "") {error= true;}
                });
                if(!error){
                    $("#requisitionItems_input_box input[type='number']").each(function() {
                        if ($(this).val() == "") { error= true; }
                    });
                }
                if (error) {
                    $('div#report-error').show();
                    $('div#report-error').html('<p>One or more item fields required</p>');
                }
                else {window.trigger_submit = true;}
            });
        }
    });

    function targetField(source){
        switch (source){
            case 'Company':
                $('#companyId_field_box').show();
                $('#departmentId_field_box').hide();
                $('#userId_field_box').hide();
                break;
            case 'Department':
                $('#companyId_field_box').show();
                $('#departmentId_field_box').show();
                $('#userId_field_box').hide();
                break;
            case 'Staff':
                $('#companyId_field_box').show();
                $('#departmentId_field_box').show();
                $('#userId_field_box').show();
                break;
            default :
                $('#companyId_field_box').hide();
                $('#departmentId_field_box').hide();
                $('#userId_field_box').hide();
                break;
        }
    }

    function filterOptions(source, target, url){
        var ID= $('#field-' + source).val();
        var $el = $('#field-' + target);
        if(ID==null || ID=='')ID=0;
        $('#field'+source).append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+source+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#'+source+'_ajax_loader').show();
        $el.empty();
        $.post(
            url + ID,
            {},
            function(data){
                console.log(data);
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

    function populateItemFields(rowNumber, target, url){
        var element = $('#field-'+ rowNumber +'-'+ target);
        element.append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+target+rowNumber+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#'+target+rowNumber+'_ajax_loader').show();
        element.empty();
        $.post(
            url,
            {},
            function(data){
                element.append('<option value=""></option>');
                if(data != null){
                    $.each(data, function(key, val) {
                        element.append($('<option></option>')
                            .attr('value', val.value).text(val.property));
                    });
                    var currentVal= getItemFieldValue(rowNumber-1, target);
                    if(currentVal > 0 ){element.val(currentVal);}
                }
            },
            'json'
        )
            .fail(function() {
                //alert( "error" );
            })
            .always(function() {
                $('#'+target+rowNumber+'_ajax_loader').hide();
                element.chosen().trigger('liszt:updated');
            });
    }
    function itemRow(rowNumber){
        var $html= '';
        $html += '<li class="itemRow-'+rowNumber+'">';
        $html += '<ul>';
        $html += '<li><select id="field-'+rowNumber+'-cat" data-placeholder="Select Category" name= "item-'+rowNumber+'-cat"></select></li>';
        $html += '<li><select id="field-'+rowNumber+'-item" data-placeholder="Select Item" name = "item-'+rowNumber+'-item"></select></li>';
        $html += '<li><input type="number" id="field-'+rowNumber+'-quantity" min="0" name="item-'+rowNumber+'-quantity" /></li>';
        $html += '<li>';
        $html += '<a role="button" id="'+rowNumber+'" class="close_row_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary">';
        $html += '<span class="ui-button-icon-primary ui-icon ui-icon-circle-minus"></span>';
        $html += '<span class="ui-button-text">Delete</span>';
        $html += '</a>';
        $html += '</li>';
        $html += '</ul>';
        $html += '</li>';
        return $html;
    }
    function getItemFieldValue(rowNumber, field){
        if(state !=='edit') return 0;
        var items= $.parseJSON('<?php echo $itemDetails;?>');
        var numberOfItems= items.length;
        if(numberOfItems <= 0) return 0;
        if( rowNumber in items ){
            if(field ==='cat')return items[rowNumber].cat;
            if(field ==='item')return items[rowNumber].item;
            if(field ==='quantity')return items[rowNumber].quantity;
            }
        else return 0;
    }
    function deleteRow(elem){
        var rowNumber= elem.attr('id');
        $('li.itemRow-'+rowNumber).remove();
    }
</script>
