<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="container text-center">
<h3> Le ticket n°<?=($ticket->id_ticket)?>
<?php if(is_image($ticket->url_file)):?>
    <img width="50px" src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$ticket->url_file;?>" class="" alt="<?=$ticket->name?>">

<?php else:?>
    <i class="fas fa-file fa-3x text-<?php echo $themes->ticket->color;?>"></i> 
<?php endif;?> 

a été ajouté à la demande n°<?=$id_demande?></h3>
<div class="mt-5 row justify-content-center">

        <div class="col-4">
            <p><b>Que voulez-vous faire?</b></p>
            
    
            <div class="list-group">
                 
                    <a href="<?=base_url()?>/ticket/liste_demande/<?=$ticket->id_ticket?>" class="list-group-item list-group-item-action btn btn-<?php echo $themes->ticket->color;?>  btn-sm text-white">Ajouter le ticket n°<?=($ticket->id_ticket)?> à une autre demande</a>
                    <a href="<?=base_url("demande/fiche/$id_demande")?>" class="list-group-item list-group-item-action btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white"><?php echo $themes->demande->icon;?> Ouvrir la demande n°<?=$id_demande?></a>
            </div>
        </div>
</div>
</div>

<?php $this->endSection(); ?>