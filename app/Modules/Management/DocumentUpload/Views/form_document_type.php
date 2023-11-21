<?php $autorisationManager = \Config\Services::autorisationModel();?>


<?php if($autorisationManager->is_autorise("document_u")):?>
<form class="form_ajax_modif" action="<?=base_url()?>/document/setTypeDocument/<?=$document->id_document?>">
    <select  id="SelectTypeDocument<?=$document->id_document?>" data-type-confirm="select" data-value-origin="<?=$document->id_type?>" data-alert-titre="Changement du type de document" data-alert-content="Modifier le type du document <?=$document->name?>" class="select_modif select_change_submit_stip load_reload_page_document_stip" name="id_type" >
    <option>Choisir un type</option>
        <?php foreach($type_document as $type):?>
            <option <?php if($document->id_type==$type->name):?>selected<?php endif;?> value="<?=$type->name?>"><?=$type->name?></option>
        <?php endforeach;?>    
    </select>
</form>
<?php else:?>
    <?php if($autorisationManager->is_autorise("document_r")):?>
       
        <?php foreach($type_document as $type):?>
       <?php if($document->id_type==$document->name):?><?=$type->name?><?php endif;?>
        <?php endforeach;?> 

    <?php endif;?>
<?php endif;?>