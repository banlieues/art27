<?php $ldashboard = \Config\Services::ldashboard();?>
<?php debug($ldashboard->test(),true);?>
<script>
$(document).ready( function () {
       
   $('#table_data_<?php echo $i;?>').DataTable({
	//"lengthMenu": [ 10, 25, 50, 75, 100 ],
	"pageLength": 20,

	
//	exportOptions: { 
//	    columns: ':visible:not(:eq(2))' 
//	} ,
	//"scrollX": true,
	"scrollY":        "350px",
       
	
	
	  "language": {
                "url": "<?php echo base_url();?>/public/french_datatable.json",
				
            }
	    
	    
    });
    
  
	//$(".selectfiltre").selectpicker();
    
$(".selectfiltre").chosen({
            disable_search_threshold: 10,
            search_contains: true,
            no_results_text: "Pas de résultat pour ",
            width: "100%",
			placeholder_text_multiple: "Vous pouvez choisir plusieurs éléments",
         });  
    
} );
</script>
<style>

</style>
<?php $champs_index=explode(",",$options->field);?>
<?php //print_r($datas);?>
<div data-index="<?php echo $options->id_user_table;?>" id="r<?php echo $options->id_user_table;?>" class="card card-default card-information">
  <div class="card-heading"> 
      <div class="row">
	  <div class="col-lg-10">
              
	      <h5>
		 
		  <a href="#" class="dash_expand"  <?php if($size==12):?>style="display:none"<?php endif;?>><i class="fas fa-expand-alt" aria-hidden="true"></i>
		  </a>
		  <button type="button"
            <?php if($size==6):?>style="display:none"<?php endif;?>
            class="dash_compress btn btn-link link-dark"
            >
            <?php echo fontawesome('down-left-and-up-right-to-center');?>
            </button>
	    <?php echo $nom_table;?></h5>
	</div>
	  <div class="col-lg-2 text-right c_option">
	      <a href="" class="dash_option_table"><i class="fa fa-cog"></i></a>
	      <a style="display:none" href="" class="dash_option_table_close"><i class="fa fa-list"></i></a>
	  </div>
      </div>
      
      </div>
  <div class="card-body">
      <div class="table_lecture">
	    <table class="tabled center-all" id="table_data_<?php echo $i;?>">
	    <thead>
		<tr>
		    <?php foreach($labels as $label):?>
			<th><?php echo strip_tags(tronque_text($label,10));?></th>
		    <?php endforeach;?>
		</tr>
	    </thead>
	    <tbody>
                
                        <?php if(!isset($datas[0])): ?>
                        <tr>
                             <?php foreach($labels as $label):?>
                            <td><span style="color:grey">No&nbsp;data</span></td>
                            <?php endforeach;?>
                        </tr>
                        <?php else:?>
                            <?php foreach($datas as $data):?>
                            <tr>
                                <?php foreach($champs_index as $c_index):?>
                                <td><?php echo $ldashboard->link(strip_tags($data->$c_index),$c_index);?></td>
                                <?php endforeach;?>
                            </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                   
	    </tbody>
	</table>
      </div>
      <div style="display:none" class="table_edition">
	  
			<form class="form_option_update">
			    <input name="id_user_table" type="hidden" value="<?php echo $options->id_user_table ?>">
	    
	    <div class="form-group">
	      <label for="exampleInputEmail1">Onglet</label>
	      
	      <?php $onglets=$ldashboard->get_onglet($options->id_user);?>
	      
	      <select name="id_user_table_onglet">
	      <?php foreach($onglets as $onglet):?>
	      <option <?php if($options->id_user_table_onglet==$onglet->id_user_table_onglet):?>selected<?php endif;?> value="<?php echo $onglet->id_user_table_onglet;?>"><?php echo $onglet->nom;?></option>
	      
	      <?php endforeach;?>
	      </select>
	      
	      
	    </div>
	    <div class="form-group">
	      <label for="exampleInputEmail1">Titre</label>
	      <input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Titre" value="<?php echo $options->nom;?>">
	    </div>
	    <div class="form-group">
		<?php //print_r($options->query);?>
	    <label for="exampleInputPassword1">Champs à afficher</label><br>
	    <?php $fields_orig=explode(",",$options->field_orig);?>
	    <?php $fields=explode(",",$options->field);?>
		<div class="row">
		<?php foreach($fields_orig as $field_orig):?>
		    <div class="col-lg-3">
			<input <?php if(in_array($field_orig,$fields)):?>checked<?php endif;?> type="checkbox" name="field[]" value="<?php echo $field_orig;?>"> <?php echo $ldashboard->convert_label($field_orig);?>
		    </div>
		<?php endforeach;?>
		</div>
	    </div>
	   <?php if(!empty(strstr($options->query,"FROM demande" ))||!empty(strstr($options->query,"LEFT JOIN demande"))): ?>

	    <div class="row">
		<div class="col-lg-4">
		    <?php echo $ldashboard->get_filtre_utilisateur_en_charge($options->user_charge);?>
		</div>
		<div class="col-lg-4">
		    <?php echo $ldashboard->get_filtre_utilisateur_back_up($options->user_backup);?>

		</div>
			<div class="col-lg-4">
		<?php echo $ldashboard->get_filtre_statut_demande($options->statut_demande);?>

		</div>
	    </div>
	    <?php endif;?>
	   
		<?php if(!empty(strstr($options->query,"FROM demande" ))||!empty(strstr($options->query,"LEFT JOIN demande"))): ?>
		 <div class="row">
                            <div class="col-lg-4">
		    
		<?php echo $ldashboard->get_filtre_type_demande($options->type_demande);?>
		</div>
		<div class="col-lg-4">
		    
		<?php echo $ldashboard->get_filtre_type_accompagnement($options->type_accompagnement);?>
		</div>
                 </div>
		<?php endif;?>
		
		<?php if(!empty(strstr($options->query,"FROM rdv" ))||!empty(strstr($options->query,"LEFT JOIN rdv"))): ?>
		 <div class="row">
                            <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_user_rdv($options->user_rdv);?>

                        </div>
                
                
		<div class="col-lg-4">
		    
		<?php echo $ldashboard->get_filtre_statut_rdv($options->statut_rdv);?>

                </div></div>
		<?php endif;?>
                
                <?php if(!empty(strstr($options->query,"FROM tache" ))||!empty(strstr($options->query,"LEFT JOIN tache"))): ?>
		 <div class="row">

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_user_tache($options->user_tache);?>

                        </div>

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_statut_tache($options->statut_tache);?>

                        </div>
                 </div>
		<?php endif;?>
                
                
	    </div>
		 <div class="form-group">
	      <label>
		  <label style="margin-right: 30px">Largeur page</label>
		  <label class="radio-inline">
		    <input value="12" name="size" type="radio" <?php if($options->size==12):?>checked<?php endif;?>> Sur toute la page
		</label>
		<label class="radio-inline">
		    <input value="6" name="size" type="radio" <?php if($options->size==6):?>checked<?php endif;?>> Moitié de page
		</label>
	      </label>
	    </div>
		
			    <div class="row">
				<div class="col-lg-6">
	    <button type="submit" class="btn btn-default">Submit</button>
				</div>
				<div style="text-align:right !important" class="col-lg-6 text-right">
	    <button class="btn btn-danger id_table_delete" id_onglet="<?php echo $options->id_user_table_onglet;?>" id_user="<?php echo $options->id_user;?>" id_user_table="<?php echo $options->id_user_table;?>">Supprimer la table</button>
				</div>
			    </div>
	  </form>
	      
	  
      </div>
  </div>
</div>



