<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/27/15
 * Time: 3:05 PM
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
        var baseURL= '<?php echo base_url(REPORT_FOLDER);?>/';

        $('div.DTTT_container a#excelDownload').click(function(){
            $('form#filter-form')
                .attr('action', baseURL+'product_details/get_excel')
                .attr('method', 'post')
                .attr('target', '_blank')
                .submit();
        });

        $('form#filter-form input#filter-excel').click(function(){
            $('form#filter-form')
                .attr('action', baseURL+'product_details/get_excel')
                .attr('method', 'post')
                .attr('target', '_blank')
                .submit();
        });

        var dataTable= $('#report-table').dataTable();
        $('#filter-button').click(function(){
            filterDataTable(baseURL+'product_details/ajax_get_data', filterDT);
        });

        $('#company').change(function(){
            get_dependent_options('company', 'department', baseURL+'product_details/ajax_get_department/');
        });
        $('#category').change(function(){
            get_dependent_options('category', 'item', baseURL+'product_details/ajax_get_items/');
        });

        var filterDT = function(data){
            dataTable.fnClearTable();
            if(data.length ==0 )return false;
            $.each(data, function(id, val){
                dataTable.fnAddData(val);
            });
        }
    });

</script>