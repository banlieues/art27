
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php
	$autorisation_all = $autorisationManager->is_autorise("email_all_r");
	if($autorisation_all==FALSE){
		$mail_user =session()->get("loggedUserMail");
	}
?>

<script type="text/javascript">


	

	$(document).ready(function()
	{

		<?php if(HAS_OUTLOOK):?>
		setTimeout(function(){
			
			refresh_mails_nolus(1);
			refresh_mails_notraites();
			},5);

		<?php endif;?>

		<?php if(HAS_NOTE_NOTIFICATION):?>
		setTimeout(function(){
			
			
			refresh_message_nonlus();
			refresh_message_nolus_affiche();},5);
		<?php endif?>

		//refresh_mails_notraites()
		//setInterval(function(){refresh_mails_notraites();}, 2000*60);
		//refresh_mails_nolus(1);
		//refresh_mails_nolus(0); //liste de message non lus
		//setInterval(function(){refresh_mails_nolus(); refresh_mails_nolus(0);}, 2000*60);
		//refresh_message_nonlus();
		//refresh_message_nolus_affiche();
		//setInterval(function(){refresh_message_nonlus(); refresh_message_nolus_affiche();}, 2000*60);



		$(document).on("change",".btn_changement_lu",function()
		{
			
			var bt=$(this);
			var container=bt.closest(".container_lu_lu").parent();
			var statut=bt.attr("statut");
			var id_message=bt.attr("id_message");

			var adresse="<?php echo base_url()?>outlook/changement_lecture/"+statut+"/"+id_message;



				$(".cestlu",container).hide();
				$(".changement_statut_lu",container).show();
				$(".cestpaslu",container).hide();


				jQuery.ajax
				({	
					type:'POST',
					url: adresse,
					
					success: function(html){ 
					/*	$(".changement_statut_lu",container).hide()
						if(statut==1)
						{
							$(".cestlu",container).show();
							$(".cestpaslu",container).hide();

						}
						else
						{
							$(".cestlu",container).hide();
							$(".cestpaslu",container).show();
						}*/

						container.html(html);

						refresh_mails_nolus(1);

						refresh_mails_notraites();
						refresh_message_nolus_affiche();
						refresh_message_nonlus();
					}
					
				});
		})






   	});

	function refresh_mails_notraites()
	{
		$('.indicator_mail_non_traite').html('<i class="fa fa-circle-notch fa-spin"></i>');
		var dataString = "";
		<?php if($autorisation_all==FALSE){?>
	  		dataString = "origin=<?=$mail_user;?>";
	  	<?php } ?>
		var adresse="<?php echo base_url();?>/outlook/refresh_nontraites";
        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		    data:dataString,
		    success: function(html){ 
				
					$('.indicator_mail_non_traite').html(html);
			
				
		    }
		    
	    });
	}

	function refresh_mails_nolus(count=1,is_progressif=0)
	{
		var adresse="<?php echo base_url();?>/outlook/refresh_nonlus/"+count;
		
		$('.indicator_mail_non_lu').html('<i class="fa fa-circle-notch fa-spin"></i>');

        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
			dataType : "json",
		    success: function(data){ 
			   
					$('.indicator_mail_non_lu').html(data.total);
					
		    
		    		$('.indicator_mail_non_lu').closest('li').find('.list-group').html(data.view);
					
		    	
		    }
		    
	    });
	}


	function refresh_message_nonlus()
	{
		$('.indicator_messagerie_non_lu').html('<i class="fa fa-circle-notch fa-spin"></i>');
		var adresse="<?php echo base_url();?>messagerie/count_non_lu";

        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		    success: function(html)
			{ 
				$('.indicator_messagerie_non_lu').html(html);
		    }
		    
	    });
	}

	function refresh_message_nolus_affiche()
	{
		$('#liste_message_non_lu').html('<div class="text-center pt-5"></div><i class="fa fa-circle-notch fa-spin"></i></div>');
		var adresse="<?php echo base_url();?>messagerie/get_non_lu";

        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		    success: function(html)
			{ 
				$('#liste_message_non_lu').html(html);
		    }
		    
	    });
	}



</script>