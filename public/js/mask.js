<script>
    // --------------------------
    // Spinner mask
    // --------------------------
    if(typeof spinner_mask != 'function') {
        function spinner_mask(elem, message=null)
        {
            if(!message) message = '';
            const mask = '<div class="row justify-content-center text-center p-4"> <div class="col-12"> <?php echo fontawesome('spinner');?> </div><div class="col-12"><small>' + message + '</small></div></div>';
            $(elem).html(mask);
        }
    }

    // --------------------------
    // Waiting mask
    // --------------------------
    if(typeof waiting_start != 'function') {
        let waiting_title = '';
        function waiting_start(elem) 
        {
            waiting_title = $(elem).html();
            let fix_height = $(elem).height();
            let fix_width = $(elem).width();
            $(elem).height(fix_height).width(fix_width).html('<?php echo fontawesome('spinner');?>');
        }
        function waiting_end(elem)
        {
            $(elem).html(waiting_title);
        }
    }

    // --------------------------
    // Waiting mask export
    // --------------------------
    if(typeof waiting_start_export != 'function') {
        function waiting_start_export(elem) 
        {
            waiting_start(elem);
            $(window).blur(function() {
                waiting_end(elem);
                $(".loading_layer").hide();
            });
        }
    }

</script>