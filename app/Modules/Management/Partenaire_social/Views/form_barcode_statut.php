<?php $autorisationManager = \Config\Services::autorisationModel();?>


<?php if($autorisationManager->is_autorise("partenaire_social_u")):?>
<form class="form_ajax_modif" action="<?=base_url()?>/partenaire_social/setStatut/<?=$barcode->id_convention_barcode?>">
    <select  id="SelectTypeDocument<?=$barcode->id_convention_barcode?>" data-type-confirm="select" data-value-origin="<?=$barcode->statut?>" data-alert-titre="Changement du statut du code barre" data-alert-content="Modifier le le statut du code barre <?=$barcode->num_code?>" class="select_modif select_change_submit_stip load_reload_page_document_stip" name="statut" >
    <option>Choisir un type</option>
        <?php foreach($statuts as $statut):?>
            <option <?php if($barcode->statut==$statut->id):?>selected<?php endif;?> value="<?=$statut->id?>"><?=$statut->label?></option>
        <?php endforeach;?>    
    </select>
</form>
<?php else:?>
    <?php if($autorisationManager->is_autorise("partenaire_socil_r")):?>
       
        <?php foreach($status as $statut):?>
       <?php if($barcode->statut==$statut->id):?><?=$statut->label?><?php endif;?>
        <?php endforeach;?> 

    <?php endif;?>
<?php endif;?>