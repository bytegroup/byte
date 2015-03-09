<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/9/15
 * Time: 2:29 PM
 */
?>
<style type="text/css">
    .dataTable td{border-left: 1px #ddddff solid;}
    .dataTable th{border-left: 1px #ddddff solid;}
    .dataTable th:last-child{border-right: 1px #ddddff solid;}
    .dataTable td:last-child{border-right: 1px #ddddff solid;}
    .dataTable thead tr th{border-top:0px #000 solid; text-align: left;}
    .dataTable tfoot tr th{border-bottom:1px #000 solid;}
    .dataTable tfoot input {  width: 100%;  padding: 3px;  box-sizing: border-box;  }
</style>
<h3><?php echo $pageTitle; ?></h3>
<?php
$rows= $data;
?>
<table id="report-table" class="display" width="100%" cellspacing="0">
    <thead>
    <?php
    $numberOfColumns= count($headers);
    foreach($metadata as $title=>$value){ ?>
        <tr>
            <th colspan="2"><?php echo $title;?></th>
            <td colspan="<?php echo ($numberOfColumns-2);?>"><?php echo $value;?></td>
        </tr>
    <?php    } ?>
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

<script language="JavaScript">
    $(document).ready(function(e){

        $("#collapseReport").removeClass("in").addClass("in");
        $('#report-table tfoot th').each( function () {
            var title = $('#report-table thead th.report-header').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="'+title+'" />' );
        } );

        var table = $('#report-table').DataTable({
            "scrollX": true,
            "scrollY": "500px",
            "scrollCollapse": true
            //"paging": false
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
                "print",
                "pdf"
            ]
        });
        $( tt.fnContainer() ).insertBefore('div.dataTables_filter');

        $('div.DTTT_container').append(
            '<a id="dlink"  style="display:none;"></a>' +
            '<a id="excelDownload" class="DTTT_button DTTT_button_ExcelDownload">Excel</a>'
        );
        $('div.DTTT_container a#excelDownload').click(function(){
            var oTable =  $('#report-table'). dataTable();
            var oData = oTable.fnGetData();
            window.location= '<?php echo base_url(REPORT_FOLDER.'quotation_details/get_excel');?>';
            //console.log(oData);
        });
    });

</script>