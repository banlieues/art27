<?php if(!empty($fields)):?>
    <select url="<?=base_url();?>/queries/get_input" style="max-width:250px"  class='r_liste_champ e_<?php echo $entity;?> select_r_chosen' name="champ<?php if(!is_null($i)): echo "_##$i"; endif; ?>">
        <option value='0' selected>Choisir un champ</option>
        <?php foreach($fields as $field=>$label): ?>
                <option <?php if($field_select===$field):?>selected<?php endif;?> value="<?php echo $field;?>"><?php echo $label;?></option>
        <?php endforeach;?>
    </select>
<?php else:?>
    <b>Erreur pas de champs trouvés</b>
<?php endif;?>