<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>



<?php $autorisationManager = \Config\Services::autorisationModel();?>


<div class="row mb-2 mb-xl-3">
    <div class="col-lg-auto">
        <h3 class="fs-4"><?php echo $titleView; ?> (<?=$pager->getTotal();?> résultat<?=plurial_s($pager->getTotal());?>)</h3>
    </div>
    <?php if($id_requete>0):?>
        <h3><?=$nom_requete?> (<?=$id_requete?>)</h3>
    <?php endif;?>
    <div class='col-lg'>
        <div  class="text-lg-end sticky_button">
        <?php if($autorisationManager->is_autorise("requete_r")):?>

            <span class="zone-button-form mt-5">
                <form method="post" action="<?=base_url()?>queries/index/<?=$id_requete?>/<?=$id_requete_provisoire?>">
                    <input name="uri" type="hidden" value="<?=$uri?>">
                    <button type="submit" class="btn btn-sm btn-pink"><i class="<?=icon("queries")?>"></i> Voir le constructeur de requête</button>
                </form>
            </span>   
        <?php endif;?>
            <?php if(!empty($results)):?>
                <span class="zone-button-form mt-5">
                    <a href="<?=base_url()?>queries/export_csv/<?=$id_requete?>/<?=$id_requete_provisoire?>" class="btn btn-sm btn-success"><i class="<?=icon("export-csv")?>"></i> Exporter en CSV </a>
                </span> 
            <?php endif;?>
            <?php if($autorisationManager->is_autorise("requete_r")):?>

            <span class="zone-button-form mt-5">
                <a href="#" class="btn btn-pink btn-sm aModalSaveQuery"><i class="<?=icon("queries")?>"></i> Enregister comme nouvelle requête</a>
                <button style="display:none" href="#" class="btn btn-sm btn-success aConfirmSaveQuery"><i class="<?=icon("queries")?>"></i> Requête enregistrée!</button>
            </span> 
            <?php if($id_requete>0):?>
            <span class="zone-button-form mt-5">
                <a href="#" class="btn btn-info btn-sm text-white aModalUpdateQuery"><i class="<?=icon("queries")?>"></i> Enregistrer modifications éventuelles de la requête n°<?=$id_requete?> </a>
                <button style="display:none" href="#" class="btn btn-sm btn-info aConfirmSaveQuery"><i class="<?=icon("queries")?>"></i> Requête enregistrée!</button>
            </span> 
            <?php endif;?>

            <?php endif;?>
        </div>    
    </div>
</div>


<?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_pink")?><?php endif;?>


<?php if(empty($results)):?>
    <div class="text-center m-5"><h3>Aucun résultat trouvé pour cette requête</h3></div> 
<?php else:?>    

    
    <div class="table-responsive">   
        <table class="table table-striped table-hover my-0 table-sm">
            <tr>
                <?php foreach($labels as $label):?>
                    <th scope="col"><?=$label?></th>
                <?php endforeach;?>    
            </tr>
            <?php foreach($results as $result):?>
                <tr>
                    <?php foreach($result as $r):?>
                        <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;">
                            <?=$r?>
                        </td>
                    <?php endforeach;?>    
                </tr>
            <?php endforeach;?>    
        </table>  
    </div>  

<?php endif;?>
<?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_pink")?><?php endif;?>
  

<hr>
<div class="row">
    <div class="col-auto">
        <h3 class="">Requête SQL</h3>
                    <?=$query?>
       
    </div>
</div>
<hr>


<div class="d-none container-fluid fixed-bottom text-center p-2 footer-form border-top">

        <span class="zone-button-form">
            <a href="<?=$urlRequete?>" class="btn btn-pink"><i class="<?=icon("queries")?>"></i> Voir le constructeur de requête</a>
        </span>   
        <?php if(!empty($results)):?>
        <span class="zone-button-form">
            <a href="<?=$urlExport?>" class="btn btn-success"><i class="<?=icon("export-csv")?>"></i> Exporter en CSV </a>
        </span> 
        <?php endif;?>
        <span class="zone-button-form">
            <a href="#" class="btn btn-pink aModalSaveQuery"><i class="<?=icon("queries")?>"></i> Enregistrer la requête</a>
            <button style="display:none" href="#" class="btn btn-success aConfirmSaveQuery"><i class="<?=icon("queries")?>"></i> Requête enregistrée!</button>
        </span> 


       
</div> 

<?php $this->endSection(); ?>

<?php $this->section("script_injected_foot"); ?>
    <?php view($path."banQueries_js");?>     
                   
<?php $this->endSection(); ?>