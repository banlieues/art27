<?php //$this->extend('Layout\index'); ?>

<?php //$this->section("body"); ?>

<script>
    jQuery(document).ready(function()
    {
		if($("div#accordion_historique>div.accordion-item").length)
        {
			tinysort("div#accordion_historique>div.accordion-item",{attr:'date_sort',order:'desc'});
			
			$(".accordion_historique").collapse();
			
			$(document).off("click",".inverser_ordre").on("click",".inverser_ordre", function(e){
			
					var sens=$(this).attr("statut");
				tinysort("div#accordion_historique>div.accordion-item",{attr:'date_sort',order:sens});
					
					if(sens==="asc"){$(this).attr("statut","desc");} else {$(this).attr("statut","asc")};
					
					
				
				});
		}
	    
	$(document).off("click",".tout_ouvrir").on("click",".tout_ouvrir", function(e){
	  
		    var sens=$(this).attr("statut");
		    
		    var container=$(this).closest(".c_accordion_historique_demande");
		    container.find(".collapse").removeClass("in");
		   if(sens==="in"){ 
		       container.find(".collapse").collapse('show');
		       container.find(".text-ouverture").text("Tout fermer");
		   } 
		   else { 
		        container.find(".collapse").collapse('hide');
		       container.find(".text-ouverture").text("Tout ouvrir");
		   } 
		    
		if(sens==="in"){$(this).attr("statut","out");} else {$(this).attr("statut","in")};
	    });

	
    });

</script>




<?php if(!empty($datas)):?>
<?php $ncollapse=0; ?>

<div class="c_accordion_historique_demande">
    <?php if(count($datas)>1): ?>
	<div><span class='inverser_ordre' statut="asc" style='padding-right: 20px; cursor: pointer; padding-bottom: 10px'><i class="fa fa-sort" aria-hidden="true"></i> Inverser l'ordre</span> 
	<span style='padding-right: 20px; cursor: pointer' class='tout_ouvrir' statut="in"><i class="fa fa-folder-open" aria-hidden="true"></i> <span class="text-ouverture">Tout ouvrir</span></span> 
	</div>
    <?php endif;?>

    <div class="accordion" id="accordion_historique">
        <?php foreach($datas as $data):?>
            <div class="accordion-item" date_sort="<?php echo $data["date"];?>">
                <h2 class="accordion-header" id="heading<?php echo $ncollapse;?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $ncollapse;?>" aria-expanded="false" aria-controls="collapse<?php echo $ncollapse;?>">
                <div class="row">
		       <div class="col-lg-6">
			    <b><?php echo $data["title"];?></b>
		
		       </div>
			<div style="text-align:right" class="col-lg-6">
			    <b><?php echo $data["header"];?></b>
		       </div>
		    </div>
                </button>
                </h2>
                <div id="collapse<?php echo $ncollapse;?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $ncollapse;?>" data-bs-parent="#accordion_historique">
                    <div class="accordion-body">
                        <?php echo $data["corps"];?>
                    </div>
                </div>
            </div>
            <?php $ncollapse=$ncollapse+1;?>
        <?php endforeach?>
    </div>

</div>
<?php else: ?>
<div style="margin:20px; text-align:center"><h3>Pas d'historique à afficher</h3></div>
<?php endif;?>

<?php //$this->endSection(); ?>