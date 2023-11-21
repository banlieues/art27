<?php if($this->fh_dao->get_autorisation_mail("email_all_r")||$type_view=="mesmessages"): ?>
<?php $this->load->view("interface/choice_module"); ?>
<?php $this->load->view("interface/search_all"); ?>
<?php 	
	$autorisation_all = $this->fh_dao->get_autorisation_mail_all("email_all_r");
	
	//if($type_view=="mesmessages"){$autorisation_all=FALSE;}
	$mail_user = CRMAIL;
	if(!$autorisation_all){
		$user_connecter = $this->db->select('*')->where('id_user', $_SESSION['id'])->get('users')->result();
		$mail_user = $user_connecter[0]->mail;
	}
?>
<div class='entities c_rubrique'>
  <div style="margin-bottom:0 !important; margin:10px; border-color: SLATEGRAY !important" class="panel panel-info">
  	<div style='background-color: SLATEGRAY !important; border-color: SLATEGRAY !important' class="panel-heading">
		<div class='row'>
		    <div style='font-size:24px; min-height: 40px' class='col-lg-6'>
		      <?php if(isset($type_view) && $type_view=="mesmessages"): ?>
		      	<?php echo icon("outlook");?> Mes messages
		      <?php else : ?>
		    	<?php echo icon("outlook");?> Outlook
		      <?php endif;?>
		   
		    </div>
		</div>
    </div>

	<div style="padding:0 !important" class="panel-body">
	<aside class="lg-side" id="cont_list_mail">
	  <div class="list_mail">
	  	<?php if(isset($type_view) && $type_view=="mesmessages"): ?>
	  		<div class="inbox-body">
	  			<div id="content_table_messages" class="panel panel1 panel-default "></div>
	  		</div>
	  	<?php else : ?>
	    <div class="panel-footer">
	      <strong>Mails (<?=$mail_user;?>)</strong>

	      <div class="pull-right">
                  <?php 
                    $ids_users_autoriser=array('1','2','23',"24");
                    $id_session=$_SESSION['id'];
                    if(in_array($id_session, $ids_users_autoriser)):
                  ?>
                  <a href="<?=base_url();?>fh/myoutlook/insert_id_crm_folder" target="_blank" class="btn btn-xs btn-info">Config Dossiers CRM</a> 
                  <?php endif;?>
                  <a href="#" class='btn btn-xs btn-success btn-import-outlook9'><i class="fa fa-download"></i> Importer les emails outlook</a>
	      </div>
	    </div>



	    <div id="eror_importation9" class="panel-footer" style="display: none;"></div>

		<div class="inbox-body">
			<span class="contient">
			<ul class="nav nav-tabs " role="tablist">             
				<li  role="presentation" class="email_outlook_li <?php if($num_onglet==0):?>active<?php endif;?>">
					<a href="<?php echo base_url();?>fh/myoutlook/get_table">Tous</a>
				</li> 
				<li role="presentation" class="email_outlook_li <?php if($num_onglet==1):?>active<?php endif;?>">
					<a href="<?php echo base_url();?>fh/myoutlook/get_table/1">Emails non traités</a>
				</li> 
			</ul>
			</span>
			<div id="content_table_messages" class="panel panel1 panel-default "></div>
	    </div>
	    <?php endif;?>
	  </div>
	</aside>
</div>
  </div>
</div>

