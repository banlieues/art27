<script>
    // -------------------------------
    // Textarea autosize
    // -------------------------------
    $(document).ready(function() {
        textarea_autosize();
    });
    $('a.nav-item').on('shown.bs.tab', function (e) {
        textarea_autosize();
    });
    $('#modal').on('shown.bs.tab', function (e) {
        textarea_autosize();
    });
    function textarea_autosize()
    {
        $('textarea').each(function () {
            const fontSize = parseInt($(this).css('font-size'));
            const lineHeight = Math.floor(parseInt(fontSize) * 2);
            let nbLinesMin = 1;
            if($(this).attr('rows')) { nbLinesMin = $(this).attr('rows'); }
            minHeight = lineHeight*nbLinesMin;
            if(this.scrollHeight > minHeight) {
                this.style.height = this.scrollHeight + 'px';
            } else {
                this.style.height = minHeight + 'px';
            }
            $(this).on('focus', function () {
                $(this).css('overflow', 'hidden');
                if(this.scrollHeight > minHeight) {
                    this.style.height = this.scrollHeight + 'px';
                } else {
                    this.style.height = minHeight + 'px';
                }
            });
        });
    }

    // function textarea_autosize()
    // {
    //     $.each($('textarea'), function() 
    //     {
    //         var offset = this.offsetHeight - this.clientHeight;
    //         var resizeTextarea = function(e) {
    //             $(e).css('height', 'auto').css('height', e.scrollHeight + offset);
    //         };
    //         $(this).on('focus', function() { resizeTextarea(this); });
    //         resizeTextarea(this);
    //     });
    // }
</script>