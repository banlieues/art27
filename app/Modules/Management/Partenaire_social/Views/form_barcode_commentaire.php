<span class="click_lecture">
    <i style="cursor:pointer" class="<?=icon("edit")?>"></i> <?=$barcode->commentaire?></b>
</span>

<span style="display:none" class="click_modif">
    <form autocomplete="off" class="form_ajax_modif" method="post" action="<?=base_url()?>/partenaire_social/setCommentaire">
    <input type="hidden" class="copy_origine" value="<?=nl2br($barcode->commentaire)?>">
    <textarea class="new_value form-control" name="commentaire" value="<?=$barcode->commentaire?>"><?=nl2br($barcode->commentaire)?></textarea>
    <input name="id_convention_barcode" type="hidden" value="<?=$barcode->id_convention_barcode?>">
    <div>
            <button type="submit" class="btn btn-success btn-xs">Enregistrer</button> 
            <button  
            class="btn btn-danger btn-xs click_annule">Annuler</button>
    </div>
    </form>
</span>