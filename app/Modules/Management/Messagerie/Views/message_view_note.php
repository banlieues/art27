<?php $request = \Config\Services::request(); ?>

<?php if (!$request->isAJAX()):?>
    <?php $this->extend("Layout\index"); ?>
    <?php $this->section("body"); ?>
<?php endif;?>

<div class="card">
    <div class="card-header">
            <small style="font-size:12px"  class="text-body-secondary">
                        <span class="message_date">
                            Note du :
                            <?php if(!empty($message->date_created)):?>
                                <?=convert_date_en_to_fr_with_h($message->date_created);?>
                           
                            <?php endif;?>
                        </span>  
                            <small>(#<?=$message->id?>)
                        </small>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        De: <b><span class=""><?=$message->prenom?> <?=$message->nom?></span></b>
                    </div>
                 
                </div>
            
                Objet: <b><span class="message_sujet"><?=$message->subject?></span></b>
                
            </small>   

            <?php if($message->entity=="demande"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>demande/fiche/<?=$message->id_entity?>" class="btn btn-<?=$themes->demande->color?> btn-sm float-end"> <?=$themes->demande->icon?> Demande n°<?=$message->id_entity?></a>
       
            <?php endif;?>

            <?php if($message->entity=="bien"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>bien/fiche/<?=$message->id_entity?>" class="btn btn-<?=$themes->bien->color?> btn-sm float-end"> <?=$themes->bien->icon?> Bien n°<?=$message->id_entity?></a>
       
            <?php endif;?>

            <?php if($message->entity=="contact"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>contact/fiche/<?=$message->id_entity?>" class="btn btn-<?=$themes->contact->color?> btn-sm float-end"> <?=$themes->contact->icon?> Bien n°<?=$message->id_entity?></a>
       
            <?php endif;?>

            <?php if($message->entity=="rdv"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>rdv/form_rdv/<?=$message->id_entity?>" class="btn btn-<?=$themes->rdv->color?> btn-sm float-end"> <?=$themes->rdv->icon?> Bien n°<?=$message->id_entity?></a>
       
            <?php endif;?>

            <?php if($message->entity=="tache"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>tache/form_tache/<?=$message->id_entity?>" class="btn btn-<?=$themes->tache->color?> btn-sm float-end"> <?=$themes->tache->icon?> Bien n°<?=$message->id_entity?></a>
       
            <?php endif;?>

    </div>

    <div class="card-body">
        <div class="message_body"><?php echo nl2br($message->content)?></div>
    </div>
</div>

<?php if (!$request->isAJAX()):?>
    <?php $this->endSection(); ?>
<?php endif;?>