<script>
    // ------------------------------------------------------------------
    // Checkbox like radio
    // ------------------------------------------------------------------
    $(document).on('click', '.checkbox-radio', function() {
        checkbox_radio(this);
    });
    
    function checkbox_radio(elem) {
        if($(elem).is(':checked')) {
            let name = $(elem).attr('name');
            $('.checkbox-radio[name="' + name + '"]').not(elem).each(function() {
                $(this).prop('checked', false);
            });
        }
    }
</script>