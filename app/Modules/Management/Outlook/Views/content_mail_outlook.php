<div class="mail_content">
	<div class="mail-body"> 
		<?php if($courriel==1): ?>
		<div class="panel-footer" id_message="<?=$id_message;?>"> 
			<a href="<?= base_url();?>fh/myoutlook/get_table" class="label label-info content_list_outlook" >
				<i class="fa fa-chevron-left"></i> Retour à la liste</a>
			<a href="#" class="label label-primary call_new_message_request" type="response"><i class="fa fa-reply"></i> Repondre</a>  
			<a href="#" class="label label-warning call_new_message_request" type="transfere"><i class="fa fa-arrow-right"></i> Transférer</a>
		</div> 
		<?php endif;?>
		
		<iframe width="100%" height="1500px" src="<?=base_url();?>fh/myoutlook/get_message_iframe/<?=$id_message;?>/<?=$courriel;?>"></iframe>
	</div>
</div>