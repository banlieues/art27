
<?php if(!empty($messages)):?>
    <?php foreach($messages as $message):?>

        <?php if($message->entity=="demande"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>demande/fiche/<?=$message->id_entity?>" class="list-group-item list-group-item-action color-info"> <?=$themes->demande->icon?> Demande n°<?=$message->id_entity?>
       
          

            <?php elseif($message->entity=="bien"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>bien/fiche/<?=$message->id_entity?>" class="list-group-item list-group-item-action"> <?=$themes->bien->icon?> Bien n°<?=$message->id_entity?>
       
           
            <?php elseif($message->entity=="contact"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>contact/fiche/<?=$message->id_entity?>" class="list-group-item list-group-item-action"> <?=$themes->contact->icon?> Bien n°<?=$message->id_entity?>
       

            <?php elseif($message->entity=="rdv"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>rdv/form_rdv/<?=$message->id_entity?>" class="list-group-item list-group-item-action"> <?=$themes->rdv->icon?> Bien n°<?=$message->id_entity?>
       
          

            <?php elseif($message->entity=="tache"&&$message->id_entity>0):?>
                
                <a href="<?=base_url()?>tache/form_tache/<?=$message->id_entity?>" class="list-group-item list-group-item-action"> <?=$themes->tache->icon?> Bien n°<?=$message->id_entity?>
       
           
            <?php else: ?>
                
                <a href="<?=base_url()?>messagerie/message_view/<?=$message->id?>" class="view_message_note list-group-item list-group-item-action">

            <?php endif;?>

            <div class="d-flex w-100 justify-content-between">
                
                <small>
                    <?=convert_date_en_to_fr_with_h($message->date_created)?><br>
                    De: <?=$message->prenom?> <?=$message->nom?>  <br>
                    Concerne: <?=$message->entity?> <?=$message->id_entity?>
                </small>

            </div>
            <small><?=$message->subject?></small>
        </a>
    <?php endforeach;?>
<?php else:?>
    <div class="text-center p-5">
            Toutes les notes ont été lues!
    </div>
<?php endif;?>

