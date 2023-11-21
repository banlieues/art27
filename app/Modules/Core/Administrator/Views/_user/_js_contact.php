<script>

function contactView(elem, id_contact)
{
    const parent = $(elem).closest('.card');
    if($(elem).html().trim()=='<?php echo $themes->watch->icon;?>') {
        $(elem).html('<?php echo fontawesome('eye-slash');?>');
        $(elem).attr('title', "Masquer le(s) profil(s) du contact");
        $.get("<?php echo base_url('user/contact/view');?>" + '/' + id_contact, function(view) {
            $('.contactProfiles', parent).html(view);
        });
    } else {
        $(elem).html('<?php echo $themes->watch->icon;?>');
        $(elem).attr('title', "Voir le(s) profil(s) du contact");
        $('.contactProfiles', parent).html('');
    }

    jQuery(document).ready(function()
    {
        $(document).on("click","#submit_contacts_link",function(){ 
            $("#form_ajout_contacts").submit();
        });
    });

    /*Section en développement non utilisé, permet de charger formulaie en ajax*/
    jQuery(document).ready(function()
    {
        $(document).on("click",".btn_update_contact_user_BANDECONNECT",
            function()
            {
                var el=$(this);
                var container=el.closest(".card-body");
                var url=$(this).attr("href");
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                jQuery.ajax
                ({  
                    type:'POST',
                    url: url,
                    cache: false,
                 

                    success: function(html)
                    { 
                        container.html(html);
                    }
                });

                return false;

            })

    });
}

</script>