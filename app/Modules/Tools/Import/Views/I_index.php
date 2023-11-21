<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>
<h3><i class="<?=icon("import")?>"></i> <?=$titleView?></h3>

    <div class="container mt-5">
	<div class="card">
		<div class="card-header text-center">
			<strong>Choisir un fichier CSV à importer</strong>
		</div>
		<div class="card-body">
		<div class="mt-2">
			<?php if (session()->has('message')){ ?>
				<div class="alert <?=session()->getFlashdata('alert-class') ?>">
					<?=session()->getFlashdata('message') ?>
				</div>
			<?php } ?>
			<?php $validation = \Config\Services::validation(); ?>
		</div>	
			<form action="<?=base_url('import/execute') ?>" method="post" enctype="multipart/form-data">
				<div class="form-group mb-3">
					<div class="mb-3">
						<input type="file" name="file" class="form-control" id="file">
					</div>					   
				</div>
				<div class="text-center">
					<input type="submit" name="submit" value="Commencer l'importation" class="btn btn-dark" />
				</div>
			</form>
		</div>
	</div>

		

</div>

<?php if(!empty($tables_importer)):?>	
		<div class="container mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Liste des fichiers en cours de traiment</strong>
				</div>
				<div class="card-body">
					<div class="mt-2">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Nom du fichier</th>
									<th>Date d'importation</th>
									<th>Total lignes</th>
									<th>Importés</th>
									<th>Reste</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($tables_importer as $table):?>
									<tr>
										<td><?=$table->name_file_origin?></td>
										<td><?=convert_date_en_to_fr_with_h($table->created_at)?></td>
										<td><?=$table->number_line?></td>
										<td><?=$table->number_total_import?></td>
										<td><?=$table->number_total_reste?></td>
										<td>
											<a href="<?=base_url()?>/import/table_importation/<?=$table->name_table?>" class="btn btn-dark btn-sm">Voir tableau d'importation</a>
											<a data-view-title="Confirmation" data-view-message=" les données du fichier <?="$table->name_file_origin"?> de la liste de traitement" href="<?=base_url()?>/import/retirer_importation/<?=$table->name_table?>/<?=$table->id_ban_import?>" class="btn btn-danger btn-sm confirmDelete" >Retirer</a>
										</td>

									</tr>
								<?php endforeach;?>		
							</tbody>

						</table>
					</div>
			</div>
		
	<?php endif;?>	


<?php $this->endSection(); ?>

<?php $this->section("js_inject"); ?>

<script>
	
</script>

<?php $this->endSection(); ?>