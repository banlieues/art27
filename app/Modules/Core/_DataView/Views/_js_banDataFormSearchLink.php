<script>


jQuery(document).ready(function()
{
  
 
  $(document).on("click", ".btn_search_link", function()
  {
    event.preventDefault();  
   
    
      var container=$(this).closest(".form_search_link");
        var container_result=container.find(".form_search_link_result");

        var url=container.attr("action");

        container_result.html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>').show();

        var itemSearch=$(".itemSearch",container).val();
        var dataString="itemSearch="+itemSearch;
   
//alert(url);
        jQuery.ajax
        ({	
                type:'POST',
                data:dataString,
                url: url,
                cache: false,
                success: function(html)
                { 
                    container_result.html(html);
                }

        }); 
        
        return false;

    

  });

  $(document).on("keyUp", ".itemSearch", function()
  {
    
    event.preventDefault();  
   
    
      var container=$(this).closest(".form_search_link");
        var container_result=container.find(".form_search_link_result");

        var url=container.attr("action");

        container_result.html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>').show();

        var itemSearch=$(".itemSearch",container).val();
        var dataString="itemSearch="+itemSearch;
   

        jQuery.ajax
        ({	
                type:'POST',
                data:dataString,
                url: url,
                cache: false,
                success: function(html)
                { 
                    container_result.html(html);
                }

        }); 
        
        return false;

    

  });

  
  $(document).off("click", ".btn_copy_index_field").on("click", ".btn_copy_index_field", function()
  {
      
        var container=$(this).closest(".container_form_search_link");
        var container_data=$(this).closest(".card");
        var container_result=container.find(".form_search_link_result");
        var container_form_gestion_entity=container.closest(".form_gestion_entity");
        var container_form_gestion_entity=container_result;
//alert();
        //container_form_gestion_entity.hide();
        $(".copy_index_field",container_data).each(
          
          function()
          {
            var index=$(this).attr("name");
            var value=$(this).attr("value");
          // alert(index+"="+value);
          var type=$("input[name='"+index+"']").attr("type");

          if(type=="radio"||type=="checkbox")
          {
            $("input[name='"+index+"']").each(function() {

                $(this).prop("checked",false);
                $(this).removeClass("hasCheck");

                if($(this).val()==value)
                {
                    $(this).prop("checked",true);

                    if(type=="radio")
                    {
                      $(this).addClass("hasCheck");
                    }
                } 

            });
          }
          else
          {
            $("input[name='"+index+"']").val(null);
            $("input[name='"+index+"']").val(value);
          }


          $("select[name='"+index+"']").val(null);
            
            $("select[name='"+index+"']").val(value);


          }
        );

        $("input[name='prenom_personne']").prop("disabled",true);
        $("input[name='nom_personne']").prop("disabled",true);

        
        
        var container_result=container.find(".form_search_link_result");
        container_result.html('');
        container_result.hide();

        $("#biens_du_demandeur").html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');
        //$("#containeur_demandeurs_possible").html(null);

        if($("#create_demande_context").length)
        {

          var id_contact=$("input[name='id_contact']").val();
           var id_contact_profil=$("input[name='id_contact_profil']").val();
           //alert(id_contact+'-'+id_contact_profil);
           var adresse="<?php echo base_url();?>/bien/search_bien_by_id_contact/"+id_contact+"/"+id_contact_profil;
          // alert(adresse);
            jQuery.ajax
                    ({	
                      type:'POST',
                      url: adresse,
                     
                      
                      cache: false,
                      success: function(html)
                      {
                      
                      $("#biens_du_demandeur").html(html);
                        
                    
                      }	
                    })
        }
        
        return false;

    

  });


 
  
});
</script>
