<?php if(!empty($pagination)):?>
<div class='panel-footer'>
      <?php echo $pagination ?> <?=$sort+1;?> - <?= $sort+$limit;?> / <?=$total_rows;?> résultats
 </div>
 <?php endif;?>

<table id="table_email_outlook" class="table table-bordered table-inbox table-hover">
        <thead>
          <tr style="background-color:#000;color:#fff;">
			<?php // if(!isset($id_demande)):?>
			<th><small>#</small></th>
			<?php // endif;?>
            <th><small>Objet</small></th>
            <th><small>#ContentPreview</small></th>
            <th><small>De</small></th>
            <th><small>Date</small></th>
            <?php if(isset($brouillons) && $brouillons==TRUE):?>
            <th></th>
        	<?php endif;?>

          </tr>
        </thead>
        <tbody>
           <?php if(empty($messages)): ?>
            <tr class="unread">
              <td colspan="6">
              	<?php if(isset($brouillons) && $brouillons==TRUE):?>
              		Aucun brouillon trouvé
              	<?php else : ?>
             		 Aucun message trouvé</td>

             	<?php endif;?>
            </tr>  
          <?php 
               else: 
                  $i = 1;
                  foreach ($messages as $message) {
          ?>		
                  <tr <?php if($message->sender_mail===CRMAIL):?> style="background-color: gainsboro" <?php endif;?> id_message="<?=$message->id_primary;?>">
                      <?php // if(!isset($id_demande)):?>
					  <td style="min-width:130px!important">
                          <?php
                         
                            $message_exist = $this->db->select('*')->where('id_email', trim($message->id_primary))->get('email_outlook_lien')->result();
                            //  print_r($message_exist);
                            if(empty($message_exist)):
                          ?>
                              <!--<button href="<?=base_url();?>app/load_demandes/" class='btn btn-xs btn-default edit_email_outlook'>Joindre à une demande</button>-->
					      <?php if(!isset($type_view) OR $type_view!='mesmessages'): ?>

			      <a href="<?=base_url();?>app/index_outlook_ajout/<?=$message->id_primary;?>" class='btn btn-xs btn-default'>Joindre à une demande</a>
			      <a href="#" class='btn btn-xs btn-danger email_outlook_delete_def'><i class="fa fa-trash"></i> Supprimer</a>
			      			<?php endif;?>
                        
                        	

                        <?php else : ?>
							  
                            <!--  <button href="<?=base_url();?>app/load_demandes/<?=$message_exist[0]->id_demande;?>" class='btn btn-xs btn-success edit_email_outlook'><i class="fa fa-link"></i></button>&nbsp;-->
				 <?php if(!isset($id_demande)):?>
					      <button 
						    onglet="outllokk_message" 
						  href="<?php echo base_url();?>fh/fhc_dao/page_view/<?=$message_exist[0]->id_demande;?>fhd_liste_demande" 
						  class="fh_dao_fiche btn btn-success btn-xs" 
						  fh-descriptor="fhd_liste_demande" 
						  href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?=$message_exist[0]->id_demande;?>" 
						  href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?=$message_exist[0]->id_demande;?>">
						
						  <i class="fa fa-link"></i>
					      </button>
                             <?php endif;?>

<!--  <button href="<?=base_url();?>app/load_demandes/" class="btn btn-xs btn-info edit_email_outlook"><i class="fa fa-pencil"></i></button>&nbsp;-->
							 <?php if(!isset($type_view) OR $type_view!='mesmessages'): ?>
							  <button id_demande="<?=$message_exist[0]->id_demande;?>" href="#" class="btn btn-danger btn-xs email_outlook_delete"><i class="fa fa-trash"></i><i class="fa fa-link"></i></button>
							<?php endif;?>
                          <?php endif;?>
                      </td>
					   <?php // endif;?>
					   <?php // if lu ou pas?
					   		$is_lu=FALSE;
							if(empty($message->lus)):
								$is_lu=FALSE;
							else:
								//on explose empty, on doit regarder la date du message

								$date_limite=strtotime("2021-12-06");
								$date_mail=strtotime($message->created_datetime);
								//echo $date_limite; die($date_mail);
								if($date_mail>=$date_limite)
								{

								$is_lus=explode(",",$message->lus);
								if(in_array($this->session->userdata("id"),$is_lus)):
									$is_lu=TRUE;
								endif;
								}
								else
								{
									if(empty($message->lus)):
										$is_lu=FALSE;
									else:
										$is_lu=TRUE;		
									endif;

								
								}

								


							endif;	

						


						?>
                      <td> <?php if(!$is_lu):?><i class='fa fa-circle text-info'></i><?php endif;?> <?= $message->subject;?></td>
                      <td>
                           <?=substr(strip_tags($message->body_preview),0,300);?>
                            <?php if(!isset($brouillons)):?>
							<!-- Si je suis en admin pure alors je mets pas à jour les mails -->
							<?php if($no_tr==1):?>
                           		<button href="#" is_marque="<?php echo $is_marque;?>" class="btn btn-xs btn-danger btn-open-message-no-tr <?php if(!isset($id_demande)): echo 'get_content_modal'; else : echo 'get_content'; endif;?>" >View</button> 
                           <?php else:?>
								<button href="#" is_marque="<?php echo $is_marque;?>" class="btn btn-xs btn-danger btn-open-message <?php if(!isset($id_demande)): echo 'get_content_modal'; else : echo 'get_content'; endif;?>" >View</button> 

						   <?php endif;?>
						   <?php endif;?>    
                      </td>
                      <td> <b><i>De:</i></b> <?php if(affiche_balise($message->sender_mail)=='MicrosoftExchange329e71ec88ae4615bbc36ab6ce41109e@curbain.onmicrosoft.com'): echo 'Microsoft'; else : echo affiche_balise($message->sender_mail); endif;?><br>
			  <b><i>A:</i> </b> 
			   
			   <?php if(!empty($message->to_mail)):?>
			   <?= affiche_balise($message->to_mail);?>
			  <?php else: ?>
			  CRM
			  <?php endif;?>
		      </td>
                      <td>
                         <?php 
			 
			 if(!empty($message->received_datetime)):
                            $date = new DateTime($message->received_datetime);
			    
			  
                            echo date_format($date, 'd/m/Y à H:i');
			   else:
			       if(!empty($message->send_datetime)):
				    $date = new DateTime($message->send_datetime);


				    echo date_format($date, 'd/m/Y à H:i');
				endif;
			    endif;
                          ?>  
                      </td>

                      <?php if(isset($brouillons) && $brouillons==TRUE):?>
			            <td><button class="btn btn-success btn-sm call_new_message_courriel" id_message="<?= $message->id_primary;?>" type="brouillon">Ouvrir</button></td>
			        	<?php endif;?>

                  </tr>
          <?php   
                  $i++;
                  }
                endif;
          ?>  
        </tbody>
      </table>
	  <?php if(!empty($pagination)):?>
      <div class='panel-footer'>
      <?=$sort+1;?> - <?= $sort+$limit;?> / <?=$total_rows;?> résultats <?php echo $pagination ?>
    </div>
	<?php endif;?>
	

	<!-- Modal -->
	<!-- Associer à une demande -->
	<div id="modal_content_mail_outlook" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>
		  <div class="modal-body modal_content_mail_outlook-body"></div>
		</div>
	  </div>
	</div>
	
	<script>
	$(function () {
		$(document).off("click","a[data-ci-pagination-message]").on("click","a[data-ci-pagination-message]", function(e){
                 
			e.preventDefault();
			
			var dataString;

			<?php if(isset($origin)){?>
		  		dataString = "origin=<?=$origin;?>";
		  	<?php } ?>
                            
                            
                        <?php if(isset($id_demande)){?>
		  		dataString = "id_demande=<?=$id_demande;?>";
		  	<?php } ?>    

		  	<?php if(isset($type_view) && $type_view=="mesmessages"){?>
		  		if(dataString==""){
		  			dataString = dataString+"type_view=<?=$type_view;?>";
		  		}else{
		  			dataString = dataString+"&type_view=<?=$type_view;?>";
		  		}
	  	    <?php } ?>

	  	    <?php if(isset($type) && $type=="brouillons"){?>
		  		if(dataString==""){
		  			dataString = dataString+"type=<?=$type;?>";
		  		}else{
		  			dataString = dataString+"&type=<?=$type;?>";
		  		}
	  	    <?php } ?>


			var adresse= $(this).attr("href");
			var thiss = $(this);
			adresse = adresse;
        
			jQuery.ajax({ 
				type:'POST',
				url: adresse,
				data:dataString,
				cache: false,

				beforeSend: function(){
				   thiss.append('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
				},

				success: function(html)
				{ 
				  $("#content_table_messages").html(html);
				}
			});   

		});
		
		$(document).off('click', '.btn-open-message').on('click', '.btn-open-message', function(e){
			e.preventDefault();
			var id_message = $(this).closest('tr').attr('id_message');
                        var is_marque=$(this).attr("is_marque");
                       // alert(is_marque);
			jQuery.ajax
			({  
				type:'POST',
				url: "<?php echo base_url();?>fh/myoutlook/check_lus/"+id_message,
				
				success: function(html)
				{ 
				 console.log(id_message+' checked');
				  refresh_mails_nolus(1);
				}
			});

		});

		$(document).off('click', '.btn-open-message-no-tr').on('click', '.btn-open-message-no-tr', function(e){
			e.preventDefault();
			var id_message = $(this).closest('tr').attr('id_message');
                        var is_marque=$(this).attr("is_marque");
                       // alert(is_marque);
			// jQuery.ajax
			// ({  
			// 	type:'POST',
			// 	url: "<?php echo base_url();?>fh/myoutlook/check_lus/"+id_message,
				
			// 	success: function(html)
			// 	{ 
			// 	 console.log(id_message+' checked');
			// 	  refresh_mails_nolus(); refresh_mails_nolus(0);
			// 	}
			// });

		});

		$(document).off('click','.get_content').on('click', '.get_content', function(e){
			
			e.preventDefault();
			
			var id_message = $(this).closest('tr').attr('id_message');
			var data = {};
			data['limit'] = "<?=$limit;?>";
			
			jQuery.ajax
			({  
				type:'POST',
				url: "<?php echo base_url();?>fh/myoutlook/get_message/"+id_message,
				data: data,
				cache: false,
				
				beforeSend: function(){
				   $("#content_table_messages").html('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i> en chargement...').fadeIn();
				},

				success: function(html)
				{ 
				  $("#content_table_messages").html(html);
				}
			});
		});
		$('#modal_content_mail_outlook').on('show.bs.modal', function (e) {
	   
		var calcul_hauteur_modal=window.innerHeight-200;
				$(".modal-body").css("max-height",calcul_hauteur_modal+"px");
				var calcul_hauteur_iframe=window.innerHeight-250;
		  $("iframe",".modal-body").css("height",calcul_hauteur_iframe+"px");
	    });
		
		$(document).off('click','.get_content_modal').on('click', '.get_content_modal', function(e){
			e.preventDefault();
			
		
			var id_message = $(this).closest('tr').attr('id_message');
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
	});
	</script>