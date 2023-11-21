<script>
    // -------------------------------
    // Translator functions
    // -------------------------------
    function translator_save(elem, id_transl)
    {
        const form = $('#' + $(elem).attr('form'));
        const data = $(form).serializeArray();
        
        const url = "<?php echo base_url("translator/row");?>/" + id_transl + "/save";
        $.post(url, data, function(row) {
            row = JSON.parse(row);
            if(!row.invalid_fields) {  
                $('#modal').modal('hide');
                const lang = "<?php echo LanguageSessionGet();?>";
                if(lang=='bi') {
                    if(row['label_fr']==row['label_nl']) {
                        $('.box-translator[id_transl="' + row['id_transl'] + '"][lang="nl"]').outerHtml('');
                    } else {
                        $('.box-translator[id_transl="' + row['id_transl'] + '"][lang="nl"]').outerHtml(row['label_nl']);
                    }
                } else {
                    if(row['label_' + lang] && row['label_' + lang].length > 0) {
                        $('.box-translator[id_transl="' + row['id_transl'] + '"][lang="' + lang + '"]').outerHtml(row['label_' + lang]);
                    }
                }
            } else {
                console.log(row.invalid_fields);
            }

            
        })
    }
    $('.btn-translator').on('click', function(e) {
        modal_translator(e, this);
        return false;
    });
    function modal_translator(e, elem)
    {
        e.preventDefault();
        const id_transl = $(elem).parent('.box-translator').attr('id_transl');
        const url = window.location.origin + '/translator/row/' + id_transl + '/modal';
        ajax_modal(url);
    }

    function language_set(locale)
    {
        $.get(window.location.origin + '/language/set/' + locale, function() {
            window.location.reload();
        });
    }
</script>