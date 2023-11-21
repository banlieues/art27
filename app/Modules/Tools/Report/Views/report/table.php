<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Liste des <?php echo $level_label;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    Liste des <?php echo $level_label;?>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Report\js/js_report');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="wrapper">
    <table id="reportsTable" class="table table-striped w-100" level="<?php echo $level;?>">
        <thead>
            <tr>
                <th></th>
                <th>Date de mise à jour</th>
                <th>Nom du <?php echo $level_label;?></th>
                <?php if($level == 'schema' || $level == 'template'):?> 
                    <th>Thématiques associées</th> 
                <?php elseif($level == 'publication'):?> 
                    <th>Demandeur</th> 
                    <th>Demande</th> 
                <?php endif;?>
                <th>Détails</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    let table;
    $(document).ready( function () {
        $.fn.dataTable.moment( 'DD/MM/YY - HH:mm' );
        const level = $('#reportsTable').attr('level');
        table = $('#reportsTable').DataTable({
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
            "order" : [[ 1, "desc" ]],
            "language" : {
                "url" : "<?php echo base_url('assets/french_datatable.json');?>"
            },
            "ajax": {
                "url": window.location.origin + '/report/' + level + 's/get',
            },
            "order": [ 
                [ 0,  'desc' ] 
            ],
            "columns": [
                { 
                    data: 'td_delete', 
                    "searchable": false, "orderable": false, className: "text-center", 
                },
                { 
                    data: 'updated_at', 
                    "searchable": false, className: "text-center"
                },
                { 
                    data: 'label' 
                },
                <?php if($level == 'schema' || $level == 'template'):?> 
                    { 
                        data: 'td_thems' 
                    }, 
                <?php elseif($level == 'publication'):?> 
                    {
                        data: function(data) {
                            return '<button class="btn btn-sm btn-secondary" onclick="js_modal_iframe_person(' + data.id_person + ');"> ' + data.fullname + ' </button>';
                        },
                        "className": "text-center",
                    },
                    { 
                        data: function(data) {
                        return '<button class="btn btn-sm btn-secondary" onclick="js_modal_iframe_demande(' + data.id_request + ');"> ' + data.id_request + ' </button>';
                        }, 
                        "searchable": false, "orderable": false, "className": "text-center", 
                    },
                <?php endif;?>
                { 
                    data: 'td_details', 
                    "searchable": false, "orderable": false, "className": "text-center", 
                },
            ],
        });
    });
    
    $.fn.DataTable.ext.pager.numbers_length = 5;

</script>

<?php $this->endSection(); ?>






