


<script type="text/javascript">

	$(document).ready(function()
	{

		$(document).on("click",".view_message",function()
		{

			$("#MessageModal").modal('show');

			var adresse=$(this).attr("href");

			jQuery.ajax
			({ 
				type :"POST",
				url : adresse,
				dataType : "json",

				beforeSend: function()
				{
					$("#modal_message_mail").html('<div class="m-5" style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');
				},

				success : function(data)
				{
					$("#modal_message_mail").html(data.html);


				}
			})

			return false;
		})

		$(document).on("click",".view_message_note",function()
		{

			$("#MessageModal").modal('show');

			var adresse=$(this).attr("href");

			jQuery.ajax
			({ 
				type :"POST",
				url : adresse,
			

				beforeSend: function()
				{
					$("#modal_message_mail").html('<div class="m-5" style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>');
				},

				success : function(html)
				{
					$("#modal_message_mail").html(html);
					refresh_message_nonlus();
					refresh_message_nolus_affiche();


				}
			})

			return false;
		})

		$('#modal_message_mail').on('hide.bs.modal', function () {
			$("#modal_message_mail").html('');
		})	
		
		
	});





</script>