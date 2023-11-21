<script type="text/javascript">
$('input, select').not('input[name="suggestions"]').not('input[type="checkbox"]').each(function() {
    $(this).attr('required', true);
});

$('input, select').on('change', function() {
    const name = $(this).attr('name');
    $('input[name="' + name + '"], select[name="' + name + '"]').removeClass('is-invalid');
});

function js_enquete_submit_with_validation(elem) 
{
    const form = elem.closest('form');
    $('input.slider-input').attr('readonly', false);
    if (form.checkValidity() === true) {
        $(form).submit();
    } else {
        $('input').each(function() {
            const name = $(this).attr('name');
            if(
                ($(this).attr('type')==='radio' && $('input[name="' + name + '"]:checked').length==0) ||
                ($(this).hasClass('slider-input') && !$(this).val())
            ) $(this).addClass('is-invalid');
        });
        $('select').each(function() {
            if(!$(this).val()) $(this).addClass('is-invalid');
        });
        $('#formMissingValues').attr('hidden', false);
        $('#formErrorValues').attr('hidden', true);
        $(window).scrollTop(0);
    }
    $('input.slider-input').attr('readonly', true);
}

$(document).ready(function() {
    $('.slider-group').each(function(){
        let namelang = $(this).attr('id');
        js_question_note_slider(namelang);
    });
});
$(document).on('show.bs.modal', '#modal', function () {
    $('.slider-group').each(function(){
        let namelang = $(this).attr('id');
        js_question_note_slider(namelang);
    });
});
function js_question_note_slider(namelang)
{
    const handle = $("#handle_" + namelang);
    const slider = $("#slider_" + namelang);
    const group = $(slider).closest('.slider-group');
    $( "#slider_" + namelang).slider({
        value: 5,
        min: 0,
        max: 10,
        step: 1,
        create: function( event, ui ) {
            $(handle).css({
                'width' : '3em',
                'height' : '1.6em',
                'top' : '50%',
                'margin-top' : '-.8em',
                'text-align' : 'center',
                'line-height' : '1.6em',
            });
            $(slider).css('max-width', '500px');
        },
        slide: function( event, ui ) {
            handle.text( ui.value );
            $("#input_" + namelang, group).val( ui.value).trigger("change");
        }
    });
}

function js_disable_slider(elem)
{
    let target = $(elem).attr('data-target');
    let group = $(elem).closest('.slider-group');
    if($(elem).prop('checked') == true) {
        $( '#slider_' + target, group).slider('destroy');
        $('input#input_' + target, group).attr('hidden', true);
        $('#input_' + target, group).removeClass('is-invalid');
        $('#input_' + target, group).attr('required', false);
        $('#input_' + target, group).val('');
    } else {
        $('#input_' + target).prop('required', true);
        js_question_note_slider(target);
        $('input#input_' + target).attr('hidden', false);
    }
}
</script>