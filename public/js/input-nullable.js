<script>
    
// ------------------------------------------------------------------
// Set input boolean nullable
// ------------------------------------------------------------------
$(document).ready(function() {
    $('.input-nullable').each(function() {
        input_nullable_process(this);
    });
    const observerInsertInputNullable = new MutationObserver(function(mutationList, observer) {
        for (const mutation of mutationList) {
            if (mutation.type === 'childList') {
                for(node of mutation.addedNodes) {
                    if($(node).hasClass('.input-nullable')) {
                        input_nullable_process(node);
                    } else {
                        $(node).find('.input-nullable').each(function() {
                            input_nullable_process(this);
                        });
                    }
                };
            }
        }
    }).observe(document, { childList: true, subtree: true });
});

function input_nullable_process(elem)
{
    $(elem).addClass('checkbox-radio');
    $(elem).after('<input type="checkbox" class="checkbox-radio" form="' + $(elem).attr('form') + '" value="0" name="' + $(elem).attr('name') + '" style="display: none;"/>');

    const input_hidden = $('input.checkbox-radio[name="' + $(elem).attr('name') + '"]:hidden');
    if($(elem).is(':disabled')) $(input_hidden).attr('disabled', true);
}

$(document).on('click', '.input-nullable', function()
{
    const input_hidden = $('input.checkbox-radio[type="checkbox"][name="' + $(this).attr('name') + '"]:hidden');
    if($(this).is(':checked')) $(input_hidden).prop('checked', false);
    else $(input_hidden).prop('checked', true);
});

</script>