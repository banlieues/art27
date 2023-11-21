/* 
 * Author: Karol Butcher
 * v1 2021
 */


/** 1. J'ai besoin de savoir le descriptor du component base de données,  ban_components_contact, ou…… 2/Information pour faire la requête…… 3/avoir les id_primaire */
jQuery(document).ready(function()
{
  $(document).off("click", ".ban_modification_component").on("click", ".ban_modification_component", function()
  {
      $(this).html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');

      var dataString=null;

      var url=$(this).attr("href");

      var container=$(this).closest(".card").find(".card-body");
      container.addClass("card_body_close");
      var el=$(this);

      $(".ban_modification_component").hide();
      $(".ban_modification_component").addClass("disabled");
      
     el.after('<div class="card_form_components_btn"><button class="ban_modification_components_annuler btn btn-danger btn-sm">Annuler</button></div>');

      $(container).find(".view_components_read").hide();
      $(container).find(".view_components_update").show();

     /* jQuery.ajax
        ({	
                type:'POST',
                data:dataString,
                url: url,
                cache: false,
                success: function(html)
                { 
                    $(".ban_modification_component").hide();
                    $(".ban_modification_component").addClass("disabled");
                    $(container).hide();
                    $(container).after('<div class="card-body card_form_components">'+html+'</div>');
                    el.after('<div class="card_form_components_btn"><button class="btn btn-success btn-sm">Enregister</button> <button class="ban_modification_components_annuler btn btn-danger btn-sm">Annuler</button></div>');
                }

        }); */

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
  
});
