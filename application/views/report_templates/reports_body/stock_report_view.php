<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/10/15
 * Time: 5:04 PM
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
<?php
$rows= $data;
?>
<table id="report-table" class="display" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th colspan="7" rowspan="2">&nbsp;</th>
            <th colspan="5">Current Stock</th>
        </tr>
        <tr>
            <th colspan="2">Stock</th>
            <th colspan="1">&nbsp;</th>
            <th colspan="2">Damage</th>
        </tr>
        <tr>
            <?php foreach($headerFields as $header){?><th class="report-header"><?php echo $header;?></th><?php } ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <?php foreach($headerFields as $header){?><th><?php echo $header;?></th><?php } ?>
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

<script language="JavaScript">
    $(document).ready(function(e){

        $("#collapseReport").removeClass("in").addClass("in");
        $('#report-table tfoot th').each( function () {
            var title = $('#report-table thead th.report-header').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="'+title+'" />' );
        } );

        var table = $('#report-table').DataTable({
            "scrollX": true
        });
        table.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () {
                table
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            } );
        } );
        var tt = new $.fn.dataTable.TableTools(table, {
            "sSwfPath": "<?php echo $base_url.REPORT_ASSETS;?>TableTools/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                "copy",
                "print"
            ]
        });
        $( tt.fnContainer() ).insertBefore('div.dataTables_filter');

        $('div.DTTT_container').append(
            '<a id="dlink"  style="display:none;"></a>' +
            '<a id="excelDownload" class="DTTT_button DTTT_button_ExcelDownload">Excel</a>'
        );
        $('div.DTTT_container a#excelDownload').click(function(){
            tableToExcel('report-table', '<?php echo $pageTitle;?>', '<?php echo $pageTitle;?>.xls');
        });
    });

</script>