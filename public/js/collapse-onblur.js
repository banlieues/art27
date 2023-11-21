<script>
    // ------------------------------------------------------------------
    // Close collapse when click outside
    // ------------------------------------------------------------------
    $(document).on('click', function(e) {
        $('.collapse-click-outside').each(function() {
            if(!$(this).is(e.target) && $(this).has(e.target).length === 0) $(this).collapse('hide');
        });
    });
</script>