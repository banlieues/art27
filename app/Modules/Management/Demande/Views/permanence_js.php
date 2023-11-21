<script type="text/javascript">
		
     <?php if($interface==="outlook"):?>
	
	$(".tr_fiche_demande_contact_premier").find(".control-label").html("Premier contact?");
	$(".tr_fiche_visite_demande_id_visite").find(".control-label").html("Type");
	
	$(".tr_fiche_demande_contact_duree").html("").remove();
	
	$(".message_obligatoire_contact").remove();
	
	$(".tr_fiche_email_personne").find(".control-label").html("* Email");
	
	 
    <?php endif;?>

//	$("input[name_checkbox='id_type_demande']").each(function(){
//	    var el_type=$(this);
//	    if(el_type.closest("label").text()==="Accompagnement"){el_type.closest("label").text("Demande d'accompagnement");};
//	    if(el_type.val()==="2"){el_type.html("Demande de visite");};
//	    
//	});
	
	   <?php if($interface==="bureau"):?>
	 
	
	$(".tr_fiche_demande_contact_duree").find(".control-label").html("Durée du contact");
	 
    <?php endif;?>

	<?php if($interface==="telephone"||$interface==="guichet"):?>
	 
	
	 $(".tr_fiche_rel_personne_bien").find(".control-label").html("Profil du demandeur");
	 $(".tr_fiche_nom_personne").find(".control-label").html("Nom");
	  
	 <?php endif;?>
    
    $(".tr_fiche_nom_demande").find(".control-label").html("* Sujet");
    $(".tr_fiche_id_type_demande").find(".checkboxunique").removeClass("checkboxunique").addClass("checkboxuniquemultiple");
    $(".tr_fiche_accompagnement_demande_id_type_accompagnement").find(".control-label").html("** Type d'acc. spécifique");
    $(".tr_fiche_accompagnement_demande_id_thematique_principal").find(".control-label").html("** Thématique d'entrée");
    
  $(function () {
    
$('.a ').on('click', function(evt){
       // bloquer le comportement par défaut: on ne rechargera pas la page
       evt.preventDefault(); 
       // enregistre la valeur de l'attribut  href dans la variable target
	var target = $(this).attr('href');
       /* le sélecteur $(html, body) permet de corriger un bug sur chrome 
       et safari (webkit) */
	$('html, body')
       // on arrête toutes les animations en cours 
       .stop()
       /* on fait maintenant l'animation vers le haut (scrollTop) vers 
        notre ancre target */
       .animate({scrollTop: $(target).offset().top}, 500 );
    });
    

	   
    
      $(document).off('click', '.modal_demandeur').on('click', '.modal_demandeur', function(){
	  var id_message=$(this).closest("tr").attr("id_message");
	
	var dataString="id_message="+id_message;
          $('#fh_dao_fiche_modal').modal();
          $('.fh_dao_fiche_modal_container').html("<div style='text-align:center; margin-top: 20px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");
          
          var adresse = "<?=base_url();?>app/load_demandeur/";
          jQuery.ajax
           ({  
              type:'POST',
              url: adresse,
              data: dataString,
              cache: false,

              success: function(html)
              { 
                    $('.fh_dao_fiche_modal_container').html(html);
              }
          
        });
	    return false;
          
      });
      
         $(document).off('click', '.modal_bien').on('click', '.modal_bien', function(){
	  var id_message=$(this).closest("tr").attr("id_message");
	
	var dataString="id_message="+id_message;
          $('#fh_dao_fiche_modal').modal();
          $('.fh_dao_fiche_modal_container').html("<div style='text-align:center; margin-top: 20px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");
          
          var adresse = "<?=base_url();?>app/load_bien/";
          jQuery.ajax
           ({  
              type:'POST',
              url: adresse,
              data: dataString,
              cache: false,

              success: function(html)
              { 
                    $('.fh_dao_fiche_modal_container').html(html);
              }
          
        });
	    return false;
          
      });
      
      $(document).off('click', '.btn_ajout_id_message_demande').on('click', '.btn_ajout_id_message_demande', function(e){
			e.preventDefault();
			
			var id_message = $(this).attr('id_message');
			var id_demande = $(this).attr('id_demande');
			var adresse = "<?=base_url();?>fh/myoutlook/save_outlook_mail";
			var dataString = "id_message="+id_message+"&id_demande="+id_demande;
			var thiss = $(this);

			jQuery.ajax
			({  
				type:'POST',
				url: adresse,
				data: dataString,

				beforeSend: function(){
				   thiss.html('<i id="Loading" class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
				},
			
				success: function(html){
					if(html){
//						$('#fh_dao_fiche_modal').modal('hide');
//						$('#fh_dao_fiche_modal2').modal('hide');
						$('#fh_dao_fiche_modal').find(".close_modal_fiche").trigger("click");
						$('#fh_dao_fiche_modal2').find(".close_modal_fiche").trigger("click");
						thiss.closest(".fh_dao_fiche_modal").find(".close_modal_fiche").trigger("click");
						jQuery.ajax
						({  
						    type:'POST',
						    url: "<?php echo base_url()?>app/get_permanence_confirm/"+id_demande,
						    
						    success: function(html){
							$(".container_insert_demande_principale").html(html);
							
						    }	
						    
						    
						  });   
			
						
						
					}
					
					
				}
			});
			
			});
      
      $(document).off('click', '.edit_email_outlook').on('click', '.edit_email_outlook', function(e){
			e.preventDefault();
			var id_message=$(this).attr("id_message");
			var dataString="id_message="+id_message;
			var adresse = $(this).attr('href');
			
			$("#fh_dao_fiche_modal_title").html("");
			$('#fh_dao_fiche_modal').modal();
			$('.fh_dao_fiche_modal_container').html("<div style='text-align:center; margin-top: 20px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");
			
          
			jQuery.ajax
			({  
				type:'POST',
				url: adresse,
				data: dataString,
				cache: false,

				success: function(html)
				{ 
					$('.fh_dao_fiche_modal_container').html(html);
				}
			});
          
        });
	
    $(document).off('click','.verif_doublon_adresse').on('click', '.verif_doublon_adresse', function(e){
		
	
	var container=$(this).closest(".c_adresse_permanence");
	var adresse_fr=$("input[name='adresse_fr_bien']",container).val();
	var adresse_nl=$("input[name='adresse_nl_bien']",container).val();
	var dataString="adresse_fr="+adresse_fr+"&adresse_nl="+adresse_nl;
	
	$(".search_doublon_adresse").html('<div class="text-center m-5"><i class="fas fa-circle-notch fa-spin"></i></div>');

	var adresse="<?php echo base_url();?>/bien/verif_doublon_adresse";
	jQuery.ajax
			    ({	
				    type:'POST',
				    url: adresse,
				    data: dataString,
				    
				    cache: false,
				     success: function(html)
				    {
						
						$(".search_doublon_adresse").html(html);
					
					
				    }	
			    })
    });

	
      $(document).off('click','.get_content_modal_p').on('click', '.get_content_modal_p', function(e){
			e.preventDefault();
			
		
			var id_message = $(this).attr('id_message');
			//var data = {};
			
			
			jQuery.ajax
			({  
				type:'POST',
				url: "<?php echo base_url();?>fh/myoutlook/get_message/"+id_message+"/0",
				//data: data,
				cache: false,

				success: function(html)
				{ 
				  $(".modal_content_mail_outlook-body").html(html);
				  $("#modal_content_mail_outlook").modal();
				}
			});
		});
      
    $(document).off("click",".attacher_directement").on("click",".attacher_directement", function(e) 
    {
	    
	    var id_entity=$(this).attr("id_entity");
	    var entitie=$(this).attr("entity");
	    
	 


	    if(entitie==="demande")
	    {
		var id_message= $('input[name="id_message"]').val();
		var dataString="id_message="+id_message+"&id_demande="+id_entity;
		
		var adresse="<?php echo base_url();?>app/set_message_demande";
		 jQuery.ajax
			    ({	
				    type:'POST',
				    url: adresse,
				    data: dataString,
				    
				    cache: false,
				     success: function(html)
				    {
					
					$(".container_insert_demande_principale").html(html);
					
				    }	
			    })	    
	    }	

	    if(entitie==="personne")
	    {
		
		
		$("input[name_checkbox='demande_contact_premier']").each(function(){
		    var val_check_first=$(this).val();
		    //alert();
		    if(val_check_first==="1"){
			//alert(val_check_first);
			$(this).prop("checked",false);
		    }
		    else
		    {
			$(this).prop("checked",true);
			$("input[name_visibility='demande_contact_premier']").val(val_check_first);
		    }
		    
		});
		
		    var adresse="<?php echo base_url();?>app/get_demandeur";
		    var dataString="id_entity="+id_entity;
		    jQuery.ajax
			    ({	
				    type:'POST',
				    url: adresse,
				    data: dataString,
				    dataType: 'json',
				    cache: false,
				     success: function(data)
				    {
					
					$("input[name='id_entity_personne']").val(null);
					$("input[name='nom_personne_no_obligatoire']").val(null);
					$("input[name='prenom_personne_no_obligatoire']").val(null);
					$("input[name='adresse_personne']").val(null);
					//$("input[name='email_personne']").val("");
					$("input[name='telephone_personne']").val(null);
					
					$("select[name='localite_personne']").val(null);
					
					$("select[name='localite_personne']").trigger("chosen:updated");
					$("select[name='pays_personne']").val(null);
					$("select[name='pays_personne']").trigger("chosen:updated");
					
					
					$("input[name='id_entity_personne']").val(data.id_entity_personne);
					$("input[name='nom_personne_no_obligatoire']").val(data.nom_personne);
					$("input[name='prenom_personne_no_obligatoire']").val(data.prenom_personne);
					$("input[name_checkbox='langue_personne']").prop("checked",false);
					$("input[name_checkbox='civilite_personne']").prop("checked",false);
					
					
					if(data.langue_personne==="1")
					{
					 
					    $("input[name_checkbox='langue_personne']").each(function(){
						if($(this).val()==="1"){
						   
						    //$(this).trigger("click");
						    $(this).trigger("click");
						}
					    });
					    
					}
					if(data.langue_personne==="2")
					{
					 
					    $("input[name_checkbox='langue_personne']").each(function(){
						if($(this).val()==="2"){
						   
						     $(this).trigger("click");
						}
					    });
					    
					}
					if(data.civilite_personne==="1")
					{
					 
					    $("input[name_checkbox='civilite_personne']").each(function(){
						if($(this).val()==="1"){
						   
						     $(this).trigger("click");
						}
					    });
					    
					}
					if(data.civilite_personne==="2")
					{
					 
					    $("input[name_checkbox='civilite_personne']").each(function(){
						if($(this).val()==="2"){
						   
						     $(this).trigger("click");
						}
					    });
					    
					}
					//alert(data.localite_personne);
					$("input[name='adresse_personne']").val(data.adresse_personne);
				
					$("input[name='email_personne']").val(data.email_personne);
					$("input[name='telephone_personne']").val(data.telephone_personne);
					if (data.localite_personne !== null){
					$("select[name='localite_personne']").val(data.localite_personne);
				    }
					
					$("select[name='localite_personne']").trigger("chosen:updated");
					$("select[name='pays_personne']").val(data.pays_personne);
					$("select[name='pays_personne']").trigger("chosen:updated");
					
					
					
					
					$(".doublon_nom").html("");
					$("#biens_du_demandeur").html("");
					
					$("input[name='adresse_fr_bien']").val(null);
					$("input[name='adresse_nl_bien']").val(null);
					$("input[name='adresse_bt']").val(null);
					$("textarea[name='etage_logement_bien']").val(null);
					$("select[name='id_nombre_bien']").val(null);
					$("select[name='id_nombre_bien']").trigger("chosen:updated");
					$(".tr_fiche_etage_logement_bien").hide();
					$(".tr_fiche_id_chauffage_bien").hide();
					$(".tr_fiche_id_nombre_bien").hide();
					$("input[name_checkbox='id_chauffage_bien']").prop("checked",false);
					$("input[name_checkbox='id_type_bien_coche']").prop("checked",false);
					
					// $("textarea[name='etage_logement_bien']").val(null);
					// $("select[name='id_nombre_bien']").val(null);
					// $("select[name='id_nombre_bien']").trigger("chosen:updated");
					// $(".tr_fiche_etage_logement_bien").hide();
					// $(".tr_fiche_id_chauffage_bien").hide();
					// $(".tr_fiche_id_nombre_bien").hide();
					$("input[name='id_entity_bien']").val(null);
					$("select[name='rel_personne_bien']").val(null);
                    $("select[name='rel_personne_bien']").trigger("chosen:updated");
					
					if(data.nb_bien!=="0"){
					    
					    //alert(data.nb_bien);
					    if(data.nb_bien===0){
						//alert();
						   $(".error_span",$("#tr_fiche_id_bien")).remove();
	    
						    $(".error_bien_count").html("");
						$("input[name='id_entity_bien']").val(data.id_entity_bien);
						$("input[name_checkbox='id_type_bien_coche']").prop("checked",false);
						$("input[name_checkbox='id_chauffage_bien']").prop("checked",false);
						$("textarea[name='etage_logement_bien']").val("");
						$("select[name='id_nombre_bien']").val("0");
						$("select[name='id_nombre_bien']").trigger("chosen:updated");
						$(".tr_fiche_etage_logement_bien").hide();
						$(".tr_fiche_id_chauffage_bien").hide();
						$(".tr_fiche_id_nombre_bien").hide();
						
						$("input[name='adresse_fr_bien']").val(data.adresse_fr_bien);
						$("input[name='adresse_nl_bien']").val(data.adresse_nl_bien);
						$("input[name_checkbox='id_type_bien_coche']").each(function(){
						    if($(this).val()===data.id_type_bien_coche){

							 $(this).trigger("click");
							if($(this).val()==="2")
							{
							    
							    $(".tr_fiche_etage_logement_bien").show();
							    $(".tr_fiche_id_chauffage_bien").show();
							    
							    //alert(data.etage_logement_bien);
							    $("textarea[name='etage_logement_bien']").val(data.etage_logement_bien);
							    
							    $("input[name_checkbox='id_chauffage_bien']").each(function(){ if($(this).val()===data.id_chauffage_bien){ $(this).trigger("click"); } });
							    
							   
							    
							   
							    
							}
							if($(this).val()==="3")
							{
							    $(".tr_fiche_id_nombre_bien").show();
							    $(".tr_fiche_id_chauffage_bien").show();
							    
							    $("select[name='id_nombre_bien']").val(data.id_nombre_bien);
							    $("select[name='id_nombre_bien']").trigger("chosen:updated");
							    $("input[name_checkbox='id_chauffage_bien']").each(function(){ if($(this).val()===data.id_chauffage_bien){ $(this).trigger("click"); } });

							    
							} 
						    }
						});
							var id_bien=$("input[name='id_entity_bien']").val();
						var id_personne=$("input[name='id_entity_personne']").val();
						
						var dataString2="id_bien="+id_bien+"&id_personne="+id_personne;
						var adresse="<?php echo base_url();?>app/get_rel_demandeur_bien";
						


						jQuery.ajax
						({	
							type:'POST',
							url: adresse,
							data: dataString2,
							dataType: 'json',
							cache: false,
							success: function(data)
							{
							    if(data.is_error===0){
								
								$("select[name='rel_personne_bien']").val(data.rel_personne_bien);
							    $("select[name='rel_personne_bien']").trigger("chosen:updated");
							    }
							}	
						    });
					    }
					    else
					    {
						$("#biens_du_demandeur").html(data.bien);
					    }	
					}
				    }

			    });
		
	    }//fin cas bien
	    if(entitie==="bien")
	    {
	    
			$("#containeur_demandeurs_possible").html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');

			var adresse="<?php echo base_url();?>/bien/get_bien_data/"+id_entity;
		    var dataString="id_entity="+id_entity;
		    jQuery.ajax
			    ({	
				    type:'POST',
				    url: adresse,
				    data: dataString,
				    dataType: 'json',
				    cache: false,
				     success: function(data)
				    {
						
						$("select[name='rel_personne_bien']").val(null);
						//$("select[name='rel_personne_bien']").trigger("chosen:updated");
					
						$("input[name='id_entity_bien']").val(null);
						$("input[name='id_entity_bien']").val(data.id_bien);

						$("input[name_checkbox='id_type_bien_coche']").prop("checked",false);
						$("input[name_checkbox='id_type_bien_coche']").removeClass("hasCheck");
						$("input[name_checkbox='id_chauffage_bien']").prop("checked",false);
						$("input[name_checkbox='id_chauffage_bien']").removeClass("hasCheck");

						$("textarea[name='etage_logement_bien']").val(null);
						$("select[name='id_nombre_bien']").val(null);
						//$("select[name='id_nombre_bien']").trigger("chosen:updated");
						$("select[name='adresse_fr_cp']").val(null);
						$("input[name='adresse_bt']").val(data.bt);
						//$("select[name='adresse_fr_cp']").trigger("chosen:updated");

						if(data.adresse_fr_cp>0&&$.isNumeric(data.adresse_fr_cp))
						{ 
						    $("select[name='adresse_fr_cp']").val(data.adresse_fr_cp);
							//$("select[name='adresse_fr_cp']").trigger("chosen:updated");
						}
						    
						    
						$(".tr_fiche_etage_logement_bien").hide();
						$(".tr_fiche_id_chauffage_bien").hide();
						$(".tr_fiche_id_nombre_bien").hide();
						
						$("input[name='adresse_fr_bien']").val(data.adresse_fr);
						$("input[name='adresse_nl_bien']").val(data.adresse_nl);
						$("input[name_checkbox='id_type_bien_coche']").each(function(){
						    if($(this).val()===data.id_type){

							$(this).trigger("click");
							if($(this).val()==="2")
							{
							    
							    $(".tr_fiche_etage_logement_bien").show();
							    $(".tr_fiche_id_chauffage_bien").show();
							    
							    //alert(data.etage_logement_bien);
							    $("textarea[name='etage_logement_bien']").val(data.etage_logement);
							    
							    $("input[name_checkbox='id_chauffage_bien']").each(function(){ if($(this).val()===data.id_chauffage){ $(this).trigger("click"); } });
							    
							   
							    
							   
							    
							}
							if($(this).val()==="3")
							{
							    $(".tr_fiche_id_nombre_bien").show();
							    $(".tr_fiche_id_chauffage_bien").show();
							    
							    $("select[name='id_nombre_bien']").val(data.id_nombre_logement);
							    //$("select[name='id_nombre_bien']").trigger("chosen:updated");
							    $("input[name_checkbox='id_chauffage_bien']").each(function(){ if($(this).val()===data.id_type_chauffage){ $(this).trigger("click"); } });

							    
							} 
						    }
						});
						
						var id_bien=$("input[name='id_entity_bien']").val();
						var id_personne=$("input[name='id_entity_personne']").val();
						
						
						var dataString2="id_bien="+id_bien+"&id_personne="+id_personne;
						var adresse="<?php echo base_url();?>/demande/form_get_demandeurs_possibles/"+id_bien;
						//alert(adresse);


						jQuery.ajax
						({	
							type:'POST',
							url: adresse,
							data: dataString2,
							//dataType: 'json',
							cache: false,
							success: function(data)
							{
								$("#demandeurs_possible").show();

								$("#containeur_demandeurs_possible").html(data);
								//alert(data);
								
							
							}	
						});






						
				    }//fin
				});
		
	    }	//fin entitie bien
	    
		//set_obligatoire();
    });    
  })

</script>

