<div class="container mt-2">
	<div class="card card-default">
		<div class="card-header"> <h5>Modifier le nom de l'onglet</h5></div>
		<div class="card-body">
			<div style="margin-top:20px" class="row">
				<div size="12" class="col-lg-12 lg_dynamique c_table">
					<form method='post' action='<?php echo base_url();?>/dashboard/form_update_onglet' style='padding:0 10px 20px 10px' class="form_option_insert_onglet">
						<input name="id_user_table_onglet" type="hidden" value="<?php echo $id_onglet;?>">
						<input name="id_user" type="hidden" value="<?php echo $id_user;?>">

						<div class="form-group">
							<label for="exampleInputEmail1"><b>Nom de l'onglet</b></label>
							<input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Titre" value="<?php echo $name_onglet;?>">
						</div>

						<div class="row mt-2">
							<div class="col-lg-6">
								<button type='submit' class='btn btn-success btn-sm'>Submit</button>
							</div>
							<div style="text-align:right !important" class="col-lg-6 text-right">
								<button class="btn btn-danger btn-sm id_onglet_delete" id_user='<?php echo $id_user;?>' id_onglet="<?php echo $id_onglet;?>">Supprimer l'onglet</button>
							</div>
						</div>
				
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



