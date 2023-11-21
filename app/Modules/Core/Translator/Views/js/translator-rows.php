<script>

// -------------------------------
// Translator functions
// -------------------------------

function row_delete_modal(elem) {
    const tr = $(elem).closest('tr');
    const id_transl = $(tr).attr('id_transl');
    modal_show(elem, "<?php echo base_url('translator/row');?>/" + id_transl + "/delete/modal");
}

function row_delete(elem, id_transl) {
    const tr = $('tr[id_transl="' + id_transl + '"]');
    const tbody = $(tr).closest('tbody');
    const nb_columns = $(elem).attr('nb_columns');

    $.get("<?php echo base_url('translator/row');?>/" + id_transl + "/delete", function() {
        $(tr).fadeOut(function() { 
            $(tr).outerHtml('');
            if($('tr[id_transl]').length==0) {
                $(tbody).html('<td colspan="' + nb_columns + '" class="p-4 text-center"> Plus de ligne de traduction avec ces critères. </td>')
            }
        });
    });
}

function row_edit(elem) {
    const tr = $(elem).closest('tr');
    const height = $(tr).height();
    $('textarea', tr).height(height);
    $('button.form_read:visible').add('.form_read', tr).fadeOut(function() {
        $('.form_update:hidden', tr).fadeIn();
    });
}

function row_undo(elem) {
    const tr = $(elem).closest('tr');
    const id_transl = $(tr).attr('id_transl');
    $('.form_update:visible', tr).fadeOut();
    $(tr).fadeTo('default', 0);
    $.get("<?php echo base_url('translator/row');?>/" + id_transl, function(view) {
        $(tr).html(view);
        $(tr).fadeTo('default', 1);
        $('.form_read:hidden').fadeIn();
    });
}

function row_save(elem) {
    const tr = $(elem).closest('tr');

    const form_id = $(elem).attr('form');
    $(tr).append('<form id="' + form_id + '"></form>');
    const formdata = new FormData($('#' + form_id)[0]);
    
    const id_transl = $(tr).attr('id_transl');
    $('.form_update:visible', tr).fadeOut();
    $(tr).fadeTo('default', 0);
    $.ajax({
        url: "<?php echo base_url('translator/row');?>/" + id_transl,
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        dataType: 'html',
        success: function(view) {
            $(tr).html(view);
            $(tr).fadeTo('default', 1);
            $('.form_read:hidden').fadeIn();
        },
        error: function(data) { console.log(data); }
    });
}

</script>