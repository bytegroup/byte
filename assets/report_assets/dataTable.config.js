
$('#report-table tfoot th').each( function () {
    var title = $('#report-table thead th.report-header').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
} );


$("#tabs").tabs( {
    "activate": function(event, ui) {
        $( $.fn.dataTable.tables( true ) ).DataTable().columns.adjust();
    }
} );

var table = $('#report-table').DataTable({
    "scrollX"       : true,
    "scrollCollapse": true,
    "scrollY"       : '500px',
    "jqueryUI"      : true
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
    "sSwfPath": "../assets/report_assets/TableTools/swf/copy_csv_xls_pdf.swf",
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

function filterDataTable(url, tableObj){
    $('#filter-button').append('<img src="../ajax-loader.gif" border="0" id="filter_ajax_loader" class="dd_ajax_loader" style="display: none;">');
    $('#filter_ajax_loader').show();
    $.post(
        url,
        $( "#filter-form" ).serialize(),
        function(data){
            tableObj.fnClearTable();
            if(data != null && data.length!=0){
                tableObj.fnAddData(data);
            }
        },
        'json'
    )
        .fail(function() {
            //alert( "error" );
        })
        .always(function() {
            $('#filter_ajax_loader').remove();
        });
}