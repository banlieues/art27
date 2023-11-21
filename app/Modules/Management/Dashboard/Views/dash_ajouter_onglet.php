<div class="container mt-2">

<div class="card card-default">
    <div class="card-header"> <h5>Ajouter un onglet</h5></div>
    <div class="card-body">
	<div style="margin-top:20px" class="row">
	    <div size="12" class="col-lg-12 lg_dynamique c_table">
		
		    <form method='post' action='<?php echo base_url();?>/dashboard/form_insert_onglet' style='padding:0 10px 20px 10px' class="form_option_insert_onglet">
			<input name="id_user" type="hidden" value="<?php echo $id_user;?>">
		 
			<div class="form-group">
		      <label for="exampleInputEmail1"><strong>Nom de l'onglet</strong></label>
		      <input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Titre" value="">
		    </div>
				    
			<button type='submit' class='btn btn-success btn-sm mt-2'>Enregister</button>
		   
		  </form>

	    </div>
	</div>

	</div>
</div>
</div>



