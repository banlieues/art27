<?php if(!empty($entities)):?>
    <select url="<?=base_url()?>/queries/get_list_select_field" style="max-width:250px" class='r_liste_entity select_r_chosen' name="entity_##<?=$i?>">
        <option value="0">Choisir une entité</option>
        <?php foreach($entities as $label=>$entity):?>
            <option <?php if($entity_select==$label):?>selected<?php endif;?> value="<?php echo $label;?>"><?php echo $entity;?></option>
        <?php endforeach;?>
    
    </select>
<?php else:?>
    <b>Erreur! Pas d'entités trouvées</b>
<?php endif;?>

