<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="wrapper">
    <table id="tagsTable" class="table table-striped w-100">
        <thead>
            <tr>
                <th></th>
                <th>Nom du tag</th>
                <th>Nombre de blocs reliés</th>
                <th>Blocs</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    let table;
    $(document).ready( function () {
        $.fn.dataTable.moment( 'DD/MM/YY - HH:mm' );
        table = $('#tagsTable').DataTable({
            "pageLength": 100,
            dom: "<'row justify-content-between align-items-center'<'col-2'B><'col-2 mt-3'l><'col-3'p><'col-2 mb-3'i><'col-3 mt-3'f>>",
            "infoCallback": function( settings, start, end, max, total, pre ) {
                return start + " à " + end + " sur " + total;
            },
            buttons: [{ 
                extend: 'excel', 
                text: 'Export Excel',
                className: 'btn-sm btn-light border rounded',
            }],
            "scrollX" : true,
            stateSave: true,
            "order" : [[ 1, "asc" ]],
            "language" : {
                "url" : "<?php echo base_url('assets/french_datatable.json');?>"
            },
            "ajax": {
                "url": "<?php echo base_url('report/get_tags');?>",
            },
            "columnDefs": [
//                { className: "text-center", "targets": [ 1, 3, 7 ] },
//                { "visible": false, "targets": [ 0, 1 ], "searchable": false },
            ],
            "columns": [
                { data: 'td_delete', "searchable": false, "orderable": false, className: "text-center", },
                { data: 'label' },
                { data: 'nb_blocks' },
                { data: 'td_blocks' },
            ],
        });
    });
    
    $.fn.DataTable.ext.pager.numbers_length = 5;

</script>


