<script>
    // ------------------------------------------------------------------
    // Plusminus process
    // ------------------------------------------------------------------
    $(document).ready(function() {
        $('.plusminus-group').each(function() {
            plusminus_process(this)
        });

        $('.plusminus-model').each(function() {
            $(this).find('.datepicker[name]').each(function() {
                const fp = this._flatpickr;
                fp.destroy();
            });
        });
    });

    $(document).on('click', '.plusminus-add', function() {
        plusminus_row_add(this);
    });

    $(document).on('click', '.plusminus-remove', function() {
        plusminus_row_remove(this);
    });

    function plusminus_process(group)
    {
        $(group).each(function() {
            const row = $('.plusminus-row', group);
            let i = 0;
            $(row).each(function() {
                $(this).find('.plusminus-remove').removeClass('invisible');
                $(this).find('.plusminus-add').addClass('invisible');
                $(this).find('.plusminus-rank').text((i + 1) + '.');
                i++;
            });
            if(i==1) {
                $(row[0]).find('.plusminus-remove').addClass('invisible');
            }
            $(row[i-1]).find('.plusminus-add').last().removeClass('invisible');
        });
    }

    function plusminus_row_add(elem, callback=null) {
        let group;
        let model;
        if($(elem).attr('plusminus-target')) {
            model = $('#' + $(elem).attr('plusminus-target'));
            group = $(model).closest('.plusminus-group');
        } else {
            group = $(elem).closest('.plusminus-group');
            model = $(group).find('.plusminus-model');
        }
        const i = $(group).find('.plusminus-row').length;
        plusminus_model_clone(model, i, function() {
            if(callback) callback();
        });
    }

    function plusminus_model_clone(model, i, callback=null)
    {
        const group = $(model).closest('.plusminus-group');
        const clone = $(model).clone().removeAttr('id').removeClass('plusminus-model').addClass('plusminus-row');
        let html = $(clone).html();
        html = html.replaceAll('##i##', '##' + i + '##');
        $(clone).html(html);
        $(clone).find('input, select, textarea').attr('disabled', false);
        $(model).before(clone);
        $(clone).fadeIn(function() { 
            plusminus_process(group);
            if(callback) callback();
        });
    }

    function plusminus_row_remove(elem, callback=null)
    {
        const group = $(elem).closest('.plusminus-group');
        const row = $(elem).closest('.plusminus-row');
        $(row).fadeOut(function() {
            $(row).remove();
            plusminus_process(group);
            if(callback) callback();
        });
    }
</script>