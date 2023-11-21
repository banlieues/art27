
<?php if (!$request_is_ajax): ?>
    <?php $this->extend("Layout\index"); ?>
    <?php $this->section("body"); ?>
<?php endif;?>

<?php if(!empty($messages)):?>
    <ul class="list-group">
        <?php foreach($messages as $message):?>
            <li class="list-group-item">
                <span class="text-body-secondary">
                <small><span class="message_date">
                            <?php if(!empty($message->date_created)):?>
                                <?=convert_date_en_to_fr_with_h($message->date_created);?>
                            
                            <?php endif;?>
                        </span>  
                        (#<?=$message->id?>)<input type="hidden" value="<?=$message->id?>" name="id_messages_lu[]">
                            <br>de: <b><span class=""><?=$message->prenom?> <?=$message->nom?></span></b>
                            </small>
                        <br>
                            Objet: <b><span class="message_sujet"><?=$message->subject?></span></b>
                        <br>
                        <?php echo nl2br(trim($message->content))?>
                </span>
            </li>
        <?php endforeach;?>
    </ul>   
<?php else:?>
    <div class="text-center p-1">Pas de notes</div>
<?php endif;?>


<?php if (!$request_is_ajax): ?>
    <?php $this->endSection()?>
<?php endif;?>