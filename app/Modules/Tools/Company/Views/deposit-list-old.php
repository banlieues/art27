<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    <?php echo translator("Dépôt des demandes");?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    <?php echo translator("Dépôt des demandes");?>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Company\js/js_company');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<table id="table_deposit" class="table table-striped table-bordered w-100">
    <thead> 
        <th></th>
        <!-- <th> ID dépôt </th> -->
        <th> Date de demande </th>
        <th> Dénomination </th>
        <th> Ville </th>
        <th> Remarques </th>
        <th> Actions </th>
    </thead>
    <tfoot> 
        <th></th>
        <!-- <th> ID dépôt </th> -->
        <th> Date de demande </th>
        <th> Dénomination </th>
        <th> Ville </th>
        <th> Remarques </th>
        <th> Actions </th>
    </tfoot>
</table>

<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.moment( 'DD/MM/YY - HH:mm' );
        $('#table_deposit').DataTable({
//                        "paging": true,
            "pageLength": 100,
//                        dom : 'Bfrpt',
            dom: "<'row justify-content-between align-items-center'<'col-2'B><'col-2 mt-3'l><'col-3'p><'col-2 mb-3'i><'col-3 mt-3'f>>" 
                + "<'row'<'col-12'tr>>" 
                + "<'row justify-content-between align-items-center'<'col-2'B><'col-2 mt-3'l><'col-3'p><'col-2 mb-3'i><'col-3 mt-3'f>>",
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
//                        "processing": true,
//                        "serverSide": true,
//                        "serverMethod": 'post',
            "ajax": {
                "url": "<?php echo base_url('company/deposits/get')?>",
//                            "pageLength": 10,
//                            "dataSrc": data,
//                            "type": "POST",
            },
            "columns": [
                {   
                    // data: function(data) {
                    //     const html = '<button type="button" class="btn text-danger p-0" post-data={"id_deposit":' + data.id_deposit + '} onclick="modal_show(this, \'<?php //echo $url_modal_deposit_delete;?>\');"> <?php echo fontawesome('trash-alt');?> </button>';
                    //     return html;
                    // }, 
                    data: function(data) {
                        const html = '<button type="button" class="btn text-danger p-0" onclick="deposit_delete_modal(this, ' + data.id_deposit + ');"> <?php echo fontawesome('trash-alt');?> </button>';
                        return html;
                    },
                    className: 'text-center',
                    orderable: false, 
                },
                // { data: 'id_deposit' },
                { data: 'date_create' },
                { data: 'label' },
                { data: 'address_city' },
                { 
                    data: function(data) {
                        let html = '';
                        if(data.comment && data.comment.trim() != '') {
                            html = '<a role="button" class="text-dark" title="' + data.comment + '"> <?php echo fontawesome('clipboard');?> </a>'
                        }
                        return html;           
                    },
                    className: 'text-center',
                    orderable: false,
                },
                {   
                    data: function(data) {
                        const html = '<button type="button" class="btn py-0" onclick="deposit_info_modal(this, ' + data.id_deposit + ')"> <?php echo fontawesome('info-circle');?> </button>';
                        return html;
                    }, 
                    // data: function(data) {
                    //     const html = '<button type="button" class="btn py-0" post-data={"id_deposit":' + data.id_deposit + '} onclick="modal_show(this, \'<?php //echo $url_modal_deposit_info;?>\', \'Je recherche des éventuels doublons.\')"> <?php echo fontawesome('info-circle');?> </button>';
                    //     return html;
                    // }, 
                    className: 'text-center',
                    orderable: false,
                },
            ],
        });
    });
    $.fn.DataTable.ext.pager.numbers_length = 5;

</script>

<?php $this->endSection(); ?>


