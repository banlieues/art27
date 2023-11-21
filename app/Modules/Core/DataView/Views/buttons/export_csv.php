<button type="button" form="searchOrderForm" class="btn btn-sm btn-dark" 
    onclick="table_export_csv(this);"
    export-module="<?php echo $module;?>"
    export-model="<?php echo $model;?>"
    export-method="<?php echo $method;?>"
    export-filename="<?php echo $filename;?>"
    title="Exporter le tableau en CSV"
    >
    <?php echo fontawesome('file-download');?>
</button>

<script>
    function table_export_csv(elem) 
    {
        waiting_start(elem);
        const form_id = $(elem).attr('form');
        const form = $('#' + form_id);
        let url = "<?php echo base_url('file/export');?>/" + "/" + $(elem).attr('export-filename') + "/" + $(elem).attr('export-module') + "/" + $(elem).attr('export-model') + "/" + $(elem).attr('export-method');
        $(form).attr('method', 'get').attr('action', url);
        $(form).submit();
        $(window).blur(function() {
            $(form).removeAttr('method').removeAttr('action');
            waiting_end(elem);
        });
    }
</script>
