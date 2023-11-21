<script>
    // --------------------------
    // Ajax
    // --------------------------
    /* 
    * Author: Karol Butcher
    * v1 2022
    */

    jQuery(document).ready(function()
    {
        /**
         * Ajaxfication pager
         * 
         * Créer un page avec sur les <a>la class page_link_ajax
         * ajouter dans le container de recharge la classe container container_pager_ajax
         * 
         * Ne pas oublier le path pour l'adresse ajax sinon pger prend le path de l'uri de la page en cours
         *    $pager->setPath('controller/method');
         * 
         */

        $(document).on("submit",".form_link_ajax",function(e) 
        {
            // e.preventDefault();
            var url=$(this).attr("action");
            var itemSearch=$(this).find("input[name='itemSearch']").val();
            //alert(itemSearch);
            var dataString=$(this).serialize();
            var container=$(this).closest(".container_pager_ajax");
            //container
            container.fadeTo( "slow", 0.33 );
            container.find($(".table")).html('<div style="text-align:center" class="m-5 p-5"><i class="fas fa-circle-notch fa-spin"></i></div>');;
            jQuery.ajax
            ({  
                type:'GET',
                url: url,
                cache: false,
                data: dataString,
                //dataType:'json',

                success: function(html)
                { 
                    container.html(html);
                    container.fadeTo("slow",1);
                    initialize_banData(container);
                    $(".ajax_charge").val(url).trigger('change');

                    url = new URL(window.location);
                    url.searchParams.set('itemsearch', itemSearch);
                    window.history.pushState({}, '', url);
                    //alert();
                }
            });
            return false;
        });

        $(document).on("click",".page_link_ajax",function(e) 
        {
            //e.preventDefault();
            var url=$(this).attr("href");
            
            var container=$(this).closest(".container_pager_ajax");
            container.fadeTo( "slow", 0.33 );
            jQuery.ajax
            ({  
                type:'POST',
                url: url,
                cache: false,
                //dataType:'json',

                success: function(html)
                { 
                    //container.closest(".destroy_if_reload_ajax");
                    container.html(html);
                    container.fadeTo("slow",1);
                    initialize_banData(container);
                    $(".ajax_charge").val(url).trigger('change');
                // alert();
                }
            });
            return false;
        });
    });

    function ajax_html(url, formdata=null, callback=null)
    {
        $.ajax({
            url: url,
            type: 'POST',
            method: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType : 'html',
            data: formdata,
            cache: false,
            error: function(xhr, status, error){
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
            },
            success: function(result) {
                if(callback) callback(result);
            },
        });
    }

</script>