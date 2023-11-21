<?php $ldashboard = \Config\Services::ldashboard();?>
<?php $champs_index=explode(",",$options->field);?>

<style>
	
</style>


<script>
$(document).ready( function () {

<?php if(IS_AJAX_DATATABLE): ?>
   var <?php echo "tabledata$i"?>= $('#table_data_<?php echo $i;?>').DataTable({
         "language": {
                "url": "<?php echo base_url();?>/public/french_datatable.json"
            },
			"pageLength": <?php echo $options->len?>,
						"order": [[ 3, "desc" ]],
					
	 'processing': true,
	
          'serverSide': true,
          
          'serverMethod': 'post',        
        "ajax": {

            url : "<?php echo base_url();?>/dashboard/load_table_data_ajax/<?php echo $options->id_user_table;?>",

           

        },
	
//	exportOptions: { 
//	    columns: ':visible:not(:eq(2))' 
//	} ,
	//"scrollX": true,
	"scrollY":        "350px",
       
	
	
	    
    });

	
    <?php else:?>   
		
	
   var <?php echo "tabledata$i"?>= $('#table_data_<?php echo $i;?>').DataTable({
	
	
//	exportOptions: { 
//	    columns: ':visible:not(:eq(2))' 
//	} ,
	//"scrollX": true,
	"scrollY":        "350px",

	"pageLength": <?php echo $options->len?>,
	"order": [[ <?php echo $options->index_order?>, "<?php echo $options->sort?>" ]],

	 "language": {
                "url": "<?php echo base_url();?>/public/french_datatable.json"
            },
	
	    
    });
    
 <?php endif;?> 

 $('#table_data_<?php echo $i;?>').on( 'order.dt', function () {
   // alert( 'Table reorder' );
	
	var order = <?php echo "tabledata$i"?>.order();
	var indexcolumn=order[0][0];
	var sort=order[0][1];
	//alert(indexcolumn+ ' '+sort);
} );

$('#table_data_<?php echo $i;?>').on( 'order.dt', function () {
   // alert( 'Table reorder' );
	
	var order = <?php echo "tabledata$i"?>.order();
	var id_user_table=<?php echo $options->id_user_table;?>;
	var dataString="index_order="+order[0][0]+"&sort="+order[0][1]+"&id_user_table="+id_user_table;
	var url="<?php echo base_url();?>/dashboard/update_order";
			jQuery.ajax
			({	
				type:'POST',
				url: url,
				data: dataString,
				cache: false,
				success: function(html)
				{ 
					
				}
			})
	//alert(dataString);
	
} );

$('#table_data_<?php echo $i;?>').on( 'length.dt', function ( e, settings, len ) {
	var id_user_table=<?php echo $options->id_user_table;?>;
	var dataString="len="+len+"&id_user_table="+id_user_table;
	var url="<?php echo base_url();?>/dashboard/update_len";
			jQuery.ajax
			({	
				type:'POST',
				url: url,
				data: dataString,
				cache: false,
				success: function(html)
				{ 
					
				}
			})

   // console.log( 'New page length: '+len );
} );

   // $(".selectfiltre").selectpicker();
    
    $(".selectfiltre").chosen({
            disable_search_threshold: 10,
            search_contains: true,
            no_results_text: "Pas de résultat pour ",
            width: "100%",
			placeholder_text_multiple: "Vous pouvez choisir plusieurs éléments",
         });  
    
} );





</script>




<script src="<?php echo base_url('packages/xncolorpicker-main/dist/xncolorpicker.min.js');?>"></script>


<?php
//event quand on appelle ordering et nb


?>






<?php 

if(!empty($options)):
	$color_background=$options->color_background;
	$color_police=$options->color_police;
else:
	$color_background="#ecf0f1";
	$color_police="#000000";
