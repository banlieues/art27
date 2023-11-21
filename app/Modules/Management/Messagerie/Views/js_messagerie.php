
<script>

    


function remonte_page(container)
{
  $("html,body").animate({scrollTop: container.offset().top-150}, 'slow');
}



function load_note_create(url)
{
    remonte_page( $("#top_note_create"));
    var container=$("#top_note_create");
    container.show();
    var container_form=$("#container_form_note");
    container_form.html('<div class="text-center m-4"><i class="fa fa-circle-notch fa-spin"></i><br>Chargement</div>');
    jQuery.ajax
      ({	
              type:'POST',
              url: url,
              cache: false,
              success: function(html)
              { 
                container_form.html(html);
              }
      })
}


function load_note()
{
    var container=$("#container_note");

    container.hide();
    container.parent().find(".loading").show();

    var id_demande=container.attr("id_demande");

    var url=container.attr("href_ajax");

    jQuery.ajax
    ({	
            type:'POST',
            url: url,
            cache: false,
            success: function(html)
            { 
              container.parent().find(".loading").hide();
              container.html(html).show();
            }

    }); 
}
    






jQuery(document).ready(function()
{
  <?php if(isset($notes_non_lues)&&!is_null($notes_non_lues)):?>
    $("#modalViewTitle").html("Note(s) non lue(s)");
    $("#modalViewBody").html($("#provisoire_note").html());
    $("#modalView").modal('show');
    $("#provisoire_note").html(null);

    var url="<?=base_url()?>messagerie/set_lu_entity";
    var dataString=$("#form_id_lu").serialize();
    


    jQuery.ajax
    ({
                type:'POST',
                url: url,
                data: dataString,
                cache: false,
                success: function()
                { 
                    refresh_message_nonlus();
		                refresh_message_nolus_affiche();
                }
          
    });



<?php endif;?>



    load_note();


      $(document).on("click","#ajouter_note",function() {

         $(".zone-button-form-note").show();
          $("#top_note").show();
          on_mode_edition();
          load_note_create($(this).attr("href"));

          return false;
        });


        $(document).on("click","#note_new_cancel",function() {
        //alert(a_mail);
        remonte_page( $("#top_note_create"));
        $("#container_form_note").html();
        off_mode_edition();

        $("#top_note_create").hide();
        remonte_page($('#liste_note'));
        $(".zone-button-form-note").hide();
        return false;
        //$(this).hide();
      }); 

      $(document).on("click","#bt_form_note",function()
      {
       
        var form=$("#form_note");
        $("#loading_layer").show();
        var url=form.attr("action");
        var dataString=form.serialize();

        var is_error=verif_error_form(form);

        if(is_error==true)
        {

          remonte_page( $("#top_note_create"));

          $("#loading_layer").hide();

          return false;
        }

        jQuery.ajax
          ({	
              type:'POST',
              url: url,
              data: dataString,
              cache: false,
              success: function(html)
              { 
                if(is_error==false)
                {
                  $("#top_note_create").hide();
                  $("#loading_layer").hide();
                  $("#container_form_note").html(null);
                  $(".zone-button-form-note").hide();
                  load_note();
                  remonte_page($("#liste_note"));
                  off_mode_edition();
                }
               
              }

          }); 

          return false;
          
      });
 

    
      
});
</script>