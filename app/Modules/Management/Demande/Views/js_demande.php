
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



function reset_all_form_message()
{
    $("#form_a_mail").val(null);
    $("#form_cc").val(null);
    $("#form_cci").val(null);
    $("#form_sujet").val(null);

    $("#form_body").summernote('code',null);

    $("#loading_send").hide();

    $(".output_send_message").html("");



}



function load_reload_page_document()
{
 
    if($(".select_change_submit").hasClass("load_reload_page_document"))
    {
      location.reload(true);
    }
}

/*function load_note_create(url)
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
}*/

function load_rdv_create(url)
{
      remonte_page( $("#top_rdv"));
      var container=$("#container_rdv_create");

      container.hide();
      container.parent().find(".loading").show();

      var id_demande=container.attr("id_demande");

      //var url=container.attr("href_ajax");

      jQuery.ajax
      (
        {	
              type:'POST',
              url: url,
              cache: false,
              success: function(html)
              { 
                container.parent().find(".loading").hide();
                container.html(html).fadeIn();
               
                // $(".datepicker",container).each(function(){
                //     set_datepicker(this);
                // })
               
                set_timepicker( $("input[name='temp_avant_rdv']"));
                set_timepicker( $("input[name='temp_apres_rdv']"));

                set_timepicker( $("input[name='date_rdv_debut_h']"));

                set_timepicker( $("input[name='date_rdv_fin_h']"));

                $(".zone-button-form-rdv").fadeIn();

                $.each($('textarea'), function() 
                {
                    var offset = this.offsetHeight - this.clientHeight;
                    var resizeTextarea = function(e) {
                        $(e).css('height', 'auto').css('height', e.scrollHeight + offset);
                    };
                    $(this).on('keyup input', function() { resizeTextarea(this); });
                    resizeTextarea(this);
                });
               
              }

      }
    ); 
}

function load_tache_create(url)
{
      remonte_page( $("#top_tache"));
      var container=$("#container_tache_create");

      container.hide();
      container.parent().find(".loading").show();

      var id_demande=container.attr("id_demande");

      //var url=container.attr("href_ajax");

      jQuery.ajax
      ({	
              type:'POST',
              url: url,
              cache: false,
              success: function(html)
              { 
                container.parent().find(".loading").hide();
                container.html(html).fadeIn();
               
                // $(".datepicker",container).each(function(){
                //     set_datepicker(this);
                // })
               
               

                set_timepicker( $("input[name='date_tache_h']"));

                $(".zone-button-form-tache").fadeIn();

                $.each($('textarea'), function() 
                {
                    var offset = this.offsetHeight - this.clientHeight;
                    var resizeTextarea = function(e) {
                        $(e).css('height', 'auto').css('height', e.scrollHeight + offset);
                    };
                    $(this).on('keyup input', function() { resizeTextarea(this); });
                    resizeTextarea(this);
                });
               
              }

    });
  }

function load_fil(isprogressif=0)
{
    var container=$("#container_fil");

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
             
              $("#top_message").hide();
              reset_all_form_message();
              $(".bt_redaction_message").show();

             <?php if(isset($id_email_primary)&&$id_email_primary>0):?>
                var b = document.getElementById('container_fil_message');
             // b.scrollTop = document.getElementById('266657').offsetTop - b.offsetTop;
            
              //var position=$("#266657").offset().top-100;
                   /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
                   et safari (webkit) */
                $('#container_fil_message').stop().animate({scrollTop: document.getElementById('<?=$id_email_primary?>').offsetTop - b.offsetTop}, 500 );
              <?php endif;?>
             
              if(isprogressif==1)
              {
                load_document();
                load_rdv();
                load_tache();

              }
            }

    }); 
    
}

function load_document()
{
    var container=$("#container_document");

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


function load_rdv()
{
    var container=$("#container_rdv");

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


    
function load_tache()
{
    var container=$("#container_tache");
    var id_demande=container.attr("id_demande");

    container.hide();
    container.parent().find(".loading").show();

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


function load_document_join()
{


  if($("#container_document_join").length)
  {
      var container=$("#container_document_join");
      var id_demande=container.attr("id_demande");



        container.hide();
        container.parent().find(".loading").show();

      // var url=container.attr("href_ajax")+"/"+id_message;
        var url=container.attr("href_base");
   
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

                  

                  document.querySelectorAll('.my-lightbox-toggle-join').forEach((el) => el.addEventListener('click', (e) => {
                    e.preventDefault();
                    const lightbox = new Lightbox(el, options);
                    lightbox.show();
                  }));


                }

        }); 

  }


}



