<form action="<?=base_url()?>demande/change_statut_demande/<?=$demande->id_demande?>" class="d-flex">
    <select id="ChangeSelectStatut<?=$demande->id_demande?>"
        data-type-confirm="select"
        data-value-origin="<?=$demande->id_demande_statut?>"
        data-alert-titre="Changer le statut de la demande"
        data-alert-content="Modifier le statut de la demande n°<?=$demande->id_demande?>"
        class="select_change_submit"
        name="id_demande_statut"
        >
        <?php foreach($statut_demande as $statut):?>
            <option value="<?=$statut->id?>"
                <?php if ($statut->id==$demande->id_demande_statut):?>selected <?php endif;?>
                >
                <?=$statut->label?>
            </option>
        <?php endforeach;?>
    </select> 
    <?php if(isset($is_notif) && $is_notif):?>
        <div class="text-success ms-2"> Le statut a été modifié </div>
    <?php endif;?>
</form>