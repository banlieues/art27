<script>
// -------------------------------
// JS_FEATURE_TAG
// -------------------------------

if (typeof road_tag_to_checkbox != 'function') {
    function road_tag_to_checkbox(elem)
    {
        const group = $(elem).closest('.form-control-group');
        const collapse = $('.collapse', group);

        let ids_selected = $(elem).val();
        if(typeof ids_selected === 'string') ids_selected = [ids_selected];
        $(':radio, :checkbox', collapse).prop('checked', false);
        for(let id_road of ids_selected) {
            $(':checkbox[value="' + id_road + '"], :radio[value="' + id_road + '"]', collapse).prop('checked', true);
        }
    }
}

if (typeof road_checkbox_to_tag != 'function') {
    function road_checkbox_to_tag(elem)
    {
        const group = $(elem).closest('.form-control-group');
        const select = $('select', group)[0];
        $(select).find('option').prop('selected', false);
    
        const checked = $(':checkbox:visible:checked, :radio:visible:checked', group);
        $(checked).each(function() {
            const id_road = $(this).val();
            $('option[value="' + id_road + '"]', select).prop('selected', true);
        });
        $(select).bsMultiSelect("Update");
    }
}

if (typeof tag_button_alternate != 'function') {
    function tag_button_alternate(elem)
    {
        let parent = $(elem).closest('.form-control-group');
        let offset = $(parent).offset().top - $('header').outerHeight();
        $(elem).fadeOut(function() {
            $('button', parent).not(elem).fadeIn(function() {
                if($(elem).hasClass('tag-button-hide-list')) {
                    $('html, body').animate({
                        scrollTop: offset,
                    }, 'slow');
                }
            });
            
        });
    }
}

</script>