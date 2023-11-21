<script>
    // --------------------------
    // Datepicker
    // --------------------------
    function set_datepicker(elem)
    {
        // fp = elem._flatpickr;
        // console.log(fp);
        // if(fp) fp.destroy();

        elem.flatpickr({
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: 'fr',
        });

        // Inputmask("datetime", {
        //     placeholder: "dd/mm/yyyy",
        //     inputFormat: "dd/MM/yyyy"
        // }).mask(elem);
    }

    function set_timepicker(elem)
    {
        elem.flatpickr({
            allowInput: false,
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: 'fr',
        });

    /* Inputmask("datetime", {
                placeholder: "H:mm",
                inputFormat: "H:m"
            
            }).mask(elem);*/
    
    }

    $(document).ready(function()
    {
        $('.datepicker').each(function() { 
            set_datepicker(this);
        });
        observerInsertDatepicker();
    });

    function observerInsertDatepicker()
    {
        const observerInsertDatepicker = new MutationObserver(function(mutationList, observer) {
            for (const mutation of mutationList) {
                if (mutation.type === 'childList') {
                    for(node of mutation.addedNodes) {
                        if($(node).hasClass('.datepicker')) {
                            set_datepicker(node);
                        } else {
                            $(node).find('.datepicker').each(function() {
                                set_datepicker(this);
                            });
                        }
                    };
                }
            }
        }).observe(document, { childList: true, subtree: true });
    }

    // function observerStyleDatepicker(elem)
    // {
    //     const observerStyleDatepicker = new MutationObserver(function(mutationList, observer) {
    //         for (const mutation of mutationList) {
    //             if($(mutation.target).css('display')!='none' || !$(mutation.target).css('display')) {
    //                 if($(mutation.target).hasClass('.datepicker') && $(mutation.target).is(':visible') && $(mutation.target).attr('type')=='text') {
    //                     console.log("3");
    //                     console.log($(mutation.target));
    //                     set_datepicker(mutation.target);
    //                 }
    //                 else {
    //                     $(mutation.target).find('.datepicker[type="text"]:visible').each(function() {
    //                         console.log('4');
    //                         console.log($(this));
    //                         set_datepicker(this);
    //                     });
    //                 }
    //             }
    //         }
    //     }).observe(elem, { attributes: true, attributeFilter: ['style'], });
    // }

</script>