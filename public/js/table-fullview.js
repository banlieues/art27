<script>
    // --------------------------
    // Calculate table height for sticky header
    // --------------------------
    $(document).ready(function() {
        $('.table-fullview').each(function() {
            get_available_height(this);
        });
    });

    function get_available_height(elem)
    {
        let height = window.innerHeight;
        if($('header').length) height -= $('header').outerHeight();
        // if($('.title-container').length) height -= $('.title-container').outerHeight();
        // if($('ul.nav').length) height -= $('ul.nav').outerHeight();
        height -= $(elem).find('thead').outerHeight();
        // height -= convertRemToPixels(1.5);
        // height -= convertRemToPixels(1.5);
        if($('footer.footer').length) height -= $('footer.footer').outerHeight();
        // console.log('total height : ' + height);

        $(elem).css('max-height', height + 'px');
    }

    function convertRemToPixels(rem) {    
        return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
    }
</script>