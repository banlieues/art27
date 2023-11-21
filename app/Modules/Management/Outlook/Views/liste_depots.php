<table class="table table-bordered table-striped table-inbox table-hover">
	<thead>
		<tr>
			<th></th>
			<th class="col-md-1">Date</th>
			<th class="col-md-1">Date d'échéance</th>
			<th class="col">Titre</th>
			<th class="col">Commentaire</th>
			<th class="col">Type</th>
			<th class="col-md-1">Source</th>
			<?php if(isset($view) && $view=='generale'):?>
			<th>Dossier</th>
			<?php endif;?>
			<th class="col-md-1"></th>
		</tr>
	</thead>
	
	<tbody>
		<?php if(isset($files) && count($files)>0): ?>
			<?php foreach ($files as $file) { ?>
				<tr>
					<td><a href="#" style="color:red;" class="delete_depot" name_depot="<?= $file->name;?>" id_depot='<?= $file->id;?>'><i class="fa fa-trash"></i></a></td>
					<td><?php $date = new DateTime($file->date_created); echo $date->format("d/m/Y H:i:s");?></td>
					<td>
						<?php 
							 
							if($file->date_echeance == "0000-00-00 00:00:00"){
								$date_field = "";
							}else{
								$date = new DateTime($file->date_echeance);
								$date_field =  $date->format("Y-m-d");
								//$date_field =  $file->date_echeance;
							}

							echo $this->fh_dao->get_dao_direct("demande", "date_echeance_file", $date_field, $file->id_demande, $file->id);
						?>
					</td>
					<td>
						 <?php echo $this->fh_dao->get_dao_direct("demande", "nom_file", $file->name, $id_demande, $file->id);?>
				    </td>
					<td> <?php echo $this->fh_dao->get_dao_direct("demande", "commentaire_file", $file->commentaire, $id_demande, $file->id);?></td>
					<td>
						<?php 
						    echo $this->fh_dao->get_dao_direct("demande", "type_file", $file->id_type, $id_demande, $file->id);?>
					</td>
					<td>
						<?php 
							if($file->id_message>0){
								$email = $this->db->select('sender_mail')->where('id_primary', $file->id_message)->get('email_outlook')->result();
								if(isset($email[0]->sender_mail)):
									if ($email[0]->sender_mail == CRMAIL):
										echo 'Email sortant';
									else :
										echo 'Email entrant';
									endif;
								else : 
									echo 'Email';
								endif;
							}else{
								echo 'Dépôt';
							}
						?>
					</td>
					<?php if(isset($view) && $view=='generale'):?>
					<td>
						<?php if(isset($file->id_demande)&&$file->id_demande>0): ?>
						  <button href="<?=base_url();?>fh/fhc_dao/page_view/<?=$file->id_demande;?>fhd_liste_demande" 
                                                          class="fh_dao_fiche btn btn-success btn-xs" fh-descriptor="fhd_liste_demande" 
                                                          href-ajax="<?=base_url();?>fh/fhc_dao/get_fiche/<?=$file->id_demande;?>" 
                                                          href-title="<?=base_url();?>fh/fhc_dao/get_fiche_title/<?=$file->id_demande;?>">
						  <i class="fa fa-link"></i>
					      </button>
					    <?php elseif(isset($file->id_demande)&&isset($file->id_message)&&$file->id_demande==0&&$file->id_message>0): 

					    	$ids_demande = $this->db->from('email_outlook_lien')->where('id_email', $file->id_message)->get()->result();

					    
					    	if(empty($ids_demande)):
					    		echo "---";
					    	else :
					    ?>
					    <button href="<?=base_url();?>fh/fhc_dao/page_view/<?=$ids_demande[0]->id_demande;?>fhd_liste_demande" 
                                                    class="fh_dao_fiche btn btn-success btn-xs" fh-descriptor="fhd_liste_demande" 
                                                    href-ajax="<?=base_url();?>fh/fhc_dao/get_fiche/<?=$ids_demande[0]->id_demande;?>" 
                                                    href-title="<?=base_url();?>fh/fhc_dao/get_fiche_title/<?=$ids_demande[0]->id_demande;?>">
						  <i class="fa fa-link"></i>
					      </button>
					  <?php endif;?>

					    <?php else : ?>---<?php endif;?>
					</td>
					<?php endif;?>
					<td>
						<?php 
							if(!empty(trim($file->url_file))):
								//$url_doc = base_url().'assets/demandes/documents/'.$file->url_file;
								$url_doc = base_url().'fh/myoutlook/download_document/'.$file->id;
							else :
								$url_doc = base_url().'fh/myoutlook/download_base64_document/'.$file->id;
							endif; 
						?>
						<a href="<?=$url_doc;?>" class="btn btn-sm btn-success">Ouvrir</a>
					</td>
				</tr>
			<?php } ?>
		<?php else : ?>
			<tr>
				<td colspan="8" class="text-center"><b>Aucun fichier trouvé pour cette demande</b></td colspan="6">
			</tr>
		<?php endif;?>
		
	</tbody>

</table>

<?php if(!empty($pagination)):?>
      <div class='panel-footer'>
      <?=$sort+1;?> - <?php if(($sort+$limit)>$total_rows): echo $total_rows; else : echo $sort+$limit; endif;?> / <?=$total_rows;?> résultats <?php echo $pagination ?>
    </div>
	<?php endif;?>

<style type="text/css">
	tr td {
		word-wrap: break-word;
	}
</style>

<script type="text/javascript">

	
</script>