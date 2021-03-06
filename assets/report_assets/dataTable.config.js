
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
    "sSwfPath": "",
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

function filterDataTable(url, fnCallBack){
    $('#filter-button').append('<img src="../ajax-loader.gif" border="0" id="filter_ajax_loader" class="dd_ajax_loader" style="display: none;">');
    $('#filter_ajax_loader').show();
    $.post(
        url,
        $( "#filter-form" ).serialize(),
        function(data){
            if(data != null){
                fnCallBack(data);
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