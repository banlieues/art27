<a href="<?=base_url()?>outlook/message_view/<?=$id_email_primary?>" class="view_message list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
        
        <small><?=convert_date_en_to_fr_with_h($date)?></small>
    </div>
    <small><?=$value->subject?></small>
</a>

