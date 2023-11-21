<div style="background-color:white; padding:20px" class="text-center container_lu_lu">
<?php $date_limite=strtotime("2021-12-06");
								$date_mail=strtotime($message->created_datetime);
								//echo $date_limite; die($date_mail);
								if($date_mail>=$date_limite)
								{
									?>
	<span class="cestlu">
		<b class="text-success">Mail lu</b> <button id_message=<?=$message->id_primary?>  class="btn_changement_lu" statut="0">Remettre en statut Non lu</button>
	</span>
	<?php } ?>

	<span style="display:none" class="changement_statut_lu"><i class="fa fa-spinner fa-spin"></i> Changement statut en cours…</span>
	<span style="display:none" class="cestpaslu">
		<b class="text-info"><i class="fa fa-circle text-info"></i> Mail non-lu</b> <button class="btn_changement_lu" id_message=<?=$message->id_primary?> statut="1">Remettre en statut lu</button>
	</span>
</div>

<div class="mail-header"> 
	<div class="mail-title"> 
	    <?php if(!empty($message->subject)): echo $message->subject; else : echo "(Pas d'objet)" ; endif;?>
		
	</div>
	
</div>

<div class="mail-info"> 
	<div class="mail-sender"> 
		<span> <b>De</b> <?= affiche_balise($message->sender_mail);?></span>
		<br><span> <b>A</b> <?=$message->to_mail;?></span> - <span> <?php if(isset($message->cc_mail) && !empty($message->cc_mail)): echo '<b>Cc</b> '.$message->cc_mail; endif;?></span> - <span> <?php if(isset($message->bcc_mail) && !empty($message->bcc_mail)): echo '<b>Cci</b> '.$message->bcc_mail; endif;?></span>
    </div> 
    <div class="mail-date">
		 <?php $date = new DateTime($message->received_datetime);
                    echo date_format($date, 'd/m/Y à H:i'); ?>
	</div> 
</div> 

<div class="well">
<?php 
		$this->db->select('*');
		$this->db->from('email_demande_depots');
		$this->db->where('id_message', $message->id_primary);
		$this->db->group_by("email_demande_depots.name");
		$files = $this->db->get()->result();

		echo '<strong>Attachement(s)</strong>';
		if(count($files)>0):
			foreach ($files as $file) {
				if(!empty(trim($file->url_file))):
					echo '<li><a href="'.base_url().'assets/demandes/documents/'.$file->url_file.'" target="_blank">'.$file->name.'</a></li>';
				else :
					echo '<li><a href="'.base_url().'fh/myoutlook/download_base64_document/'.$file->id.'" target="_blank">'.$file->name.'</a></li>';
				endif;
			}
		else : 
				echo '<br>aucun fichier joint dans ce message';
		endif;
?>
</div>

<div class="mail-text" > 
	<?= $message->body_content;?>
</div> 

<script>
$(document).on("click",".btn_changement_lu",function(){
	
	var bt=$(this);
	var container=bt.closest(".container_lu_lu");
	var statut=bt.attr("statut");
	var id_message=bt.attr("id_message");

	var adresse="<?php echo base_url()?>fh/myoutlook/changement_lecture/"+statut+"/"+id_message;

	

	$(".cestlu",container).hide();
	$(".changement_statut_lu",container).show();
	$(".cestpaslu",container).hide();
	

        jQuery.ajax
	    ({	
		    type:'POST',
		    url: adresse,
		   
		    success: function(html){ 
				$(".changement_statut_lu",container).hide()
				if(statut==1)
				{
					$(".cestlu",container).show();
					$(".cestpaslu",container).hide();

				}
				else
				{
					$(".cestlu",container).hide();
					$(".cestpaslu",container).show();
				}
		    }
		    
	    });
})
</script>