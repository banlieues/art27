
<?php if(!empty($taches)):?>
            <div class="list-group-flush">
                <?php foreach($taches as $tache):?>
                    <a href="<?=base_url()?>tache/form_tache/<?=$id_demande?>/<?=$tache->id_tache?>" class="view_tache list-group-item list-group-item-action text-dark">
                        <?php if(!empty($tache->date_tache)&&$tache->date_tache!="0000-00-00 00:00:00"):?>
                            <b><?=convert_date_en_to_fr_with_h($tache->date_tache,true, false)?></b><br>
                        <?php else:?>
                            <b>Date non définie</b><br>
                        <?php endif;?>
                        <?=$tache->sujet?><br>
                        <badge class="badge bg-secondary"><?=$tache->type?></badge> 
                        <badge class="badge bg-secondary"><?=$tache->statut?></badge>
                    </a>
                    <hr class="m-2">
                <?php endforeach;?>    
            </div>
<?php else:?>
    <div class="row p-1 m-2">
        Pas de tâches liées à cette demande
    </div>
<?php endif;?>