endif;
?>
<style>
    .dataTables_processing{
        z-index:100000 !important;
        background-color: whitesmoke !important;
    }
	.fcolorpicker-curbox{
		border: 1px solid black !important;
	}

	#r<?php echo $options->id_user_table;?> table.dataTable thead th, #r<?php echo $options->id_user_table;?> table.dataTable thead td
	{
		border-bottom: 1px solid <?php echo $color_background;?>
	}

	#r<?php echo $options->id_user_table;?> .dataTables_wrapper.no-footer .dataTables_scrollBody{
		border-bottom: 1px solid <?php echo $color_background;?>
	}

	.sorting_desc,.sorting_asc{
		background-color:#ccc;


	}

	.sorting_asc {
	background-image: url("<?=base_url()?>/public/DataTables/DataTables-1.10.18/images/sort_asc.png") !important;
}

.sorting_desc {
	background-image: url("<?=base_url()?>/public/DataTables/DataTables-1.10.18/images/sort_desc.png") !important;
}

.sorting{
	background-image: url("<?=base_url()?>/public/DataTables/DataTables-1.10.18/images/sort_both.png") !important;
}	
</style>


<input type="hidden" class="value_id_user_table<?php echo $i;?>" value="<?php echo $options->id_user_table;?>" />

<?php //print_r($options);?>
<?php //echo $options->id_user_table;?>
<div style="border-color: <?php echo $color_background;?>" data-index="<?php echo $options->id_user_table;?>" id="r<?php echo $options->id_user_table;?>" class="card card-default card-information card-<?php echo $i;?>">
  <div style="background-color:<?php echo $color_background;?>;cursor:grab" class="p-2 card_grab card-heading card-heading-<?php echo $i;?>"> 
      <div class="row">
	  <div class="col-lg-8">
              <span class="text-danger-<?php echo $i;?>" style="color:<?php echo $color_police;?>"> 
			  	<?php if($ldashboard->change_sql($id)):?>
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
 					Attention, la requête d'origine a été modifiée! <button  id_user_table="<?php echo $id;?>" class="btn btn-danger btn-xs bt_maj">Mettre à jour</button>
				<?php endif;?>
 			</span>
	      <h5 style="color:<?php echo $color_police;?>" class="titre-bande-<?php echo $i;?>">
		 <span class="mode_no_edition">
		  <a href="#" class="dash_expand"  <?php if($size==12):?>style="display:none"<?php endif;?>><i style="color:<?php echo $color_police;?>"   class="fas fa-expand-alt" aria-hidden="true"></i>
		  </a>
		  <button type="button"
            <?php if($size==6):?>style="display:none"<?php endif;?>
            class="dash_compress dash_compress-<?php echo $i;?> btn btn-link"
            >
            <?php echo fontawesome('down-left-and-up-right-to-center');?>
		  </button>
		  </span>
	    <span class="nom_table"><?php echo $nom_table;?></span>
              
              </h5>
			  
	</div>
	
	<div class="col-lg-2 text-right c_option">
	      <a href="" id_user_table="<?php echo $options->id_user_table;?>" style="color:<?php echo $color_police;?>"  class="mode_no_edition dash_dupliquer cog-<?php echo $i;?>"><i style="color:<?php echo $color_police;?>"  class="fa fa-clone cog-<?php echo $i;?>"></i> Dupliquer</a>
	  </div>

	  <div class="col-lg-2 text-right c_option">
	      <a href="" style="color:<?php echo $color_police;?>"  class="mode_no_edition dash_option_table cog-<?php echo $i;?>"><i style="color:<?php echo $color_police;?>"  class="fa fa-cog cog-<?php echo $i;?>"></i> Modifier</a>
	      <a style="display:none; color:<?php echo $color_police;?>" href="" class="dash_option_table_close cog-<?php echo $i;?>"><i class="fa fa-trash fa-list-<?php echo $i;?>"></i> Fermer</a>
	  </div>

	  
      </div>
      
      </div>
  <div class="card-body card-body-<?php echo $i;?>">
      <div class="table_lecture">
          <?php //print_r($champs_index);?>
    <?php if(!empty($champs_index[0])):?>
	    <table class="tabled center-all" id="table_data_<?php echo $i;?>">
	    <thead>
		<tr>
		    <?php foreach($labels as $label):?>
                            <th><?php echo strip_tags($label,10);?></th>
		    <?php endforeach;?>
		</tr>
	    </thead>
	    <tbody>
             <?php if(!IS_AJAX_DATATABLE):?>   
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
                            <td>
							
                                 <?php //print_r($data);?>
                                <?php //echo $c_index;?>
								<?php $c_index=corrige_index_personne($c_index)?>
								
								<?php if($c_index!="email"):?>
									<?php if(!isset($data->$c_index)):?>
										
										<?php if($c_index=="id_bien"):?>
										<?php //echo debug($data)?>
										<?php endif;?>
								<?php else:?>
                                <?php echo $ldashboard->link(strip_tags($data->$c_index),$c_index);?>
								<?php endif;?>
								<?php else:?>
									<?php echo 'EMAIL!';?>
								<?php endif; ?>
								
                            </td>
			<?php endforeach;?>
		    </tr>
		    <?php endforeach;?>
		<?php endif;?>
                    
             <?php endif;?>       
	    </tbody>
	  
	</table>
          <?php else: ?>
          Pas de champs sélectionnés
          <?php endif;?>
      </div>
      <div style="display:none" class="table_edition">
	  
			<form class="form_option_update">
			    <input name="id_user_table" type="hidden" value="<?php echo $options->id_user_table ?>">
	    
	    <div class="form-group">
	      <label for="exampleInputEmail1"><b>Onglet</b></label>
	        
	      <?php $onglets=$ldashboard->get_onglet($options->id_user);?>
	      
	      <select style='margin-right:200px !important' name="id_user_table_onglet">
	      <?php foreach($onglets as $onglet):?>
	      <option <?php if($options->id_user_table_onglet==$onglet->id_user_table_onglet):?>selected<?php endif;?> value="<?php echo $onglet->id_user_table_onglet;?>"><?php echo $onglet->nom;?></option>
	      
	      <?php endforeach;?>
	      </select>
	       <label for="exampleInputEmail1">Requête d'origine: </label>
                 <?php echo $ldashboard->get_requete_origine($options->id_requete);?>
	      
	    </div>
		<div style="margin-bottom:20px" class="row mt-2">
			<div class="col-lg-3">
				<!--<p> <a id="setcolor">Annuler</a> <a id="getcolor">Obtenir color</a></p>-->
				<label for="exampleInputEmail1"><b>Couleur de fond</b></label>
					<div class="get_color" id="noinputter<?php echo $i;?>"></div>
			</div>


			<div class="col-lg-3">
				<!--<p> <a id="setcolor">Annuler</a> <a id="getcolor">Obtenir color</a></p>-->
				<label for="exampleInputEmail1"><b>Couleur de police</b></label>
					<div class="get_color" id="noinputterfont<?php echo $i;?>"></div>
			</div>
		  </div>
		


		
                       
	    <div class="form-group mb-2">
	      <label for="exampleInputEmail1"><b>Titre</b></label>
	      <input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Titre" value="<?php echo htmlspecialchars($options->nom);?>">
	    </div>
	    <div class="form-group">
		<?php //print_r($options->query);?>
	    <?php $fields_orig=explode(",",$options->field_orig);?>
	    <?php $fields=explode(",",$options->field);?>
		<div class="row">
		<?php foreach($fields_orig as $field_orig):?>
		    <div class="col-lg-3">
			<input <?php if(in_array($field_orig,$fields)):?>checked<?php endif;?> type="checkbox" name="field[]" value="<?php echo $field_orig;?>"> 
			    <?php echo $ldashboard->convert_label($field_orig);?> <small><i>(sql:<?php echo $field_orig;?>)</i></small>
		    </div>
		<?php endforeach;?>
		</div>
	    </div>
	
		<hr>
	   <?php if(!empty(strstr(str_replace("`",null,$options->query),"FROM demande" ))||!empty(strstr(str_replace("`",null,$options->query),"LEFT JOIN demande"))): ?>

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
	    <div class="row">
		<?php if(!empty(strstr(str_replace("`",null,$options->query),"FROM demande" ))||!empty(strstr(str_replace("`",null,$options->query),"LEFT JOIN demande"))): ?>
		<div class="col-lg-4">
		    
		<?php echo $ldashboard->get_filtre_type_demande($options->type_demande);?>
		</div>
		<div class="col-lg-4">
		    
		<?php echo $ldashboard->get_filtre_type_accompagnement($options->type_accompagnement);?>
		</div>
                
                
