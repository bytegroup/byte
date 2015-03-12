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
<div class="ui-corner-all" style="background-color: #f1f1f1; padding-top: 20px; margin-bottom: 20px; border: 1px #999 solid;">
    <?php echo $filter_form;?>
</div>
<?php
$rows= $data;
?>
<table id="report-table" class="display" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th class="report-header"></th>
        <?php foreach($headers as $header){?><th class="report-header"><?php echo $header;?></th><?php } ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="report-header"></th>
        <?php foreach($headers as $header){?><th><?php echo $header;?></th><?php } ?>
    </tr>
    </tfoot>

    <tbody>
    <?php foreach($rows as $fields): ?>
        <tr>
            <td><a role="button" href="" class="ui-button">Details</a></td>
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

        $('form#filter-form input#filter-excel').click(function(){
            $('form#filter-form')
                .attr('action', '<?php echo base_url(REPORT_FOLDER.'requisition_list/get_excel');?>')
                .attr('method', 'post')
                .attr('target', '_blank')
                .submit();
        });

        var dataTable= $('#report-table').dataTable();
        var url= '<?php echo base_url(REPORT_FOLDER.'requisition_list/ajax_get_data');?>';
        $('#filter-button').click(function(){filterDataTable(url, dataTable);});
        $('#company').change(function(){
            get_dependent_options('company', 'department', '<?php echo base_url(REPORT_FOLDER.'requisition_list/ajax_get_department');?>/');
        });
        $('#category').change(function(){
            get_dependent_options('category', 'item', '<?php echo base_url(REPORT_FOLDER.'requisition_list/ajax_get_items');?>/');
        });
    });

</script>