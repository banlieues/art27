<span class="click_lecture">
    <i style="cursor:pointer" class="<?=icon("edit")?>"></i> <?=$ticket->num_code_ticket?></b>
</span>

<span style="display:none" class="click_modif">
    <form autocomplete="off" class="form_ajax_modif tr_load" method="post" action="<?=base_url()?>/ticket/setNumCode">
    <input type="hidden" class="copy_origine" value="<?=nl2br($ticket->num_code_ticket)?>">
    <textarea class="new_value form-control" name="num_code_ticket" value="<?=$ticket->num_code_ticket?>"><?=nl2br($ticket->num_code_ticket)?></textarea>
    <input name="id_ticket" type="hidden" value="<?=$ticket->id_ticket?>">
    <div>
            <button type="submit" class="btn btn-success btn-xs">Enregistrer</button> 
            <button  
            class="btn btn-danger btn-xs click_annule">Annuler</button>
    </div>
    </form>
</span>