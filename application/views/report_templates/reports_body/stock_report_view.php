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
    <?php foreach($rows as $fields): ?>
        <tr>
            <?php foreach($fields as $field){?>
                <td><?php echo $field;?></td>
            <?php } ?>
        </tr>
    <?php endforeach;?>
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
        $.fn.dataTable.TableTools.buttons.download = $.extend(
            true,
            {},
            $.fn.dataTable.TableTools.buttonBase,
            {
                "sButtonText": "Download",
                "sUrl":      "",
                "sType":     "POST",
                "fnData":    false,
                "fnClick": function( button, config ) {
                    var dt = new $.fn.dataTable.Api( this.s.dt );
                    var data = dt.ajax.params() || {};

                    // Optional static additional parameters
                    // data.customParameter = ...;

                    if ( config.fnData ) {
                        config.fnData( data );
                    }

                    var iframe = $('>iframe/<', {
                        id: "RemotingIFrame"
                    }).css( {
                        border: 'none',
                        width: 0,
                        height: 0
                    } )
                        .appendTo( 'body' );

                    var contentWindow = iframe[0].contentWindow;
                    contentWindow.document.open();
                    contentWindow.document.close();

                    var form = contentWindow.document.createElement( 'form' );
                    form.setAttribute( 'method', config.sType );
                    form.setAttribute( 'action', config.sUrl );

                    var input = contentWindow.document.createElement( 'input' );
                    input.name = 'json';
                    input.value = JSON.stringify( data );

                    form.appendChild( input );
                    contentWindow.document.body.appendChild( form );
                    form.submit();
                }
            }
        );
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
                "print",
                {
                    "sExtends":    "collection",
                    "sButtonText": "Save",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                },
                {
                    "sExtends":    "download",
                    "sButtonText": "Download XLS",
                    "sUrl":        "<?php echo base_url('report/stock_report/pdfTest');?>"
                }
            ]
        });
        $( tt.fnContainer() ).insertBefore('div.dataTables_filter');

    });
</script>