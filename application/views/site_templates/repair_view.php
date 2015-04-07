<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 12:42 PM
 */
?>
<style type="text/css">
    form#crudForm div.repair-list{border-top: 1px #999 solid;border-bottom: 1px #999 solid;padding-bottom:5px; }
    div.repair-list ul{width: 100%;list-style: none; margin: 0;}
    div.repair-list ul li ul li{display: inline-block;padding: 0 4px;width: 220px;text-align: center;height: 22px;}
    div.repair-list ul li ul li:first-child{width: 100px;}
    div.repair-list ul li ul li:nth-child(2){text-align: left;}
    div.repair-list ul li ul li:last-child{font-weight: bold;text-align: right;width: 120px;}
    div.repair-list ul li ul li:nth-child(3){width: 100px;}
    div.repair-list ul li ul.table-header{background-color: #9acc9a;}
    div.repair-list ul li ul{border-bottom: 1px #9acc9a solid;}
    div.repair-list ul li:last-child ul{border-bottom: none;}
    div.repair-list ul li ul li span{border-bottom: 4px #9acc9a double;}
    div.repair-list-buttons{border-top: 1px #999 solid; padding-top:5px; }
    div.repair-list-buttons ul{width: 100%; list-style: none;}
    div.repair-list-buttons ul li{display: inline-block; padding: 0 5px;}

</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Damage List" onclick="window.location='<?php echo $backToDamageList; ?>'" class="ui-input-button" id="">
    </div>
</div>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        var isRepairComplete = <?php  echo json_encode($repairComplete);?>;
        var isAlreadyInRepair= <?php echo json_encode($alreadyInRepair);?>;
        var damDetailId= <?php echo json_encode($damageDetailId);?>;

        $("#field-repairAmount").numeric();
        $("#field-repairTypeId").change(function(){
            repair_amount($(this).val());
        });
        $("form#crudForm").append('<div class="repair-list"></div>');
        $("form#crudForm div.repair-list").html(list());

        $("form#crudForm").append(button_list());

        $('form#crudForm a#repair-bill-button').click(function(){
            var ids= '';
            $('div.repair-list input[type="checkbox"]').each(function(){
                if($(this).is(':checked') && !$(this).is(':disabled')) ids += $(this).val() + ';';
            });

            if(ids==='' || ids===null){
                sweetAlert('Nop!', 'Please select at least one repair to add the repair bill.', 'warning');
            }else{
                ids= encodeURIComponent(ids.slice(0,-1));
                window.location= '<?php echo base_url(IT_MODULE_FOLDER.'repair_bill/index');?>/'+ids+'/add';
            }
        });
        $('form#crudForm a#permanent-damage-button').click(function(){
            permanent_damage(damDetailId);
        });
        $('form#crudForm a#add-to-stock-button').click(function(){
            add_to_stock(damDetailId);
        });

        function button_list(){
            var $html = '';
            $html += '<div class="repair-list-buttons">';
            $html += '<ul>';

            if(!isRepairComplete){
                $html += '<li>'+get_button('<?php echo base_url(IT_MODULE_FOLDER.'repair/index/'.$damageDetailId).'/add';?>', 'Add New Repair', 'ui-icon-circle-plus')+'</li>';
                $html += '<li>'+get_button('#', 'Repair Bill', 'ui-icon-calculator')+'</li>';
                $html += '<li>'+get_button('#', 'Add to Issue', 'ui-icon-circle-plus')+'</li>';
                $html += '<li>'+get_button('#', 'Add to Stock', 'ui-icon-circle-plus')+'</li>';
                $html += '<li>'+get_button('#', 'Permanent Damage', 'ui-icon-circle-plus')+ '</li>';
            }else{
                $('form#crudForm input#form-button-save').prop('disabled', true);
                $html += '<li>'+get_button('#', 'Repair Bill', 'ui-icon-calculator')+'</li>';
            }

            $html += '</ul>';
            $html += '</div>';
            return $html;
        }

    });

    function repair_amount(repairTypeId){
        var url='<?php echo base_url(IT_MODULE_FOLDER.'repair/ajax_get_repair_amount')?>/'+repairTypeId;
        $('#repairTypeId_input_box').append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="repairTypeId_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#repairTypeId_ajax_loader').show();
        $.post(
            url,
            {},
            function(data){$("#field-repairAmount").val(parseFloat(data));}
            ,
            'json'
        )
        .fail(function() {
                $("#field-repairAmount").val(parseFloat('0'));
        })
        .always(function() {
            $('#repairTypeId_ajax_loader').hide();
        });
    }
    function list(){
        var $html= '';
        $html += '<ul>';
        $html += '<li class="list-table-header">';
        $html += '<ul class="table-header">';
        $html += '<li>&nbsp;</li><li>Repair Type</li><li>Repair Date</li><li>Vendor</li><li>Repair Amount</li>';
        $html += '</ul>';
        $html += '</li>';
        repair_list();
        $html += '<li>';
        $html += '<ul>';
        $html += '<li>&nbsp;</li>' +
        '<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li><span class="total-amount"></span></li>';
        $html += '</ul>';
        $html += '</li>';
        $html += '</ul>';
        return $html;
    }
    function repair_list(){
        var $html = '';
        var $total= 0.0;
        var url='<?php echo base_url(IT_MODULE_FOLDER.'repair/ajax_get_repair_list')?>/'+<?php echo $damageDetailId;?>;
        $.post(
            url,
            {},
            function(data){
                $.each(data, function(key, val){
                    var disabled = (val.billId != 0) ? 'disabled checked':'';
                    $html = '<li>';
                    $html += '<ul>';
                    $html += '<li>';
                    $html += '<input type="checkbox" name="repairIds[]" '+disabled+' value="'+key+'"/>&nbsp;&nbsp;';
                    $html += '<a href="<?php echo base_url(IT_MODULE_FOLDER.'repair/index/'.$damageDetailId).'/edit/';?>'+key+'" class="ui-widget ui-state-default" role="button">' +
                    '&nbsp;Edit&nbsp;' +
                    '</a>' +
                    '&nbsp;' +
                    '<a href="#" class="ui-widget ui-state-default" role="button">' +
                    '&nbsp;Delete&nbsp;' +
                    '</a>' +
                    '</li>';
                    $html += '<li>'+val.repairType+'</li><li>'+val.date+'</li><li>'+val.vendor+'</li><li>'+val.amount+'</li>';
                    $html += '</ul>';
                    $html += '</li>';
                    $total += parseFloat(val.amount);
                    $('li.list-table-header').after($html);
                });
                $('span.total-amount').html('Total = '+$total);
            }
            ,
            'json'
        )
            .fail(function() {
                //alert('error');
            })
            .always(function() {
            });
    }
    function get_button($href, value, iconClass){
        var buttonId= value.toLowerCase().replace(/ /g, "-");
        var $html='';
        $html += '<a ';
        $html += 'role="button" id="'+buttonId+'-button" ';
        $html += 'href="'+$href+'" ';
        $html += 'class="ui-button ui-state-default ui-button-text-icon-primary">';
        $html += '<span class="ui-button-icon-primary ui-icon '+iconClass+'"></span>';
        $html += '<span class="ui-button-text">'+value+'</span>';
        $html += '</a>';

        return $html;
    }
    function permanent_damage(damageDetailId){
        var url='<?php echo base_url(IT_MODULE_FOLDER.'repair/ajax_permanent_damage_declare')?>/'+damageDetailId;
        $('a#permanent-damage-button').append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="permanent-damage_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#permanent-damage_ajax_loader').show();
        $.post(
            url,
            {},
            function(data){
                swal({
                    title: data.productCode,
                    text: "Successfully declared as 'Permanently Damaged'",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9acc9a",
                    confirmButtonText: "Ok",
                    closeOnConfirm: false
                },
                function(){
                    window.location=data.redirectURL;
                });
            }
            ,
            'json'
        )
            .fail(function() {
                $("#field-repairAmount").val(parseFloat('0'));
            })
            .always(function() {
                $('#permanent-damage_ajax_loader').hide();
            });
    }
    function add_to_stock(damageDetailId){
        var url='<?php echo base_url(IT_MODULE_FOLDER.'repair/ajax_add_to_stock')?>/'+damageDetailId;
        $('a#add-to-stock-button').append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="add-to-stock_ajax_loader" class="dd_ajax_loader" style="display: none;">');
        $('#add-to-stock_ajax_loader').show();
        $.post(
            url,
            {},
            function(data){
                swal({
                    title: data.productCode,
                    text: "Successfully added to Stock",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9acc9a",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                },
                function(){
                    window.location=data.redirectURL;
                });
            }
            ,
            'json'
        )
            .fail(function() {
                $("#field-repairAmount").val(parseFloat('0'));
            })
            .always(function() {
                $('#add-to-stock_ajax_loader').hide();
            });
    }
</script>