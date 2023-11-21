<script>
    // --------------------------
    // Select
    // --------------------------
    $(document).ready(function() {
        $(".select_rs_chosen").chosen({
            disable_search_threshold: 10,
            search_contains: true,
            no_results_text: "Pas de résultats",
            width: "200px"
        });
    });

    // const deselect_present=document.querySelectorAll(".dselect");
    document.querySelectorAll(".dselect").forEach(function(ds)
    {
        const config = {
            search: true, // Toggle search feature. Default: false
            creatable: true, // Creatable selection. Default: false
            clearable: true, // Clearable selection. Default: false
            maxHeight: '360px', // Max height for showing scrollbar. Default: 360px
            size: 'sm', // Can be "sm" or "lg". Default ''
        };

        dselect(ds,config);
    });

    // ------------------------------------------------------------------
    // Bs Multiselect
    // ------------------------------------------------------------------
    function bs_multi_select(elem)
    {  
        if($(elem).hasClass('tags-block')) {
            $(elem).bsMultiSelect({
                cssPatch: {
                    picks: {
                        listStyleType:'none', 
                        display:'flex', 
                        flexDirection: 'column', 
                        height: 'auto', 
                        marginBottom: '0',
                    },
                    pick: {
                        marginRight: 'auto' 
                    },
                    choiceCheckBox: {
                        color: 'inherit', 
                        opacity: 1,
                    },
                    label_floating_lifted: {
                        opacity: '.65', 
                        transform : 'scale(.85) translateY(-.5rem) translateX(.15rem)',
                        zIndex: $.maxZIndex,
                    },
                },
                useChoicesDynamicStyling: true,
            });
        } else {
            $(elem).bsMultiSelect({
                useChoicesDynamicStyling: true,
            });
        }
        if(typeof $(elem).attr('multiple') == 'undefined' || $(elem).attr('multiple') == false) {
            $(elem).on('change', function() {
                const value = $(this).val();
                if($(this).val()) {
                    $(this).find('option').not($('option[value="' + value + '"]')).attr('selected', false);
                } else {
                    $(this).find('option').attr('selected', false);
                }
                $(this).bsMultiSelect('Update');
            });
        }
    }

    $(document).ready(function() {
        $('select.bs-multi-select').each(function() {
            bs_multi_select(this);
        });

        const observerInsertBsmultiselect = new MutationObserver(function(mutationList, observer) {
            for (const mutation of mutationList) {
                if (mutation.type === 'childList') {
                    for(node of mutation.addedNodes) {
                        if($(node).hasClass('.bs-multi-select')) {
                            bs_multi_select(node);
                        } else {
                            $(node).find('select.bs-multi-select').each(function() {
                                bs_multi_select(this);
                            });
                        }
                    };
                }
            }
        }).observe(document, { childList: true, subtree: true });
    });

    $(document).on('focusin', '.modal .dashboardcode-bsmultiselect ul', function() {
        $(this).closest('.modal-dialog').removeClass('modal-dialog-scrollable');
    });

    $(document).on('focusout', '.modal .dashboardcode-bsmultiselect ul', function() {
        $(this).closest('.modal-dialog').addClass('modal-dialog-scrollable');
    });

</script>