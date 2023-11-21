<script>
    // --------------------------
    // Collapse caret right/down process
    // --------------------------
    $('.collapse').on('show.bs.collapse', function() {
        const collapse_id = $(this).attr('id');
        $('button.btn-caret[data-bs-target="#' + collapse_id + '"]').html('<?php echo fontawesome('caret-down');?>');
    });
    $('.collapse').on('hide.bs.collapse', function(event) {
        event.stopPropagation();
        const collapse_id = $(this).attr('id');
        $('button.btn-caret[data-bs-target="#' + collapse_id + '"]').html('<?php echo fontawesome('caret-right');?>');
    });
</script>