<!--                <div class="col-lg-4">
		    
		<?php //echo $ldashboard->get_filtre_type_is_not_accompagnement_specifique($options->type_is_not_accompagnement_specifique);?>
		</div>-->
                
                
		<?php endif;?>
		
		<?php if(!empty(strstr(str_replace("`",null,$options->query),"FROM rdv" ))||!empty(strstr(str_replace("`",null,$options->query),"LEFT JOIN rdv"))): ?>
		

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_user_rdv($options->user_rdv);?>

                        </div>

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_statut_rdv($options->statut_rdv);?>

                        </div>
		<?php endif;?>
                
                   <?php if(!empty(strstr(str_replace("`",null,$options->query),"FROM tache" ))||!empty(strstr(str_replace("`",null,$options->query),"LEFT JOIN tache"))): ?>
		

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_user_tache($options->user_tache);?>

                        </div>

                        <div class="col-lg-4">

                            <?php echo $ldashboard->get_filtre_statut_tache($options->statut_tache);?>

                        </div>
		<?php endif;?>
                
             
                
	    </div>
		 <div class="form-group mt-2">
	      <label>
		  <label style="margin-right: 30px"><b>Largeur page</b></label>
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
	    <button type="submit" class="btn btn-success">Submit</button>
				</div>
				<div style="text-align:right !important"  class="col-lg-6 text-right">
	    <button class="btn btn-danger id_table_delete" id_onglet="<?php echo $options->id_user_table_onglet;?>" id_user="<?php echo $options->id_user;?>" id_user_table="<?php echo $options->id_user_table;?>">Supprimer la table</button>
				</div>
			    </div>
	  </form>
	      
	  
      </div>
  </div>
