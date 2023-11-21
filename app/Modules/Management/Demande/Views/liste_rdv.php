  
  <?php //debug($rdvs);?>

  <?php if(!empty($rdvs)):?>
            <div class="list-group-flush">
                <?php foreach($rdvs as $rdv):?>
                    <a href="<?=base_url()?>rdv/form_rdv/<?=$id_demande?>/<?=$rdv->id_rdv?>" class="view_rdv list-group-item list-group-item-action text-dark">
                        <?php if(!empty($rdv->date_rdv_debut)&&$rdv->date_rdv_debut!="0000-00-00 00:00:00"):?>
                            <b><?=convert_date_en_to_fr_with_h($rdv->date_rdv_debut,true, false)?></b><br>
                        <?php else:?>
                            <b>Date non définie</b><br>
                        <?php endif;?>
                        <?=$rdv->titre?><br>
                        <badge class="badge bg-secondary"><?=$rdv->type?></badge> 
                        <badge class="badge bg-secondary"><?=$rdv->statut?></badge>
                    </a>
                    <hr class="m-2">
                <?php endforeach;?>    
            </div>
<?php else:?>
    <div class="row p-1 m-2">
        Pas de RDV liés à cette demande
    </div>
<?php endif;?>