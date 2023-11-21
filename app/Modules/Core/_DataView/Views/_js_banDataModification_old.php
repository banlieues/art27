<script>
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
      $(".zone_button_mode_form").show();
      $(".zone_button_mode_form_hide").hide();
      
      $(".btn_contextuel_menu_form").hide();
      
      $(".ban_modification_component").addClass("disabled");
      
     el.after('<div class="card_form_components_btn"><button class="ban_modification_components_annuler btn btn-danger btn-sm">Annuler</button></div>');

      $(container).find(".view_components_read").hide();
      $(container).find(".view_components_update").show();

      return false;

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

  $(document).off("submit", ".form_gestion_entity").on("submit", ".form_gestion_entity", function()
  {
    //event.preventDefault();
    var form=$(this);
    $(".form-control",form).prop('disabled', false);
    form.submit();
  });
  
  $(document).off("click", ".btn_save_insert").on("click", ".btn_save_insert", function()
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
  
});
</script>