</div>


<script>
   

   
    var noinputter<?php echo $i;?> = new XNColorPicker({
        color: "<?php echo $color_background;?>",
        selector: "#noinputter<?php echo $i;?>",
        showprecolor: true,//显示预制颜色
        prevcolors: null,//预制颜色，不设置则默认

        historycolornum: 16,//历史条数
        format: 'hex',//rgba hex hsla,初始颜色类型
        showPalette:true,//显示色盘
        show:false, //初始化显示
        lang:'en',// cn 、en
        colorTypeOption:'single',//

        showhistorycolor: true,//show history colors
        hideInputer:true,//hide input field


        onError: function (e) {

        },
        onCancel:function(color){
            console.log("change",color)
			var hex=color.color.hex;
			$(".card-heading-<?php echo $i;?>").css("background-color",hex);
			$(".card-<?php echo $i;?>").css("border-color",hex);
			//$(".card-body-<?php echo $i;?>").css("background-color",hex);
        },
        onChange:function(color){
			
			
            console.log("change",color)
			var hex=color.color.hex;
			$(".card-heading-<?php echo $i;?>").css("background-color",hex);
			$("#r<?php echo $options->id_user_table;?> table.dataTable thead th").css("border-color",hex); 
			$("#r<?php echo $options->id_user_table;?> table.dataTable thead td").css("border-color",hex);
			$("#r<?php echo $options->id_user_table;?> .dataTables_wrapper.no-footer .dataTables_scrollBody").css("border-color",hex);
			$(".card-<?php echo $i;?>").css("border-color",hex);
			//$(".card-body-<?php echo $i;?>").css("background-color",hex);
			
		
			
        },
        onConfirm:function(color){
            console.log("change",color)
			var hex=color.color.hex;
			$(".card-heading-<?php echo $i;?>").css("background-color",hex);
			$(".card-<?php echo $i;?>").css("border-color",hex);
			var val_id_user_table=$(".value_id_user_table<?php echo $i;?>").val();
			
			var url="<?php echo base_url();?>/dashboard/update_color_background";
			var dataString="color="+hex+"&id_user_table="+val_id_user_table;
			jQuery.ajax
			({	
				type:'POST',
				url: url,
				data: dataString,
				cache: false,
				success: function(html)
				{ 
					
				}
			})
			//$(".card-body-<?php echo $i;?>").css("background-color",hex);
        }
    })

    //document.querySelector("#setcolor").onclick=function(){noinputter<?php echo $i;?>.setColor('#ffff00');}
    
  

	var noinputterfont<?php echo $i;?> = new XNColorPicker({
        color: "<?php echo $color_police;?>",
        selector: "#noinputterfont<?php echo $i;?>",
        showprecolor: true,//显示预制颜色
        prevcolors: null,//预制颜色，不设置则默认

        historycolornum: 16,//历史条数
        format: 'hex',//rgba hex hsla,初始颜色类型
        showPalette:true,//显示色盘
        show:false, //初始化显示
        lang:'en',// cn 、en
        colorTypeOption:'single',//

        showhistorycolor: true,//show history colors
        hideInputer:true,//hide input field


        onError: function (e) {

        },
        onCancel:function(color){
            var hex=color.color.hex;
			$(".cog-<?php echo $i;?>").css("color",hex);
			$(".fa-list-<?php echo $i;?>").css("color",hex);
			$(".titre-bande-<?php echo $i;?>").css("color",hex);
			$(".dash_compress-<?php echo $i;?>").css("color",hex);
			$(".fa-expand-alt-<?php echo $i;?>").css("color",hex);
			$(".text-danger-<?php echo $i;?>").css("color",hex);
        },
        onChange:function(color){
			
			
           
			var hex=color.color.hex;
			$(".cog-<?php echo $i;?>").css("color",hex);
			$(".fa-list-<?php echo $i;?>").css("color",hex);
			$(".titre-bande-<?php echo $i;?>").css("color",hex);
			$(".dash_compress-<?php echo $i;?>").css("color",hex);
			$(".fa-expand-alt-<?php echo $i;?>").css("color",hex);
			$(".text-danger-<?php echo $i;?>").css("color",hex);
			
		
			
        },
        onConfirm:function(color){
           
			var hex=color.color.hex;
			$(".cog-<?php echo $i;?>").css("color",hex);
			$(".titre-bande-<?php echo $i;?>").css("color",hex);
			$(".dash_compress-<?php echo $i;?>").css("color",hex);
			$(".fa-expand-alt-<?php echo $i;?>").css("color",hex);
			$(".text-danger-<?php echo $i;?>").css("color",hex);
			var val_id_user_table=$(".value_id_user_table<?php echo $i;?>").val();
			
			var url="<?php echo base_url();?>/dashboard/update_color_font";
			var dataString="color="+hex+"&id_user_table="+val_id_user_table;
			jQuery.ajax
			({	
				type:'POST',
				url: url,
				data: dataString,
				cache: false,
				success: function(html)
				{ 
					
				}
			})

        }
    })

    //document.querySelector("#setcolor").onclick=function(){noinputterfont<?php echo $i;?>.setColor('#ffff00');}
  
  


  
   
   

</script>




