
<script>

function remonte_page(container)
{
  $("html,body").animate({scrollTop: container.offset().top-150}, 'slow');
}

function remonte_initialize()
{
  var container=$("#top_fiche");
  $("html,body").animate({scrollTop: container.offset().top-200}, 'slow');

}



function load_document()
{
    var container=$("#container_document");

    container.hide();
    container.parent().find(".loading").show();

    //var id_demande=container.attr("id_demande");

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





              const options = {
                keyboard: true,
                constrain: true,
                
               
              };

              

              document.querySelectorAll('.my-lightbox-toggle').forEach((el) => el.addEventListener('click', (e) => {
                e.preventDefault();
                const lightbox = new Lightbox(el, options);
                lightbox.show();
              }));

            }

    }); 

    
}


function gerer_document_modal(entity,id_entity)
      {
        $(".container_GererDocumentModalCRM").html('<div class="text-center m-4"><i class="fa fa-circle-notch fa-spin"></i><br>Chargement</div>');
            
            var url= "<?=base_url()?>document/listDocument/"+entity+"/"+id_entity;

            

            jQuery.ajax
            ({	
                type:'POST',
                url: url,
                cache: false,
                success: function(html)
                { 
                  
                  $(".container_GererDocumentModalCRM").html(html);
              
                    //alert(html);

                }

            }); 


            $("#GererDocumentModalCRM").modal
            ({
              keyboard: false,
              backdrop: 'static'

            });

            $("#GererDocumentModalCRM").modal("show");
      }



jQuery(document).ready(function()
{
   
    load_document();
      //modal pour gérer les documents de la demande
     

      $(document).off("click",".gerer_document_modal").on("click",".gerer_document_modal", function(e){
     
            var entity=$(this).attr("entity");
            var id_entity=$(this).attr("id_entity");

            gerer_document_modal(entity,id_entity);



        });

        $(document).off("click",".close_GererDocumentModalCRM").on("click",".close_GererDocumentModalCRM", function(e){
              
              $("#GererDocumentModalCRM").modal("hide");
              $(".container_GererDocumentModalCRM").html(null);

              $(".modal").modal("hide");

              load_document();
      
            });


      //ici modal pour ajout de documents prexistant dans la boîte de dépot d'une demande
      $(document).off("click",".ajouter_document_modal_crm").on("click",".ajouter_document_modal_crm", function(e){
     
                  var id_demande=$(this).attr("id_demande");
                      //alert(id_demande);
                  $(".container_AjouterDocumentModalCRM").html('<div class="text-center m-4"><i class="fa fa-circle-notch fa-spin"></i><br>Chargement</div>');
                  $("#id_demande_AjouterDocumentModalCRM").html(id_demande);
                  var url= "<?=base_url()?>demande/liste_document_ajouter_demande/"+id_demande;

                  

                  jQuery.ajax
                  ({	
                      type:'POST',
                      url: url,
                      cache: false,
                      success: function(html)
                      { 
                        
                        $(".container_AjouterDocumentModalCRM").html(html);
                    
                          //alert(html);

                      }

                  }); 


                  $("#AjouterDocumentModalCRM").modal
                  ({
                    keyboard: false,
                    backdrop: 'static'

                  });
                  $("#AjouterDocumentModalCRM").modal("show");



      });


      $(document).off("click",".close_AjouterDocumentModalCRM").on("click",".close_AjouterDocumentModalCRM", function(e){
              
              $("#AjouterDocumentModalCRM").modal("hide");
              $(".container_AjouterDocumentModalCRM").html(null);
                    
      
            });

     

      $(document).off("click",".set_ajouter_document_demande").on("click",".set_ajouter_document_demande", function(e){
              


                var url=$(this).attr("href");
                var container=$(this).closest(".td_set_ajouter_document_demande");

                container.html('<i class="fa fa-circle-notch fa-spin"></i>');

                jQuery.ajax
                  ({	
                      type:'POST',
                      url: url,
                      cache: false,
                      success: function(html)
                      { 
                        
                       container.html(html);
                    
                          //alert(html);
                          load_document();
                     

                      }

                  }); 
      
      
            });
      
      
      
      //ICi on s'occupe des dépots et des documents

      $(document).off("click",".ajouter_document_modal").on("click",".ajouter_document_modal", function(e){
     
        $("#GererDocumentModalCRM").modal("hide");
              $(".container_GererDocumentModalCRM").html(null);
       
        var entity=$(this).attr("entity");
        var id_entity=$(this).attr("id_entity")

        $(".drop_zone_entity").val(entity);
        $(".drop_zone_id_entity").val(id_entity);

        $("#AjouterDocumentModal").modal({
          keyboard: false,
          backdrop: 'static'
  
      });
        $("#AjouterDocumentModal").modal("show");
  
        return false;
      });
  
      $(document).off("click",".btn_submit_upload").on("click",".btn_submit_upload", function(e){
  
        $("#my-dropzone").submit();
  
        return false;
      });
  
     
      
      $(document).off("click",".close_AjouterRdvModal").on("click",".close_AjouterRdvModal", function(e){
        
        $("#AjouterDocumentModal").modal("hide");
       if($("#reload_page_document").length)
       {
          location.reload(true);
       }
       else
       {
        load_document();
      
        var entity=$(".ajouter_document_modal").attr("entity");
        var id_entity=$(".ajouter_document_modal").attr("id_entity");

        gerer_document_modal(entity,id_entity);


       }
      

       


       
      
       // load_reload_page_document();
      
       
       
        return false;
      });


   


     

     

   
      
    

      
    
      
});
</script>