<script>
    // -------------------------------
    // Show/hide read/update form
    // -------------------------------
    /* 
    * Author: Karol Butcher
    * v1 2021
    */

    /** 1. J'ai besoin de savoir le descriptor du component base de données,  ban_components_contact, ou…… 2/Information pour faire la requête…… 3/avoir les id_primaire */
    function on_mode_edition()
    {
        $(".btn_contextuel_menu_form").hide();
        $("#message_new").hide();
    }

    function off_mode_edition()
    {
        $(".btn_contextuel_menu_form").show();
        $("#message_new").show();
    }

    jQuery(document).ready(function()
    {
        $(document).off("click", ".ban_modification_component").on("click", ".ban_modification_component", function()
        {
            on_mode_edition();
            $(this).html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');

            var dataString=null;

            var url=$(this).attr("href");

            var container_source=$(this).closest(".card");
            var container=$(this).closest(".card").find(".card-body");

            $("button[type=submit]",container).hide();
            $("form",container).not(".form_search_link").attr("id","form_component_actif");
            $(".btn_save_save").attr("form","form_component_actif");

            container.addClass("card_body_close");
            var el=$(this);

            $(".ban_modification_component").hide();
            $(".zone_button_mode_form").show();
            $(".zone_button_mode_form_hide").hide();
            
            $(".btn_contextuel_menu_form").hide();
            
            $(".ban_modification_component").addClass("disabled");
            
            el.after('<div class="card_form_components_btn"><button class="ban_modification_components_annuler btn btn-danger btn-sm">Annuler</button></div>');

            $(container).find(".view_components_read").hide();
            $(container).find(".view_components_update").show();

            $(".pas_tout_voir",container_source).hide();
            $(".tout_voir",container_source).hide();
            container.attr("style",null);

            var position=container_source.offset().top-100;
            /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
            et safari (webkit) */
            $('html, body')
            // on arrête toutes les animations en cours 
            .stop()
            /* on fait maintenant l'animation vers le haut (scrollTop) vers 
                notre ancre target */
            .animate({scrollTop: position}, 500 );

            //set_ssth();
            textarea_autosize();

            return false;
        });

        $(document).off("click", ".btn_save_save").on("click", ".btn_save_save", function(e)
        {

         
            e.preventDefault();

            var fields=$("#form_component_actif").serializeArray();
            var form=$("#form_component_actif");
            var is_error=0;
            
            jQuery.each( fields, function( i, field ) 
            {
                if(field.name!='indexesForm[]')
                {
                    let fieldname = field.name.split('[')[0];
                    var tr=$(".tr_fiche_"+fieldname,form);
                    if(tr.is(":visible"))
                    {
                        var label=$("label",tr).not(".label_checkbox");

                        var is_obligatoire=label.text().indexOf("*");
                        if(is_obligatoire!="-1")
                        {
                            if(field.value==="0" || field.value==="")
                            {
                                $(".error_form_verif").remove();

                                label.append('<small class="error_span" style="color:red"><br>Information obligatoire</small>');
                                is_error=1;
                            }
                        }
                    }
                }
            });
            
            if(is_error===1)
            {
                //alert("Erreur! Complétez les champs obligatoires précédés d'une *!"); 

                $("#modalViewTitle").html("Erreur lors de l'enregistrement du formulaire"); 
                $("#modalViewBody").html("<div class='text-danger'>Complétez les champs obligatoires précédés d'un *!</div>");

                $("#modalView").modal('show');

                return false; 
            }
            else
            {
                form.submit();
            }
        });

        $(document).off("click", ".ban_modification_components_annuler").on("click", ".ban_modification_components_annuler", function()
        {
            var container=$(this).closest(".card").find(".card-body");
            container.removeClass("card_body_close");
            container.show();
            $(".ban_modification_component").html("Modifier");
            $(".ban_modification_component").removeClass("disabled");
            $(".ban_modification_component").show();
        
            $(container).find(".view_components_read").show();
            $(container).find(".view_components_update").hide();

            $(".card_form_components").remove();
            $(".card_form_components_btn").remove();

            return false;
        });

        // commente par tamo sinon fait plante le script (recursion des submit)...
        // $(document).off("submit", ".form_gestion_entity").on("submit", ".form_gestion_entity", function()
        // {
        //     event.preventDefault();
            
        //     var form=$(this);
        //     $(".form-control",form).prop('disabled', false);
        //     form.submit();
        // });
        
        $(document).on("click", ".btn_save_insert", function()
        {
            let dataForm={};
            let list_key_primary={};
            var i=0;
            var dataString;

            var entity=$(this).attr("entity");

            $(".form_gestion_entity").each(function(){
                $(".form-control",$(this)).prop('disabled', false);
                dataString=$(this).serializeArray();
                dataForm[i]=dataString;
                i=i+1;
            });

            $.ajax({ 
                type: 'POST', 
                url: '<?=base_url()?>/'+entity+'/save_insert', 
                data: dataForm, 
                //dataType: 'json',
                //dataType: 'json',
                success: function (data) 
                { 
                //$("body").html(data);

                window.location.replace(data); //Redirection similaire à une redirection HTTP
                }
            });
            
            return false;
        });

        $(document).off("click", ".tout_voir").on("click", ".tout_voir", function()
        {
            var container=$(this).closest(".card");
            var body=container.find(".card-body");
            $(".tout_voir",container).hide();
            $(".pas_tout_voir",container).show();
            body.attr("style",null);
            var target = $(this).attr('href');
            go_to_anchor(container);
            // var position=container.offset().top-100;
            // /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
            // et safari (webkit) */
            // $('html, body')
            // // on arrête toutes les animations en cours 
            // .stop()
            // /* on fait maintenant l'animation vers le haut (scrollTop) vers 
            //     notre ancre target */
            // .animate({scrollTop: position}, 500 );

            return false;
        });

        $(document).off("click", ".pas_tout_voir").on("click", ".pas_tout_voir", function()
        {
            var container=$(this).closest(".card");
            var body=container.find(".card-body");
            $(".pas_tout_voir",container).hide();
            $(".tout_voir",container).show();
            body.attr("style","max-height: 40vh !important; overflow: auto");
            go_to_anchor(container);
            //     var position=container.offset().top-100;
            // /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
            // et safari (webkit) */
            // $('html, body')
            // // on arrête toutes les animations en cours 
            // .stop()
            // /* on fait maintenant l'animation vers le haut (scrollTop) vers 
            //     notre ancre target */
            // .animate({scrollTop: position}, 500 );

            return false;
        });

        $(document).off("click", ".btn_new_contact_profil").on("click", ".btn_new_contact_profil", function()
        {
            var new_contact_profil=$(".new_contact_profil");

            $(this).hide();
            new_contact_profil.show();

            $(".ban_modification_component").hide();
            $(".zone_button_mode_form").show();
            $(".zone_button_mode_form_hide").hide();
            
            $(".btn_contextuel_menu_form").hide();
            
            $(".ban_modification_component").addClass("disabled");

            var position=new_contact_profil.offset().top-100;
            /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
            et safari (webkit) */
            $('html, body')
            // on arrête toutes les animations en cours 
            .stop()
            /* on fait maintenant l'animation vers le haut (scrollTop) vers 
                notre ancre target */
            .animate({scrollTop: position}, 500 );

            return false;
        });
    
        //$(this).attr("style","");
    });

    function js_form_update(elem)
    {
        const form_id = $(elem).attr('form');
        if(form_id) {
            $('.form_read').show();
            $('.form_update').hide();
            $('.form_read[form="' + form_id + '"]').hide();
            $('.form_update[form="' + form_id + '"]').show();
            $('.form_update .form-control-plaintext[form="' + form_id + '"]').removeClass('form-control-plaintext').addClass('form-control');
            $('.form_update .form-control[form="' + form_id + '"]').not('.plusminus-model .form-control').removeAttr('disabled');
            // $('.form-control-plaintext[form="' + form_id + '"]').removeClass('form-control-plaintext').addClass('form-control');
            // $('.form-control[form="' + form_id + '"]').not('.plusminus-model .form-control').removeAttr('disabled');
        } else {
            $('.form_read').hide();
            $('.form_update').show();
            $('.form_update .form-control-plaintext').removeClass('form-control-plaintext').addClass('form-control');
            $('.form_update .form-control').not('.plusminus-model .form-control').removeAttr('disabled');
        }
    }
    
</script>