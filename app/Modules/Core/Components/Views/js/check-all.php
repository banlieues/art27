<script>

    // ------------------------------------------------------------------
    // Check and unckeck all
    // ------------------------------------------------------------------
    $(document).on('click', '.check-all-input', function() {
        check_all(this);
    });
    function check_all(elem) {
        const group = $(elem).closest('.check-all-group');
        const check_name = $(elem).attr('check-name');
        let checkbox;
        if(group.length > 0) {
            checkbox = $('.check-all-target', group);
            
        } else if(check_name.length > 0) {
            checkbox = $('.check-all-target[check-name="' + check_name + '"]');
        }
        $(checkbox).each(function() {
            if(
                ($(elem).prop('checked') === true && $(this).prop('checked') === false) ||
                ($(elem).prop('checked') === false && $(this).prop('checked') === true)
            ) $(this).trigger('click');
        });
    }

</script>