jQuery(document).ready(function()
{

    load_fil(1);
    //load_document();
    //load_rdv();
    //load_tache();

  

    $('#form_body').summernote({
        lang: 'fr-FR',
        height: 300,
        toolbar: [
   
            ['view', ['undo', 'redo']],
            // ['font', ['bold', 'underline', 'clear', 'backcolor', 'forecolor']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            // ['insert', ['link', 'picture', 'video', 'hr']],
            ['insert', ['link', 'hr']],
            // ['view', ['fullscreen', 'codeview', 'help']],
            ['view', ['codeview']],
           
        ],


    });

    $(document).on("click","#message_new",function() {

      $(".zone-button-form-message").show();
      on_mode_edition();
      $("#form_send_message").show();
        reset_all_form_message();
       // $(".bt_redaction_message").hide();
        var a_mail=$("#a_mail").val();
        //alert(a_mail);
        $("#form_a_mail").val(a_mail);
        
        $("#id_mail_brouillon").val(0);
        $("#is_brouillon").val(0);


        var context="new";

        var id_demande=$(this).attr("id_demande");

        var id_message=0;

       var url_for_document_base="<?=base_url()?>demande/liste_document_join/";
       var url_for_document=url_for_document_base+id_demande+"/"+id_message+"/"+context;



       $("#container_document_join").attr("href_base",url_for_document);

       load_document_join();

       var ref="#Ref:"+id_demande+"#";
       

      
       $("#form_body").summernote('code',"<br><br><?php echo fullname($user->prenom, $user->nom);?>");
        $("#top_message").show();

        $("#ref_message").html(ref);
        $("#ref_message_input").val(ref);
        //$(this).hide();
        
       
      }); 


     




      $(document).on("click",".btn_action_message_mail",function() {

        $(".zone-button-form-message").show();
        reset_all_form_message();
        on_mode_edition();
        $("#form_send_message").show();
        //$(".bt_redaction_message").hide();
        var card=$(this).closest(".card");
        
        var a_mail=$(".message_a",card).html();
        var form_sujet=$(".message_sujet",card).text();
        var message_date=$(".message_date",card).text();
        var id_message=$(this).attr("id_message");

        var context=$(this).attr("context");

        var id_demande=$(this).attr("id_demande");


        //alert(form_sujet);

      var url_for_document_base="<?=base_url()?>demande/liste_document_join/";
       var url_for_document=url_for_document_base+id_demande+"/"+id_message+"/"+context;

      

       $("#container_document_join").attr("href_base",url_for_document);


  

       var ref="#Ref:"+id_demande+"#";

      

       var form_sujet=form_sujet.replace(ref,'');

       load_document_join();

          if(context=="transfert")
          {
              
              form_ref="Fwd: "+ref+' ';
          }
          else if(context=="brouillon")
          {
              form_ref=ref;
          }
          else
          {
              form_ref="Re: "+ref+' ';
             

              
          }
      
      


        var form_body=$(".message_body",card).html();

        if(context=="brouillon")
        {
          var before_form_body="";
        }
        else
        {
            var before_form_body="<br><br><?php echo fullname($user->prenom, $user->nom);?><br><hr><p style='font-size:12px;'><b>"+a_mail+" a écrit le "+message_date+"</b></p>";
        }


        form_body=before_form_body+form_body;
          

        $("#form_a_mail").val(a_mail);
        $("#form_sujet").val(form_sujet);
        $("#ref_message").html(form_ref);
        $("#ref_message_input").val(form_ref);

        if(context=="brouillon")
        {
          $("#id_mail_brouillon").val(id_message);
          //alert(id_message);
          $("#is_brouillon").val(1);

        }
        else
        {
          $("#id_mail_brouillon").val(0);
          $("#is_brouillon").val(0);

        }
        


        //$("#form_body").html(form_body);
        $("#form_body").summernote('code',form_body);

        $("#top_message").show();
        
        //$(this).hide();
        $("html,body").animate({scrollTop: $("#top_message").offset().top}, 'slow'      );
      }); 

     

      
      
      $(document).on("click","#message_new_cancel",function() {
        //alert(a_mail);
        $(".zone-button-form-message").hide();
        off_mode_edition();
        reset_all_form_message();
        $(".bt_redaction_message").show();
        $("#top_message").hide();
        remonte_initialize();
        return false;
        //$(this).hide();
      }); 

    

      $(document).on("click","#ajouter_rdv",function() {

        $("#top_rdv").show();
        on_mode_edition();
        load_rdv_create($(this).attr("href"));
        
        return false;
      });


      $(document).on("click","#ajouter_tache",function() {

        $("#top_tache").show();
        on_mode_edition();
        load_tache_create($(this).attr("href"));

        return false;
        });
    




        

      $(document).on("click","#rdv_new_cancel",function() {
        //alert(a_mail);
        $("#container_rdv_create").html();
        off_mode_edition();
        $("#top_rdv").hide();
        remonte_page($("#liste_rdv"));
        $(".zone-button-form-rdv").hide();
        return false;
        //$(this).hide();
      }); 



      $(document).on("click","#tache_new_cancel",function() {

        $("#container_tache_create").html();
        off_mode_edition();
        $("#top_tache").hide();
        var container=$("#liste_tache");
        remonte_page(container);
        $(".zone-button-form-tache").hide();
        return false;
        //$(this).hide();
      }); 

      //modal pour gérer les documents de la demande
      function gerer_document_modal(id_demande)
      {
        $(".container_GererDocumentModalCRM").html('<div class="text-center m-4"><i class="fa fa-circle-notch fa-spin"></i><br>Chargement</div>');
            $("#id_demande_GererDocumentModalCRM").html(id_demande);
            var url= "<?=base_url()?>/demande/liste_document_gerer_demande/"+id_demande;

            

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

      $(document).off("click",".gerer_document_modal").on("click",".gerer_document_modal", function(e){
     
            var id_demande=$(this).attr("id_demande");

            gerer_document_modal(id_demande);



        });

        $(document).off("click",".close_GererDocumentModalCRM").on("click",".close_GererDocumentModalCRM", function(e){
              
              $("#GererDocumentModalCRM").modal("hide");
              $(".container_GererDocumentModalCRM").html(null);

              $(".modal").modal("hide");
      
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
                          load_document_join();

                      }

                  }); 
      
      
            });
      
      
      
      //ICi on s'occupe des dépots et des documents

      $(document).off("click",".ajouter_document_modal").on("click",".ajouter_document_modal", function(e){
     
        $("#GererDocumentModalCRM").modal("hide");
              $(".container_GererDocumentModalCRM").html(null);
       
        var id_demande=$(this).attr("id_demande");
        $(".drop_zone_id_demande").val(id_demande);
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
        load_document_join();
        var id_demande=$(".ajouter_document_modal").attr("id_demande");

        gerer_document_modal(id_demande);


       }
      

       


       
      
       // load_reload_page_document();
      
       
       
        return false;
      });


   


     

      $(document).on("submit","#form_insert_rdv",function() 
      {
          remonte_page($("#top_rdv"));
          
          var container=$("#container_rdv_create");

          container.hide();
          container.parent().find(".loading").show();
  
          var dataString=$(this).serialize();

          var url=$(this).attr("action");

          jQuery.ajax
          ({	
              type:'POST',
              url: url,
              data: dataString,
              cache: false,
              success: function(html)
              { 
              
                  container.html(html);
                  $("#loading_calendar_load").hide();
                  container.show();

                  $("#container_rdv_create").html();
                  $("#top_rdv").hide();

                  load_rdv();
                  remonte_page($("#liste_rdv"));
                  //alert(html);

              }

          }); 

          return false;
      });

      $(document).on("submit","#form_insert_tache",function() 
      {
          remonte_page($("#top_tache"));
          
          var container=$("#container_tache_create");

          container.hide();
          container.parent().find(".loading").show();
  
          var dataString=$(this).serialize();

          var url=$(this).attr("action");

          jQuery.ajax
          ({	
              type:'POST',
              url: url,
              data: dataString,
              cache: false,
              success: function(html)
              { 
              
                  container.html(html);
                  $("#loading_calendar_load").hide();
                  container.show();

                  $("#container_tache_create").html();
                  $("#top_tache").hide();

                  load_tache();
                  remonte_page($("#liste_tache"));
                  //alert(html);

              }

          }); 

          return false;
      });
      
      $(document).on("click","#btn_form_send_message_brouillon", function()
      {
          $("#is_brouillon").val(1);

          $("#form_send_message").submit();
      });

      $(document).on("click","#btn_form_send_message", function()
      {
          $("#is_brouillon").val(0);
          $("#form_send_message").submit();
      });

      $(document).on("submit","#form_send_message",function() 
      {
        remonte_initialize();
          
     $(".alert_form_send_message",form).hide();
     /*   $("#btn_form_send_message").hide();
        $("#btn_form_send_message_brouillon").hide();
        $("#message_new_cancel").hide();*/

        $(".zone-button-form-message").hide();

        var form=$(this);

        form.hide();
        form.parent().find(".loading").show();

        var dataString=$(this).serialize();


        var url=$(this).attr("action");
        var pluriel='';

        $(".output_send_message").html('');
        
        
          jQuery.ajax
          ({ 
                type :"POST",
                url : url, 
                data: dataString,
                dataType: "json",
                    

                success : function(data)
                {
                 
                    if(data.id){
                        //console.log(data);
                        //$('#send_message789', form).trigger('click');
                        //form.html('<p class="alert alert-success">Message envoyé avec succès.</p>');
                        //form.html("<div class='text-center m-5'>Le message a été envoyé!</div>").show();
                        
                        //form.parent().find(".loading").hide();
                       // reset_all_form_message();

                        load_fil();
                       // $(".zone-button-form-message").hide();
                       // off_mode_edition();
                        //reset_all_form_message();
                      //  $(".bt_redaction_message").show();
                        //$("#top_message").hide();

                        $(".zone-button-form-message").hide();
                        off_mode_edition();
                        reset_all_form_message();
                        $(".bt_redaction_message").show();
                        $("#top_message").hide();
                        remonte_initialize();
                       
                          
                    }else{

                      $(".zone-button-form-message").show();
                     /* $("#btn_form_send_message").show();
                      $("#btn_form_send_message_brouillon").show();
                        $("#message_new_cancel").show();*/
                        form.parent().find(".loading").hide();
                        form.show();

                        console.log(data);
                        var somme_errors = 0;
                        $.each(data, function(i, obj) {
                            somme_errors++;
                            $('#'+obj.cible+'', form).css('color', 'red').html('<span class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+obj.msg+"</span>").show();
                        });

                        if(somme_errors>1){pluriel="s"}else{pluriel=''};

                        $(".alert_form_send_message",form).show().html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+somme_errors+" erreur"+pluriel);


                       /* submit.append(' \
                            <span class="temp_error label label-danger" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+somme_errors+'</span>');
                        submit.prop('disabled', false);*/
                    }
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                form.show().html(jqXHR.responseText);
                
            });

            return false;
      });

    
 
     $(document).on("click",".bt_form_rdv",function(){
     
        var form=$("#form_rdv");
        $("#loading_layer").show();
        var url=form.attr("action");
        var dataString=form.serialize();

        var is_error=verif_error_form(form);
                //On vérifie s'il y a des erreurs
                if(is_error==true)
                {

                  <?php if(!isset($is_form_rdv_direct)):?>
                  remonte_page( $("#top_rdv"));
                  <?php endif;?>

                  $("#loading_layer").hide();

                  return false;
                }
               
        <?php if(!isset($is_form_rdv_direct)):?>
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
                  $("#top_rdv").hide();
                  $("#loading_layer").hide();
                  $("#container_rdv_create").html(null).hide();
                  $(".zone-button-form-rdv").hide();
                  load_rdv();
                  remonte_page($("#container_fil_message"));
                  off_mode_edition();
                }
               
              }

          }); 
        <?php else:?>

          form.submit();

        <?php endif;?>
       



        return false;
     });


     $(document).on("click",".bt_form_tache",function(){
     
     var form=$("#form_tache");
     $("#loading_layer").show();
     var url=form.attr("action");
     var dataString=form.serialize();

     var is_error=verif_error_form(form);
             //On vérifie s'il y a des erreurs
             if(is_error==true)
             {

               <?php if(!isset($is_form_tache_direct)):?>
               remonte_page( $("#top_tache"));
               <?php endif;?>

               $("#loading_layer").hide();

               return false;
             }
            
     <?php if(!isset($is_form_tache_direct)):?>
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
               $("#top_tache").hide();
               $("#loading_layer").hide();
               $("#container_tache_create").html(null).hide();
               $(".zone-button-form-tache").hide();
               load_tache();
               remonte_page($("#liste_tache"));
               off_mode_edition();
             }
            
           }

       }); 
     <?php else:?>

       form.submit();

     <?php endif;?>
    



     return false;
  });

     $(document).on("click",".view_rdv",function(){

      $("#top_rdv").show();
        on_mode_edition();
        load_rdv_create($(this).attr("href"));

         
  

        /*$("#container_tache_create").html();
        $("#top_tache").hide();
        $(".zone-button-form-tache").hide();*/
        
        return false;
        
     });


     $(document).on("click",".view_tache",function(){

        $("#top_tache").show();

          on_mode_edition();
          load_tache_create($(this).attr("href"));
          
        /*  $("#container_rdv_create").html();
        $("#top_rdv").hide();
        $(".zone-button-form-rdv").hide();*/
          return false;
   
        });
      
    
      
});
</script>