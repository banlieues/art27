<section class="view_depot777">
<div class="row custom-file well">
	<div class="col-md-6">
	 <form class="upload_email_demande_depots">
	 <label class="custom-file-label" for="customFile">Importer un ou plusieurs fichiers</label><br>
	 <span id="error_upload" class="output"> </span>

	 <div class="form-inline">
  	 	<div class="form-group">
  	 		<input type="file" name="files_email_demande[]" class="upload_file_pub" id="files_email_demande" multiple="">
  	 	</div>
  	 	<div class="form-group">
  	 		<button type="submit" class="btn btn-success btn-xs" id="form_submit_download"><i class="fa fa-redo"></i> Charger</button>
  	 	</div>
  	 </div>
  	</form> 
  </div>
  <div class="col-md-6">
		<div class="input-group">
		  	 <input type="text" class="form-control depot-input-search" placeholder="Rechercher">
		  	 <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
		</div>
	</div>
</div>

<button class="btn btn-success btn-xs refresh_liste_depot"><i class="fa fa-refresh"></i> Actualiser la liste</button>
<?php 
	if(!isset($id_demande)): $id_demande=0; endif;
	if(!isset($view)): $view=""; endif; 
?>
<div class="table_liste_depots" id_demande="<?=$id_demande;?>" view="<?=$view;?>" class="well" style="background-color: white;"></div>
</section>
<script type="text/javascript">
	$(function () {
		
		load_liste_depots();
	});
</script>


<style type="text/css">
	.btn-file {
	  	position: relative;
	  	overflow: hidden;
	}
	.btn-file input[type=file] {
	  	position: absolute;
	  	top: 0;
	  	right: 0;
	  	min-width: 100%;
	  	min-height: 100%;
	  	font-size: 999px;
	  	text-align: right;
	  	filter: alpha(opacity=0);
	  	opacity: 0;
	  	background: red;
	  	cursor: inherit;
	  	display: block;
	}
	input[readonly] {
	  	background-color: white !important;
	  	cursor: text !important;
	}
</style>