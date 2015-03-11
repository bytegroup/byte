<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/3/15
 * Time: 5:55 PM
 */
?>
<style type="text/css">
    .dataTable td{border-left: 1px #ddddff solid;}
    .dataTable th{border-left: 1px #ddddff solid;}
    .dataTable th:last-child{border-right: 1px #ddddff solid;}
    .dataTable td:last-child{border-right: 1px #ddddff solid;}
    .dataTable thead tr:first-child th{border-top:1px #000 solid;}
    .dataTable tfoot tr th{border-bottom:1px #000 solid;}
    .dataTable tfoot input {  width: 100%;  padding: 3px;  box-sizing: border-box;  }
</style>
<h3><?php echo $pageTitle; ?></h3>
<div class="ui-corner-all fill">
    <?php echo $filter_form;?>
</div>
<?php
$rows= $data;
?>
<table id="report-table" class="display" width="100%" cellspacing="0">
    <thead>
    <tr>
        <?php foreach($headers as $header){?><th class="report-header"><?php echo $header;?></th><?php } ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <?php foreach($headers as $header){?><th><?php echo $header;?></th><?php } ?>
    </tr>
    </tfoot>

    <tbody>
    <?php foreach($rows as $fields): ?>
        <tr>
            <?php foreach($fields as $field){?>
                <td><?php echo $field;?></td>
            <?php } ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
<script type="text/javascript" src="<?php echo $table_js;?>"></script>
<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseReport").removeClass("in").addClass("in");

        $('div.DTTT_container').append(
            '<a id="dlink"  style="display:none;"></a>' +
            '<a id="excelDownload" class="DTTT_button DTTT_button_ExcelDownload">Excel</a>'
        );
        $('div.DTTT_container a#excelDownload').click(function(){
            window.location= '<?php echo base_url(REPORT_FOLDER.'requisition_list/get_excel');?>';
        });

        var dataTable= $('#report-table').dataTable();
        var url= '<?php echo base_url(REPORT_FOLDER.'requisition_list/ajax_get_data');?>';
        $('#filter-button').click(function(){
            filterDataTable(url, dataTable);
        });
    });

    function filterOptions(source, target, url){
        var ID= $('#' + source).val();
        var $el = $('#' + target);
        if(ID==null || ID=='')ID=0;
        $('#'+source).append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+source+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
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