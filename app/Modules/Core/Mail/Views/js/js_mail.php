<script>

    // js_mail

    function template_select(elem)
    {
        const id_template = $(elem).val();
        if(id_template) {
            const url = "<?php echo base_url('mail/template');?>/" + id_template;
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

    function get_attachs_by_id_event(id_event)
    {
        if($('#mailAttachEvent').html().trim() == '') {
            $.get(window.location.origin + '/mail/get_attachs_by_id_event/' + id_event, function(view) {
                $('#mailAttachEvent').html(view);
            });
        }
    }

    function module_mail_send_process(elem, callback)
    {
        const form = $('#' + $(elem).attr('form'))[0];
        $('.plusminus-model', form).remove();

        let formdata = new FormData(form);
        formdata.append('message', $('#mailMessage', form).summernote('code'));
        formdata.append('signature', $('#mailSignature', form).html());

        $.ajax({
            url: "<?php echo base_url('mail/send');?>",
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            dataType: 'json',
            success: function(result) {
                if(!result.error) 
                {
                    if(result.isValid == 1) {
                        callback(result);
                    } else {
                        let error = [];
                        error[0] = 'Le formulaire est incomplet pour être envoyé.';
                        if(result.invalid_fields) {
                            $('[name]').removeClass('is-invalid');
                            $('[name]').parent().find('.invalid-feedback').text('').hide();
                            for(let key in result.invalid_fields) {
                                error.push(result.invalid_fields[key]);
                                $('[name^="' + key + '"]').addClass('is-invalid');
                                $('[name^="' + key + '"]').parent().find('.invalid-feedback').text(result.invalid_fields[key]).show();
                            }
                        }
                        $('#invalid_fields').html('<div class="alert alert-warning mx-4 mb-4" style="margin: 0 20px 20px 20px">' + error.join('<br>') + '</div>');
                        location.href = "#invalid_fields";
                        callback();
                    }                    
                } else {
                    console.log(result.error);
                    alert('Une erreur s\'est produite... (1)');
                }
            },
            error: function(data) {
                console.log(data);
                alert('Une erreur s\'est produite... (2)');
            }
        });
    }

    function module_mail_save_process(elem, callback)
    {
        const form = $('#' + $(elem).attr('form'))[0];
        $('.plusminus-model', form).remove();

        let formdata = new FormData(form);

        // $('[name-option="sender_option"]').each(function() { 
        //     formdata.append('sender_option[]', $(this).val()); 
        // });

        for(let recip of ['to', 'cc', 'cci']) {
            $('[name-option="' + recip + '_option"]').each(function() { 
                formdata.append(recip + '_option[]', $(this).val()); 
            });
        }
        
        $('[name-option="attachment_option"]').each(function() { 
            formdata.append('attachment_option[]', $(this).val()); 
        });
        formdata.append('message', $('#mailMessage', form).summernote('code'));
        formdata.append('signature', $('#mailSignature', form).html());

        $.ajax({
            url: "<?php echo base_url('mail/save');?>",
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: "post",
            dataType: 'json',
            success: function(result) 
            {
                callback(result);
            },
            error: function(data) {
                alert(data);
            }
        });       
    }

    function mail_module_emodel_init(elem, emodels)
    {
        if($(elem).val()) {
            for(emodel of emodels) {
                if(emodel.id_emodel==$(elem).val()) {
                    $('#mailSubject').val(emodel.subject);
                    $('#mailMessage').summernote('code', emodel.message);
                }
            }
        } else {
            $('#mailSubject').html('');
            $('#mailMessage').summernote('code', '');
        }

    }

    function mail_module_view_init()
    {
        $('.summernote', '#mailForm').each(function() {
            set_summernote(this);
        });
        $('.combobox', '#mailForm').each(function() {
            set_combobox(this);
        });
        $('.plusminus-group', '#mailForm').each(function() {
            // plusminus_transform(this);
            if($(this).attr('id') == 'mailAttachments') {
                $('.row-remove', this).hide();
                $('.row-add', this).hide();
            }
        });
    }

    function attachment_plusminus_process(elem)
    {
        if($(elem).val()) {
            const row = $(elem).closest('.plusminus-row');
            const group = $(elem).closest('.plusminus-group');
            $('.row-remove', row).show();
            $('.row-add', row).click();
            $('.row-add', group).hide();
            $('.plusminus-model', group).prev().find('.row-remove').hide();
        }
    }
    
</script>