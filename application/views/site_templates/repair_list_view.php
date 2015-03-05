<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/2/15
 * Time: 12:46 PM
 */
?>

<style type="text/css">
    table.dataTable th:first-child{width: 20px!important;text-align: center;padding: 2px;}
    table.dataTable td:first-child{width: 20px;text-align: center;}
    div.add-repair-bill-buttons{float: left;}
</style>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $("table.dataTable input[name='check']").hide();

        $("div.dataTables_wrapper").prepend('' +
        '<div class="add-repair-bill-buttons">' +

        '<a href="#" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"> <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus Sbfce27ff"></span><span class="ui-button-text">&nbsp;Add Repair Bill</span> </a>' +
        '</div>' +
        '');
        $('div.add-repair-bill-buttons a').click(function(){
            var ids= '';
            $('table.dataTable input[type="checkbox"]').each(function(){
                if($(this).is(':checked') && !$(this).is(':disabled')) ids += $(this).val()+';';
            });
            if(ids==='' || ids===null){
                alert('Please select at least one repair to add the repair bill.');
            }else{
                ids= encodeURIComponent(ids.slice(0,-1));
                window.location= '<?php echo base_url(IT_MODULE_FOLDER.'repair_bill/index');?>/'+ids+'/add';
            }
        });
    });
</script>
