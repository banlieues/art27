<script type="text/javascript">

// ------------------------------------------------------------------
// JS_MAILING
// ------------------------------------------------------------------


function mailing_save(elem, id_email=null)
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
            alert(id_email_save);
            // if(id_email_save) {
            //     $('#submitButtons').append('<button type="button" class="btn btn-sm btn-info mb-1" onclick="module_mail_output_example(' + id_email_save + ');"> Output </button>');
            //     $('#submitButtons').append('<a role="button" class="btn btn-sm btn-secondary mb-1" href="' + window.location.origin + '/mail/list' + '"> Go to email list </a>');
            // }
        });
    });
}

function mailing_send(elem, id_email=null)
{
    // required to send and get back data : module_mail_send_process(elem, function(data) {});
    waiting_start(elem);
    module_mail_send_process(elem, function(data) {
        console.log(data);
        if(typeof data == "undefined") {
            waiting_end(elem);
        } else {
            data.isSended = 1;
            const output_url = $(elem).attr('output-url');
            $.post(output_url, data, function(id_email_save) { 
                window.location.replace("<?php echo base_url('mailing/email/sended');?>");
            });
        }
    });
}

function template_select(elem)
{
    const id_template = $(elem).val();
    if(id_template) {
        const url = "<?php echo base_url('mailing/template/get');?>/" + id_template;
        $.get(url, function(template) {
            template = JSON.parse(template);
            if(!template.subject) template.subject = '';
            if(!template.message) template.message = '';
            $('#mailSubject').val(template.subject);
            $('#mailMessage').summernote('code', template.message);
        });
    }
    else {
        $('#mailSubject').val('');
        $('#mailMessage').summernote('code', '');
    }
}

function template_delete_modal(elem, id_template)
{
    const url = window.location.origin + '/mailing/template/delete/modal/' + id_template;
    ajax_modal(url);   
}

function variable_delete_modal(elem, id_var)
{
    const url = window.location.origin + '/mailing/variable/delete/modal/' + id_var;
    ajax_modal(url);   
}

function variable_new(elem)
{
    const form_id = $(elem).attr('form');
    const form = $('#' + form_id)[0];
    let formdata = new FormData(form);
    const url = window.location.origin + '/mailing/variable/new';
    ajax_html(url, formdata, function(id_var) {
        window.location.reload();
    });
}

function variable_new_modal()
{
    const url = window.location.origin + '/mailing/variable/new/modal';
    ajax_modal(url);   
}

function send_test_modal(elem, id_template)
{
    waiting_start(elem);
    $.get(window.location.origin + '/mailing/send/test/modal/' + id_template, function(result) {
        result = JSON.parse(result);
        $('.modal-title','#modal').html("Envoi d'un email test");
        $('.modal-body','#modal').html(result.body);
        $('.modal-footer','#modal').prepend('<button form="sendingEmailTest" class="btn btn-sm btn-primary" onclick="waiting_start(this);"> Tester </button>');
        $('#modal').modal('show');
        waiting_end(elem);
    });
}

$('#modal').on('hidden.bs.modal', function() {
    $('.modal-footer', this).html('<button type="button" class="btn btn-primary modal-close valid-button" data-dismiss="modal"> Valider </button><button type="button" class="btn btn-outline-secondary modal-close" data-dismiss="modal">Annuler</button>');
    $('.summernote', this).summernote('reset');
});

function js_edit_content_modal(elem)
{
    const form_id = $(elem).closest('form').attr('id');
    const div = $(elem).siblings('div[data-name]');
    const content = $(div).html();
    const name = $(div).attr('data-name');

    // _set_summernote(elem);
    $('.summernote', '#myModalEditor').summernote('code', content);

    $('.valid-button','#myModalEditor').attr('onclick', 'js_edit_content(\'' + form_id+ '\', \''+ name + '\');');
    $('#myModalEditor').modal('show');
}

function js_edit_content(form_id, name)
{
    const html = $('.summernote', '#myModalEditor').summernote("code");
    const form = $('#' + form_id);
    $(form).find('[data-name="' + name + '"]').each(function() {
        $(this).html(html);
    });
    $(form).find('[name="' + name + '"]').each(function() {
        $(this).val(html);
    });
}

</script>

