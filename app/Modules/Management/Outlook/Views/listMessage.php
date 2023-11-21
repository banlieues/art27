<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>



<!--badge bg-<?=$themes->outlook->color?> text-decoration-none-->
<div class="row">
    <div class="col-12">
        <div class="card border-top-<?=$themes->outlook->color?>">
                <div class="card-header sticky_button bg-light">
                    <form>
                        <div class="row">
                            <div class="col-lg-auto p-1 align-self-center">
                                <h5 class='card-title mb-0'>
                                <?=$nbMessages?> message<?=plurial_s($pager->getTotal())?>
                               
                                    
                                </h5>

								<input url_change="<?=base_url()?>outlook/liste_message/1" <?php if($onglet==1):?>checked<?php endif;?>  class="url_change form-check-input" name="onglet" value="1" type="radio" id="message_1"> <label class="form-check-label" for="message_1">Messages non traités</label> 
                                <input url_change="<?=base_url()?>outlook" <?php if($onglet==0):?>checked<?php endif;?>  class="url_change form-check-input" name="onglet" value="1" type="radio" id="message_0"><label class="form-check-label" for="message_0">Tous les messages</label> 
                                    
                                    
                            </div>
                            <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                <div class="input-group input-group-navbar text-lg-end">
                                    <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                    <button class="btn btn-<?=$themes->outlook->color?> text-white btn-sm" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                <a class="btn btn-<?=$themes->outlook->color?> btn-sm mt-1" href="<?=base_url()?>outlook_cron/import_outlook">
                                    <i class="<?=icon("contacts")?>"></i> Importer message
                                </a>
                            </div>
                        </div>
                    </form>
                </div> 
            <?php if ($nbMessages>0): ?>
                <div class="table-responsive"> 
                    <table id="table_data" class="table table-striped table-hover my-0 table-sm">
                        <thead>
                            <tr>
                                <?php 
                                    echo $getTh;
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($messages as $message):?>
                            <tr <?php if($message->sender_mail===CRMAIL):?> style="background-color: gainsboro" <?php endif;?> id_message="<?=$message->id_primary;?>">
                      <?php // if(!isset($id_demande)):?>
						<td>
							#<?=$message->id_primary?>
						</td>
					  <td style="min-width:130px!important">
                          <?php
                         $message_exist=$outlookModel->message_exist($message->id_primary);
                            //$message_exist = $this->db->select('*')->where('id_email', trim($message->id_primary))->get('email_outlook_lien')->result();
                            //  print_r($message_exist);
                            if(empty($message_exist)):
                          ?>
                              <!--<button href="<?=base_url();?>app/load_demandes/" class='btn btn-sm btn-default edit_email_outlook'>Joindre à une demande</button>-->
					      <?php if(!isset($type_view) OR $type_view!='mesmessages'): ?>

			      <a href="<?=base_url();?>demande/new/outlook/<?=$message->id_primary;?>" class='btn btn-sm btn-secondary'><i class="fa fa-link"></i>Joindre</a>
				  <button text_alert="le message #<?=$message->id_primary?> - <?=$message->subject?>" 
                                            id_delete="<?=$message->id_primary?>" href="<?=base_url()?>delete/deleteEmail" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                        
			      			<?php endif;?>
                        
                        	

                        <?php else : ?>
							  
                            <!--  <button href="<?=base_url();?>app/load_demandes/<?=$message_exist[0]->id_demande;?>" class='btn btn-sm btn-success edit_email_outlook'><i class="fa fa-link"></i></button>&nbsp;-->
				 <?php if(!isset($id_demande)):?>
					      <a 
						    onglet="outllokk_message" 
						  href="<?php echo base_url();?>demande/fiche/<?=$message_exist[0]->id_demande;?>" 
						  target="_blank";
						  class="fh_dao_fiche_old btn btn-success btn-sm" 
						  fh-descriptor="fhd_liste_demande" 
						  href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?=$message_exist[0]->id_demande;?>" 
						  href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?=$message_exist[0]->id_demande;?>">
						
						  <i class="fa fa-link"></i>
				 </a>
                             <?php endif;?>

<!--  <button href="<?=base_url();?>app/load_demandes/" class="btn btn-sm btn-info edit_email_outlook"><i class="fa fa-pencil"></i></button>&nbsp;-->
							 <?php if(!isset($type_view) OR $type_view!='mesmessages'): ?>
							  <button id_demande="<?=$message_exist[0]->id_demande;?>" href="#" class="btn btn-danger btn-sm email_outlook_delete"><i class="fa fa-trash"></i><i class="fa fa-link"></i></button>
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
								if(in_array(session("loggedUserId"),$is_lus)):
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
                       
                           <?=substr(strip_tags($message->body_preview),0,300);?>……

                       
                           
                      </td>
                      <td>
                      <?php if(!isset($brouillons)):?>
							<!-- Si je suis en admin pure alors je mets pas à jour les mails -->
							<?php if($no_tr==1):?>
                           		<a href="<?=base_url()?>outlook/message_view/<?=$message->id_primary?>" is_marque="<?php echo $is_marque;?>" class="btn btn-sm btn-<?=$themes->outlook->color?> view_message" ><i class="<?=icon("message_view")?>"></i> Lire</a> 
                           <?php else:?>
								<a href="<?=base_url()?>outlook/message_view/<?=$message->id_primary?>" is_marque="<?php echo $is_marque;?>" class="btn btn-sm btn-<?=$themes->outlook->color?> view_message" ><i class="<?=icon("message_view")?>"></i> Lire</a> 

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
                        <?php endforeach;?>
                        </tbody>   
                    </table>
                </div>   
                <?php if(!empty($pager) && $pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->outlook->color)?><?php endif;?>
                
            <?php else:?>
                <div class="text-center m-5"><h3>Je n'ai pas trouvé de messages</h3></div>        
            <?php endif;?>    
        </div>        
    </div>

</div>    

<?=view("Layout\Views\app_outlook_delete_js")?>

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
				 //console.log(id_message+' checked');
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
			data['limit'] = 0;
			
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

<?php $this->endSection(); ?>