<script type="text/javascript">
    $(function () {
	  	var dataString="";

	  	<?php if($autorisation_all==FALSE){?>
	  		dataString = dataString+"origin=<?=$mail_user;?>";
	  	<?php } ?>

	  	<?php if(isset($type_view) && $type_view=="mesmessages"){?>
	  		dataString = dataString+"type_view=<?=$type_view;?>";
	  	<?php } ?>

		jQuery.ajax
		({  
			type:'POST',
			url: "<?php echo base_url();?>fh/myoutlook/get_table/<?php echo $num_onglet;?>",
			data: dataString,
			cache: false,
			
			beforeSend: function(){
			   $("#content_table_messages").html('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i> en chargement...').fadeIn();
			},

			success: function(html)
			{ 
			  $("#content_table_messages").html(html);
			}
	    });

	    $(document).off('click', '.btn-import-outlook9').on('click', '.btn-import-outlook9', function(e){
        	e.preventDefault();

        	$('#eror_importation9').html('').css('display','none');

        	var thiss = $(this);
        	var admin = 0;
        	<?php if($autorisation_all==TRUE){?>
        		admin = 1;
        	<?php } ?>
        	var adresse="<?php echo base_url();?>fh/myoutlook/import_outlook/"+admin;
	        jQuery.ajax
		    ({	
			    type:'POST',
			    url: adresse,
			    dataType: 'json',
			    beforeSend: function(){
			    	thiss.prop('disabled', true); //bloquer bouton
				  thiss.html('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i> Je suis en train d\'importer...').fadeIn();

				},
			    success: function(data){ 
			    
			    	//console.log(data);
			    	var response_view = '';

			    	$.each(data.response_importation, function( index, value ) {
						response_view += '<p class="alert alert-success alert-dismissible">\
				          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
				          '+value+'</p>';
					});

					$.each(data.response_importation_error, function( index, value ) {
					  	response_view += ' <p class="alert alert-danger alert-dismissible">\
		          			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
          				'+value+'</p>';
					});


					$('#eror_importation9').html(response_view).css('display','block');

			    	thiss.html('<i class="fa fa-download"></i> Importer les emails outlook');
			    	thiss.prop('disabled', false);
			    	//console.log('success import outlook!');
			    	//location.reload(true);
					//window.location.href = "<?=base_url();?>fh/myoutlook/sync_outlook/";
					
			    }
			    
		    }).fail(
	        	function(jqXHR, textStatus, errorThrown) {

                            thiss.html('<i class="fa fa-download"></i> Importer les emails outlook');
                            thiss.prop('disabled', false);

                            alert(jqXHR.responseText);
                         }
                    );
        });
		
	  
		$(document).off('click', '.email_outlook_li').on('click', '.email_outlook_li', function(e){
			e.preventDefault();
			var dataString;

		  	<?php if($autorisation_all==FALSE){?>
		  		dataString = "origin=<?=$mail_user;?>";
		  	<?php } ?>

			var adresse = $(this).find('a').attr('href');
			var thiss = $(this);
			
			$('.email_outlook_li').removeClass('active');
				   thiss.addClass('active');
			
			jQuery.ajax
			({  
				type:'POST',
				url: adresse,
				data: dataString,
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

//		$(document).off('click', '.email_outlook_delete').on('click', '.email_outlook_delete', function(e){
//			e.preventDefault();
//			var id_demande=$(this).attr("id_demande");
//			var id_message=$(this).closest("tr").attr("id_message");
//			var dataString="id_message="+id_message+"&id_demande="+id_demande;
//			var thiss = $(this);
//        
//			var r = confirm("Etes-vous sûr de vouloir supprimer cette liaison ?");
//			if (r == true) {
//				jQuery.ajax
//				({  
//					type:'POST',
//					url: "<?=base_url();?>fh/myoutlook/move_mailoutlook_db",
//					data: dataString,
//					cache: false,
//					
//
//					success: function(html)
//					{ 
//						if(html){
//							thiss.closest('td').html(html);
//						}
//					}
//				});
//			} 
//         
//		});
//
//		$(document).off('click', '.email_outlook_delete_def').on('click', '.email_outlook_delete_def', function(e){
//			e.preventDefault();
//			var id_message=$(this).closest("tr").attr("id_message");
//			var dataString="id_message="+id_message;
//			var thiss = $(this);
//        
//			var r = confirm("Etes-vous sûr de vouloir supprimer ce message de la base de données ?");
//			if (r == true) {
//				jQuery.ajax
//				({  
//					type:'POST',
//					url: "<?=base_url();?>fh/myoutlook/delete_mailoutlook_db",
//					data: dataString,
//					cache: false,
//					
//
//					success: function(html)
//					{ 
//						if(html=='success'){
//							thiss.closest('tr').remove();
//						}
//					}
//				});
//			} 
//         
//		});


		
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
						$('#fh_dao_fiche_modal').modal('hide');
						$('#fh_dao_fiche_modal2').modal('hide');
						$("tr", "#table_email_outlook").each(function(){ 
							 var attr = $(this).attr('id_message');
							 if(typeof attr !== typeof undefined && attr !== false){
								 if(attr.trim() == id_message.trim()){
									 $('td', $(this)).first().html(html);
								 }
								
							 }		 
						});
					}
				}
			});

        });

		$(document).off('click', '.edit_email_outlook').on('click', '.edit_email_outlook', function(e){
			e.preventDefault();
			
			var id_message=$(this).closest("tr").attr("id_message");
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

	

		
	});
</script>

<?php else: ?>
<div style='padding: 20px; text-align:center'>
<h4>Ooops… Vous n'avez pas l'autorisation d'accéder à cette page… </h4>
</div>
<?php endif; ?>