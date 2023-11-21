<script>

    function module_mail_modal_info(id_email)
    {
        $.get(window.location.origin + '/mail/example/email_info_modal/' + id_email, function(view) {
            $('#modal').html(view);
            $('#modal').modal('show');
        });
    }

    function module_mail_modal_archive(id_email)
    {
        $.get(window.location.origin + '/mail/example/email_archive_modal/' + id_email, function(view) {
            $('#modal').html(view);
            $('#modal').modal('show');          
        });
    }

    function module_mail_modal_delete(id_email)
    {
        $.get(window.location.origin + '/mail/example/email_delete_modal/' + id_email, function(view) {
            $('#modal').html(view);
            $('#modal').modal('show');          
        });
    }
    
    function module_mail_output_example(id_email)
    {
        $.get(window.location.origin + '/mail/example/email_info_modal/' + id_email, function(view) {
            $('#modal').html(view);
            $('#modal').modal('show'); 
        });
    }

    function module_mail_modal_edit(id_email)
    {
        $.get(window.location.origin + '/mail/example/email_edit_modal/' + id_email, function(view) {
            $('#modal').html(view);
            $('#modal').modal('show');
        });
    }

    function module_mail_save_example(elem, id_email=null)
    {
        const form_id = $(elem).attr('form');
        const form = $('#' + form_id);
        // required to save and get back data : module_mail_save_process(elem, function(data) {});
        module_mail_save_process(elem, function(data) {
            // custom process
            const output_url = $(elem).attr('output-url');
            let isNew = 1;
            if(id_email) {
                data.id_email = id_email;
                isNew = 0;
            }
            $.post(output_url, data, function(id_email_save) { 
                if(id_email_save) {
                    $('#submitButtons').append('<button type="button" class="btn btn-sm btn-info mb-1" onclick="module_mail_output_example(' + id_email_save + ');"> Output </button>');
                    $('#submitButtons').append('<a role="button" class="btn btn-sm btn-secondary mb-1" href="' + window.location.origin + '/mail/list' + '"> Go to email list </a>');
                }
            });
        });
    }

    function module_mail_send_example(elem, id_email=null)
    {
        // required to send and get back data : module_mail_send_process(elem, function(data) {});
        module_mail_send_process(elem, function(data) {
            // custom process
            const output_url = $(elem).attr('output-url');
            let isNew = 1;
            if(id_email) {
                data.id_email = id_email;
                isNew = 0;
            }
            $.post(output_url, data, function(id_email_save) { 
                if(isNew == 1) {
                    $('#submitButtons').append('<button type="button" class="btn btn-sm btn-info mb-1" onclick="module_mail_output_example(' + id_email_save + ');"> Output </button>');
                    $('#submitButtons').append('<a role="button" class="btn btn-sm btn-secondary mb-1" href="' + window.location.origin + '/mail/list' + '"> Go to email list </a>');
                } else {
                    window.location.reload();
                }
            });
        });
    }

    
</script>