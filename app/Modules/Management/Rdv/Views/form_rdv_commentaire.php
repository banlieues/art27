<span class="click_lecture">
    <i style="cursor:pointer" class="<?=icon("edit")?>"></i> <?=$rdv->note?></b>
</span>

<span style="display:none" class="click_modif">
    <form autocomplete="off" class="form_ajax_modif" method="post" action="<?=base_url()?>/rdv/setCommentaire">
    <input type="hidden" class="copy_origine" value="<?=nl2br($rdv->note)?>">
    <textarea class="new_value form-control" name="note" value="<?=$rdv->note?>"><?=nl2br($rdv->note)?></textarea>
    <input name="id_rdv" type="hidden" value="<?=$rdv->id_rdv?>">
    <div>
            <button type="submit" class="btn btn-success btn-xs">Enregistrer</button> 
            <button  
            class="btn btn-danger btn-xs click_annule">Annuler</button>
    </div>
    </form>
</span>