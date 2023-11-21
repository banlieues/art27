<script>
    // --------------------------
    // Sticky
    // --------------------------
    $(document).ready(function() {
        $('.sticky_header').each(function() { set_sticky_header(this);});
    });
    function set_sticky_header(elem)
    {
        $(elem).css({
            backgroundColor: 'white',
            zIndex: 999,
        });
        $(elem).stick_in_parent();
    }


    $(document).ready(function() 
    {
        $('.sticky_button').each(function() {
            set_sticky_button(this); 
        });

        const observerInsertSticky = new MutationObserver(function(mutationList, observer) {
            for (const mutation of mutationList) {
                if (mutation.type === 'childList') {
                    for(node of mutation.addedNodes) {
                        if($(node).hasClass('sticky_button')) {
                            set_sticky_button(node);
                        } else {
                            $(node).find('.sticky_button').each(function() {
                                set_sticky_button(this);
                            });
                        }
                    };
                }
            }
        }).observe(document, { childList: true, subtree: true });
    });

    function set_sticky_button(elem)
    {
        const observerSticky = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if($(mutation.target).css('position')!='fixed') {
                    $(mutation.target).removeClass('is_stuck');
                } else {
                    $(mutation.target).addClass('is_stuck');
                }
            });
        }).observe(elem, { attributes: true, attributeFilter: ['style'] });

        $(elem).stick_in_parent({
            offset_top : $('header').outerHeight() + get_sticky_offset(elem),
        });
    }

    function get_sticky_offset(elem)
    {
        let height = 0;
        if($(elem).prevAll('.sticky_button').length>0) {
            height += $(elem).prevAll('.sticky_button').outerHeight();
        }
        $(elem).parentsUntil('main').each(function() {
            if($(this).prevAll('.sticky_button').length>0) {
                height += $(this).prevAll('.sticky_button').outerHeight();
            };
        });

        return height;
    }

</script>