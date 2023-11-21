<script>
    // ------------------------------------------------------------------
    // Dynamic margin-top for anchor when click on menu to appear under top banner
    // ------------------------------------------------------------------

    $(document).on('click', 'a[href^="#"]', function(e) {
        e.preventDefault();
        const anchor_id = $(this).attr('href');
        if(anchor_id!="#")
        {
            go_to_anchor_id(anchor_id);
        }
    });

    function go_to_anchor_id(anchor_id)
    {
        const anchor = $('#' + anchor_id);
        go_to_anchor(anchor);
    }

    function go_to_anchor(anchor)
    {
        const headerHeight = $('header').outerHeight();
        const sticky_height = get_sticky_offset($(anchor));
        // const anchor_top = $(anchor).position().top;
        const anchor_offset = $(anchor).offset().top;
        $(document).scrollTop(anchor_offset - headerHeight - sticky_height);
    }

</script>