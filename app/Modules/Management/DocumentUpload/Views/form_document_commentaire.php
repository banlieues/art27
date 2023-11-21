<span class="click_lecture">
    <i style="cursor:pointer" class="<?=icon("edit")?>"></i> <?=$document->commentaire?></b>
</span>

<span style="display:none" class="click_modif">
    <form autocomplete="off" class="form_ajax_modif" method="post" action="<?=base_url()?>/document/setCommentaire">
    <input type="hidden" class="copy_origine" value="<?=nl2br($document->commentaire)?>">
    <textarea class="new_value form-control" name="commentaire" value="<?=$document->commentaire?>"><?=nl2br($document->commentaire)?></textarea>
    <input name="id_document" type="hidden" value="<?=$document->id_document?>">
    <div>
            <button type="submit" class="btn btn-success btn-xs">Enregistrer</button> 
            <button  
            class="btn btn-danger btn-xs click_annule">Annuler</button>
    </div>
    </form>
</span>