<form action="<?=base_url()?>/doublon/search_by_field/<?=$type?>" method="get" entity="<?php echo $entity;?>" class="select_field_form field_critere_doublon mt-4">
        <p><b>Entité <?php echo $entity;?></b></p>
        <p><i><b>Option 1: </b>Sélectionner les champs qui doivent être comparés</i></p>
        <div class="card card-default">
            <div class="row card-body">
                            <?php foreach($fields as $index_field=>$label_field):?>
                            <div class="col-lg-3">
                                <span style="">
                                    <input  type="checkbox" name="<?php echo $index_field;?>" value="1"> <?php echo strip_tags(str_replace("Détails ",NULL,$label_field));?></input>
                                </span>
                            </div>
                            <?Php endforeach;?>
       
                    <div style='margin-top: 20px;' class="text-center">
                    <button class="btn btn-sm btn-dark" type="submit">Rechercher les doublons à fusionner</button>
                </div>
            </div>
        </div>
</form>



<div class="mb-2 mt-5"><i><b>Option 2: </b> Rechercher des <?=$entity?>s à fusionner</i></div>

<div class="row container_fusion">
     <div class="col-lg-8 banData">
        <div class="container_pager_ajax">
          <?=view($path."/d_form_search",["entity"=>$type])?>
        </div>
     </div>  

     <div class="col-lg-4">
        <div class="card card-default">
             <h5 class="card-header">Fiches à fusionner</h5>
             <div class="card-body">
                <form action="<?=base_url()?>/doublon/get_tableau_fusion/<?=$type?>" method="get" class="mt-3">
                <input type="hidden" name="tableau_fusion_direct" value="1">
                    <div class="no_fiche"><i>Aucune fiche sélectionnée</i></div>
                    <div class="id_doublon_container"></div>
                    <div style='margin-top: 20px;' class="text-center">
                        <button class="btn btn-sm btn-dark" type="submit">Fusionner</button>
                    </div>
                </form>
             </div>
             
        </div> 
     </div>                          
</div>



<hr>

