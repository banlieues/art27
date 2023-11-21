<?php $autorisationManager = \Config\Services::autorisationModel();?>


<?php if($autorisationManager->is_autorise("ticket_u")):?>
<form action="<?=base_url()?>/ticket/setTypeDocument/<?=$ticket->id_ticket?>">
    <select  id="SelectTypeDocument<?=$ticket->id_ticket?>" data-type-confirm="select" data-value-origin="<?=$ticket->id_type?>" data-alert-titre="Changement du type de ticket" data-alert-content="Modifier le type du ticket <?=$ticket->name?>" class="select_change_submit load_reload_page_ticket" name="id_type" >
    <option>Choisir un type</option>
        <?php foreach($type_ticket as $type):?>
            <option <?php if($ticket->id_type==$type->name):?>selected<?php endif;?> value="<?=$type->name?>"><?=$type->name?></option>
        <?php endforeach;?>    
    </select>
</form>
<?php else:?>
    <?php if($autorisationManager->is_autorise("ticket_r")):?>
       
        <?php foreach($type_ticket as $type):?>
       <?php if($ticket->id_type==$ticket->name):?><?=$type->name?><?php endif;?>
        <?php endforeach;?> 

    <?php endif;?>
<?php endif;?>