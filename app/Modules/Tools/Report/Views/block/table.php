<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="wrapper">
    <table id="blocksTable" class="table table-striped w-100">
        <thead>
            <tr>
                <th></th>
                <th> <?php echo t("Date de mise à jour", __NAMESPACE__);?> </th>
                <th> <?php echo t("Nom du bloc", __NAMESPACE__);?> </th>
                <th> <?php echo t("Thématiques", __NAMESPACE__);?> </th>
                <th> <?php echo t("Tags", __NAMESPACE__);?> </th>
                <th> <?php echo t("Màj le", __NAMESPACE__);?> </th>
                <th> <?php echo t("Màj par", __NAMESPACE__);?> </th>
                <th> <?php echo t("Détails", __NAMESPACE__);?> </th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    let table;
    $(document).ready( function () {
        $.fn.dataTable.moment( 'DD/MM/YY - HH:mm' );
        table = $('#blocksTable').DataTable({
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
            "order" : [[ 0, "desc" ]],
            "language" : {
                "url" : "<?php echo base_url('assets/french_datatable.json');?>"
            },
            "ajax": {
                "url": "<?php echo base_url('report/get_blocks');?>",
            },
            "order": [ 
                [ 1,  'desc' ] 
            ],
            "columns": [
                {
                    data: 'td_delete', 
                    "searchable": false, "orderable": false, className: "text-center",
                },
                {
                    data: 'updated_at',
                },
                {
                    data: 'label',
                },
                {
                    data: 'them_paths',
                    "orderable": false, 
                },
                {
                    data: 'tagnames',
                    "orderable": false, 
                },
                {
                    data: 'updated_at',
                },
                {
                    data: 'update_username',
                },
                {
                    data: 'td_details',
                    "searchable": false, "orderable": false, className: "text-center"
                },
            ],
        });
    });
    
    $.fn.DataTable.ext.pager.numbers_length = 5;

</script>

