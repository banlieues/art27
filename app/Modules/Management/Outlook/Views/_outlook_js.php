<?php // 
	$autorisation_all = $this->fh_dao->get_autorisation_mail_all("email_all_r");
	if($autorisation_all==FALSE){
		$user_connecter = $this->db->select('*')->where('id_user', $_SESSION['id'])->get('users')->result();
		$mail_user = $user_connecter[0]->mail;
	}

	
?>


<script type="text/javascript">

	$(document).ready(function(){
		/*  depot */
		load_liste_depots();

		$(document).off("click","a[data-ci-pagination-depots]").on("click","a[data-ci-pagination-depots]", function(e){
			e.preventDefault();
			
			var thiss = $(this);
			var section = thiss.closest('section[class=view_depot777]');
			var adresse=thiss.attr("href");
			var container = section.find('.table_liste_depots');
			var view = $(this).closest('.user_pagination_outlook').attr('view');
		
			adresse = adresse;
	    
			jQuery.ajax({ 
				type:'POST',
				url: adresse,
				cache: false,
				data: "view="+view,

				beforeSend: function(){
				   thiss.append('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
				},

				success: function(html)
				{ 
				  container.html(html);
				}
			});   

		});

		//refresh
		$(document).off('click', '.refresh_liste_depot').on('click', '.refresh_liste_depot', function(e){
			e.preventDefault();

			load_liste_depots();

		});

		//search
		$(document).off('keyup', '.depot-input-search').on('keyup', '.depot-input-search', function(){
			var search = $(this).val();
			var section = $(this).closest('section[class=view_depot777]');
			var id_demande = section.find('.table_liste_depots').attr('id_demande');
			var view = section.find('.table_liste_depots').attr('view');
			var container = section.find('.table_liste_depots');

			var data = 'search='+search+'&view='+view;

			load_liste_depot(id_demande, data, container);
		});

		//delete
		$(document).off('click', '.delete_depot').on('click', '.delete_depot', function(e){
			e.preventDefault();
			
			var id_depot = $(this).attr('id_depot');
			var name = $(this).attr('name_depot');
			var adresse = '<?= base_url();?>fh/myoutlook/delete_depot/'+id_depot;

				//confirmation
				$.confirm({
			        title: 'Confirmation',
			        content: 'Etes-vous sûr de vouloir supprimer ce fichier ? <br> <small>'+name+'</small>',
			        buttons: {
			            cancel:  {
			            	text: 'J\'annule',
			            	btnClass: 'btn-danger',
			            	action : function(){
			                	$.alert('L\'action est annulé !');
			            	}
			            },
			            confirm:  {
			            	text: 'Je confirme',
			            	btnClass: 'btn-success',
			            	action : function(){
			            		jQuery.ajax({ 
									type:'POST',
									url: adresse,
									dataType: 'json',
									cache: false,
									success: function(data)
									{ 
										if(data.id){
											load_liste_depots();
									  		$.alert({
									  			title: 'Success',
									  			content: 'Le fichier est supprimé!'
									  		});
										}else{
											$.alert({
									  			title: 'Error',
									  			content: 'Une erreure s\'est produite lors de la suppression'
									  		});
										} 
									}
								});
			            	}
			                
			            }
		        	}
		    	});
		});

	    //submit files
	     $(document).off("submit",".upload_email_demande_depots").on("submit",".upload_email_demande_depots", function(e){
	     	
	     		$(this).find(".output").hide();
				var form=$(this);
				var section = $(this).closest('section[class=view_depot777]');
				var id_demande = section.find('.table_liste_depots').attr('id_demande');
		
				var data = new FormData();  

	     		var fileSelect = document.getElementById('files_email_demande').files.length;
			    for (var x = 0; x < fileSelect; x++) {
			        data.append("files_email_demande[]", document.getElementById('files_email_demande').files[x]);
			    }

			    //add id_demande dans data
			    data.append("id_demande",id_demande);

				var submit = $("#form_submit_download", form);
				var adresse="<?php echo base_url();?>fh/myoutlook/tr_depot_files";

	      
	           jQuery.ajax({
	                url : adresse, 
	                cache: false,
		    		contentType: false,
		    		processData: false,
		    		data: data,
		    		type: "post",
		    		dataType: "json",
			    
				     beforeSend: function(){
		               submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
		            },

	                success	: function (data)
	                {
				
						var el = document.getElementById('Loading');
						el.parentNode.removeChild(el);

						 if(data.status == 'success'||data.status=='error_upload'){
						    if(data.status=='error_upload'){
							 	$('#error_upload', section).html('<br><medium style="color: red;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'+data.msg+'</medium>');
							  //alert(data.msg);
							}else{
							     form.find('#files_email_demande').val('');
							     submit.after(' <span class="temporaire" style="color:green"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Success</span>');
							     setTimeout( function(){
								    	form.find('.temporaire').remove();
								  }, 5000);

							     //reload
							     load_liste_depots();
						    }
						 }else{
						    // alert();
						     $.each(data, function(i, obj) {
							 $('#'+obj.cible, section).css('color', 'red').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+obj.msg).show();
						     });
						 }
	                          
	                }
			}).fail(
				function(jqXHR, textStatus, errorThrown) {

				  var el = document.getElementById('Loading');
				  el.parentNode.removeChild(el);
				  alert(jqXHR.responseText);
				}
			);
            return false;
        });

		/* end depot*/




	
		//importer tout le n minutes
		//import_outlook_mails()
		// setInterval(function(){import_outlook_mails();}, (1000*60)*10); //10min

		/*refresh_mails_notraites()
		setInterval(function(){refresh_mails_notraites();}, 2000*60);
		refresh_mails_nolus();
		refresh_mails_nolus(0); //liste de message non lus
		setInterval(function(){refresh_mails_nolus(); refresh_mails_nolus(0);}, 2000*60);*/

   	});
	

	function refresh_mails_notraites(){
		var dataString = "";
		<?php if($autorisation_all==FALSE){?>
	  		dataString = "origin=<?=$mail_user;?>";
	  	<?php } ?>
		var adresse="<?php echo base_url();?>fh/myoutlook/refresh_nontraites";
        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		    data:dataString,
		    success: function(html){ 
				$('.mail-outlook-notraite342').html(html);
		    }
		    
	    });
	}

	function refresh_mails_nolus(count=1){
		var adresse="<?php echo base_url();?>fh/myoutlook/refresh_nonlus/"+count;
		
        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		    success: function(html){ 
		    	if(count==1){
			   
					$('.mail-outlook-nolus3242').html(html);
		    	}else{
			    
		    		$('.mail-outlook-nolus3242').closest('li').find('.drop-content').html(html);
		    	}
		    }
		    
	    });
	}

	/* depot */
	function load_liste_depots(){

		jQuery('section[class=view_depot777]').each(function()
	    { 
			var section = $(this);
			var id_demande = section.find('.table_liste_depots').attr('id_demande');
			var view = section.find('.table_liste_depots').attr('view');
			var search = section.find('.depot-input-search').val();
			var container = $(this).find('.table_liste_depots');

			var data = 'search='+search+'&view='+view;

			load_liste_depot(id_demande, data, container);
			
	    });
	}

	function load_liste_depot(id_demande, data, container){
		jQuery.ajax
		({  
			type:'POST',
			url: "<?php echo base_url();?>fh/myoutlook/get_liste_depots/"+id_demande+"/1",
			data: data,
			cache: false,
			
			beforeSend: function(){
			  container.html('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i> en chargement...').fadeIn();
			},

			success: function(html)
			{ 
			  container.html(html);
			}
	    });
	}

	/* enddepot */


</script>