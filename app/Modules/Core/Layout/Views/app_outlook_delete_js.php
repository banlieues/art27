<script>
    jQuery(document).ready(function()
    {
	
	$(document).off('click', '.email_outlook_delete').on('click', '.email_outlook_delete', function(e){
			e.preventDefault();
			var id_demande=$(this).attr("id_demande");
			var id_message=$(this).closest("tr").attr("id_message");
			var dataString="id_message="+id_message+"&id_demande="+id_demande;
			var thiss = $(this);
        
			var r = confirm("Etes-vous sûr de vouloir supprimer cette liaison ?");
			if (r == true) {
				jQuery.ajax
				({  
					type:'POST',
					url: "<?=base_url();?>/outlook/move_mailoutlook_db",
					data: dataString,
					cache: false,
					

					success: function(html)
					{ 
						if(html){
							thiss.closest('td').html(html);
						}
					}
				});
			} 
         
		});

		$(document).off('click', '.email_outlook_delete_def').on('click', '.email_outlook_delete_def', function(e){
			e.preventDefault();
			var id_message=$(this).closest("tr").attr("id_message");
			var dataString="id_message="+id_message;
			var thiss = $(this);
        
			var r = confirm("Etes-vous sûr de vouloir supprimer ce message de la base de données ?");
			if (r == true) {
				jQuery.ajax
				({  
					type:'POST',
					url: "<?=base_url();?>/outlook/delete_mailoutlook_db",
					data: dataString,
					cache: false,
					

					success: function(html)
					{ 
						if(html=='success'){
							thiss.closest('tr').remove();
						}
					}
				});
			} 
         
		});
	
	
	
    } );
    
    

</script>   