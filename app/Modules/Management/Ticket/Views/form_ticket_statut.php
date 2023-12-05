<?php $autorisationManager = \Config\Services::autorisationModel();?>

<?php if($autorisationManager->is_autorise("partenaire_social_u")):?>
<form class="form_ajax_modif" action="<?=base_url()?>ticket/setStatut/<?=$ticket->id_ticket?>">
    <select  id="SelectTypeDocument<?=$ticket->id_ticket?>" data-type-confirm="select" data-value-origin="<?=$ticket->statut_ticket?>" data-alert-titre="Changement du statut du code barre" data-alert-content="Modifier le le statut du ticket n°<?=$ticket->id_ticket?>" class="select_modif select_change_submit_stip load_reload_page_document_stip" name="statut" >
    <option>Choisir un type</option>
        <?php foreach($statut_ticket as $statut):?>
            <option <?php if($ticket->statut_ticket==$statut->id):?>selected<?php endif;?> value="<?=$statut->id?>"><?=$statut->label?></option>
        <?php endforeach;?>    
    </select>
</form>
<?php else:?>
    <?php if($autorisationManager->is_autorise("partenaire_social_r")):?>
       
        <?php foreach($statut_ticket as $statut):?>
       <?php if($ticket->statut_ticket==$statut->id):?><?=$statut->label?><?php endif;?>
        <?php endforeach;?> 

    <?php endif;?>
<?php endif;?>