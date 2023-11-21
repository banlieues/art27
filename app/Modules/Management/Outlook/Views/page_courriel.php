<?php 
	if(isset($id_demande) && !empty($id_demande)):
?>
	
	<div id="container_messagerie">
   <div class="mail-box">
     <aside style="width:10% !important" class="sm-side"> 
        <div class="inbox-body">
            <a href="#" class="btn btn-compose call_new_message_courriel">
                Nouveau message
            </a>
        </div>
        <ul class="inbox-nav inbox-divider">
            <li class="active">
                <a href="<?=base_url();?>fh/myoutlook/get_table" class="btn_courriel_demande"><i class="fa fa-inbox"></i> Courriels</a>
            </li>
            <li>
                <a href="<?=base_url();?>fh/myoutlook/get_table" class="btn_courriel_demande" type="brouillons"><i class="fa fa-edit"></i> Brouillons</a>
            </li>
        </ul>
      </aside>
      <aside class="lg-side" id="cont_list_mail">
			<div id="content_table_messages" class="panel panel1 panel-default  "></div>
      </aside>
  </div>
</div>

<script>
	$(function () {
	  
		jQuery.ajax
		({  
			type:'POST',
			url: "<?php echo base_url();?>fh/myoutlook/get_table",
			data: "id_demande=<?=$id_demande;?>",
			cache: false,
			
			beforeSend: function(){
			   $("#content_table_messages").html('<i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i> en chargement...').fadeIn();
			},

			success: function(html)
			{ 
			  $("#content_table_messages").html(html);
			}
	    });
		
		<?php if(isset($id_demande)): ?>
		$(document).off('click', '.btn_courriel_demande').on('click', '.btn_courriel_demande', function(e){
			e.preventDefault();

			$(".btn_courriel_demande").closest('li').removeClass('active');
			$(this).closest('li').addClass('active');

			var datastring = "id_demande=<?=$id_demande;?>";
			var type = $(this).attr('type');
			if (typeof type !== typeof undefined && type !== false) {
				datastring = datastring + "&type="+type;
			}
			jQuery.ajax
			({  
				type:'POST',
				url: "<?php echo base_url();?>fh/myoutlook/get_table",
				data: datastring,
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
		<?php endif;?>
		
		//form new message
		  $(document).off('click', '.call_new_message_courriel').on('click', '.call_new_message_courriel', function(e){
			e.preventDefault();

			
			var section_container = $("#content_table_messages");
			var id_demande = <?= $id_demande;?>

			
			section_container.html('<div style="padding:100px; text-align:center" width="100%"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');

		
			var id_message = $(this).attr('id_message');
			var id_msg = 0;
			if (typeof id_message !== typeof undefined && id_message !== false) {
				id_msg = id_message;
			}

			var type_attr = $(this).attr('type');
			var type = "";
			if (typeof type_attr !== typeof undefined && type_attr !== false) {
				type = type_attr;
			}

			$.ajax({
			  'url': '<?=base_url();?>fh/myoutlook/get_form_message/'+id_demande+'/'+id_msg+'/'+type,
			  'type':'POST',
			  //'data': datastring,
			   success : function(html){
				section_container.html(html);
			  }

			});
		  });
		  
		  //form new message
		  $(document).off('click', '.call_new_message_request').on('click', '.call_new_message_request', function(e){
			e.preventDefault();
			
			
	
			
			var section_container = $("#content_table_messages");
			var id_demande = "<?= $id_demande;?>";
			var id_message = $(this).parent().attr('id_message');
			var type = $(this).attr('type');

			
			section_container.html('<div style="padding:100px; text-align:center" width="100%"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');

			$.ajax({
			  'url': '<?=base_url();?>fh/myoutlook/get_form_message/'+id_demande+'/'+id_message+'/'+type,
			  'type':'POST',
			  //'data': data,
			   success : function(html){
				section_container.html(html);
			  }
			});
		  });
		  
		  $(document).off('click', '.content_list_outlook').on('click', '.content_list_outlook',function(e){
			  e.preventDefault();
			  var section_container = $("#content_table_messages");
			  var adresse = $(this).attr('href');
			  
			  section_container.html('<div style="padding:100px; text-align:center" width="100%"><i class="fa fa-spinner fa-pulse fa-2x"></i></div>');
			  
			  $.ajax({
				  'url': adresse,
				  'type':'POST',
				  'data': "id_demande=<?=$id_demande;?>",
				   success : function(html){
					section_container.html(html);
				  }

				});
		  });

		
		
	});

</script>
	
<?php
	endif;
?>

