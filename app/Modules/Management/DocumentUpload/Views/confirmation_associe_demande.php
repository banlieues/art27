<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="container text-center">
<h3> Le document n°<?=($document->id_document)?>
<?php if(is_image($document->url_file)):?>
    <img width="50px" src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>" class="" alt="<?=$document->name?>">

<?php else:?>
    <i class="fas fa-file fa-3x text-<?php echo $themes->document->color;?>"></i> 
<?php endif;?> 

a été ajouté à la demande n°<?=$id_demande?></h3>
<div class="mt-5 row justify-content-center">

        <div class="col-4">
            <p><b>Que voulez-vous faire?</b></p>
            
    
            <div class="list-group">
                 
                    <a href="<?=base_url()?>/document/liste_demande/<?=$document->id_document?>" class="list-group-item list-group-item-action btn btn-<?php echo $themes->document->color;?>  btn-sm text-white">Ajouter le document n°<?=($document->id_document)?> à une autre demande</a>
                    <a href="<?=base_url("demande/fiche/$id_demande")?>" class="list-group-item list-group-item-action btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white"><?php echo $themes->demande->icon;?> Ouvrir la demande n°<?=$id_demande?></a>
            </div>
        </div>
</div>
</div>

<?php $this->endSection(); ?>