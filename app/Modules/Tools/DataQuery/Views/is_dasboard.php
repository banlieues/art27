<?php if(!is_null($is_dasboard)):?>

<form class="form_ajax_modif" action="<?=base_url()?>/queries/is_dasboard/<?=$id_requete?>">
    <select class="select_modif" name="is_dasboard">
        <option <?php if($is_dasboard==1):?>selected<?php endif;?> value="1">Sur Tableau de bord</option>
        <option <?php if($is_dasboard==0):?>selected<?php endif;?> value="0">Pas sur Tableau de bord</option>
    </select>
</form>
<?php else:?>
    <span class="text-danger"><b>Erreur !</b> Pas encoder!</span>
<?php endif;?>


