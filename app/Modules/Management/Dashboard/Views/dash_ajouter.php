<script>
$(document).ready( function () {
  
    
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

<script src="<?php echo base_url('packages/xncolorpicker-main/dist/xncolorpicker.min.js');?>"></script>

<?php $ldashboard = \Config\Services::ldashboard();?>
<?php

$color_background="#ecf0f1";
	$color_police="#000000";
	$i=55959595959595;
	?>

	<style>
		.fcolorpicker-curbox{
		border: 1px solid black !important;
	}
	</style>

<div class="container mt-2">

<div  style=margin-top:20px" class="alert alert-info alert-no-registrer">Pour créer une table choississez une requête!</div>


<div style="border-color: <?php echo $color_background;?>" class="card card-default card-information card-<?php echo $i;?>">
    <div style="background-color:<?php echo $color_background;?>;pointer:cursor" class="p-2 card_grab card-heading card-heading-<?php echo $i;?>"> <h5 style="color:<?php echo $color_police;?>" class="titre-bande-<?php echo $i;?>">Ajouter une table</h5></div>
    <div class="card-body">
	<div style="margin-top:20px" class="row">
	    <div size="12" class="col-lg-12 lg_dynamique c_table">

		    <form id_user='<?php echo $id_user;?>' id_onglet='<?php echo $id_onglet;?>' style='padding:0 10px 20px 10px' class="form_option_insert">
				    <input name="id_user" type="hidden" value="<?php echo $id_user;?>">
					<input class="input_color_background<?php echo $i;?>" name="color_background" type="hidden" value="<?php echo $color_background?>">
					<input class="input_color_police<?php echo $i;?>" name="color_police" type="hidden" value="<?php echo $color_police;?>">

		    <div class="form-group mb-2">
	      <label for="exampleInputEmail1"><b>Onglet</b></label>
	      
	      <?php $onglets=$ldashboard->get_onglet($id_user);?>
	      
	      <select class="mon_onglet mb-2" name="id_user_table_onglet">
	      <?php foreach($onglets as $onglet):?>
	      <option <?php if($id_onglet==$onglet->id_user_table_onglet):?>selected<?php endif;?> value="<?php echo $onglet->id_user_table_onglet;?>"><?php echo $onglet->nom;?></option>
	      
	      <?php endforeach;?>
	      </select>
	      
	      
	    </div>
		<div style="margin-bottom:20px" class="row">
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
			<div class="form-group mt-2">
		      <label for="exampleInputEmail1"><b>Titre</b></label>
		      <input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Titre" value="">
		    </div>
				    
		    <div class="form-group mt-2">
		      <label for="exampleInputEmail1"><b>Choisir une requête</b></label>
		      <select class="form-control select_requete" name="id_requete">
			   <option value="0">Choisir</option>
			<?php foreach($requetes as $requete):?>
			   <option value="<?php echo $requete->id_requete;?>"><?php echo $requete->nom;?></option>
			<?php endforeach;?>
		      </select>
		    </div>	
				    
		    <div class="form-group load_field mt-2">
			
		    </div>
		      <div class="load_filtre">
                               
                             
                            </div>

		    <div class="form-group mt-2">
		      <label>
			  <label style="margin-right: 30px"><b>Largeur page</b></label>
			  <label class="radio-inline">
			    <input value="6" name="size" type="radio" checked> Moitié de page
			</label>
			  <label class="radio-inline">
			    <input value="12" name="size" type="radio" > Sur toute la page
			</label>
			
		      </label>
		    </div>
		    <button style="display:none" type="submit" class="mt-2 btn btn-success btn-sm soumettre_table">Enregistrer</button>
		  </form>

	    </div>
	</div>

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
			$(".card-<?php echo $i;?>").css("border-color",hex);
			//$(".card-body-<?php echo $i;?>").css("background-color",hex);
			
		
			
        },
        onConfirm:function(color){
            console.log("change",color)
			var hex=color.color.hex;
			$(".card-heading-<?php echo $i;?>").css("background-color",hex);
			$(".card-<?php echo $i;?>").css("border-color",hex);
			$(".input_color_background<?php echo $i;?>").val(hex);
			
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
			$(".input_color_police<?php echo $i;?>").val(hex);
		

        }
    })

    //document.querySelector("#setcolor").onclick=function(){noinputterfont<?php echo $i;?>.setColor('#ffff00');}
  
  


  
   
   

</script>
