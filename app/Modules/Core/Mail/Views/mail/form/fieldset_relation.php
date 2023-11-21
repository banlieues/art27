<!-- Sender -->
<div class="mb-2 row">
    <label for="mailSender" class="col-sm-2 col-form-label">De</label>
    <div class="col-10">
        <div class="row">
            <div class="col-10">
                <select type="text" class="form-control" id="mailSender" name="sender" readonly>
                    <?php foreach($sender_option as $sender_opt):?>
                        <option
                            value="<?php echo htmlspecialchars(json_encode($sender_opt));?>"
                            <?php if(!empty($sender) && $sender->email==$sender_opt->email):?>
                                selected
                            <?php endif;?>
                            >
                            <?php echo htmlspecialchars($sender_opt->name . ' ' . $sender_opt->lastname . ' <' . $sender_opt->email . '>');?>
                        </option>
                    <?php endforeach;?>
                </select>            
            </div>
        </div>
    </div>
</div>

<div class="mb-2 row">
    <label for="mailReply" class="col-sm-2 col-form-label">Réponse à</label>
    <div class="col-10">
        <div class="row">
            <div class="col-10">
                <select type="text" class="form-control" id="mailReply" name="reply">
                    <?php foreach($reply_option as $ro):?>
                        <option
                            value="<?php echo htmlspecialchars(json_encode($ro));?>"
                            <?php if($ro->id==session('loggedUserId')):?>
                                selected
                            <?php endif;?>
                            >
                            <?php echo htmlspecialchars($ro->name . ' ' . $ro->lastname . ' <' . $ro->email . '>');?>
                        </option>
                    <?php endforeach;?>
                </select>            
            </div>
        </div>
    </div>
</div>

<?php echo view('Mail\mail/form/recip_field', $param_to);?>
<?php echo view('Mail\mail/form/recip_field', $param_cc);?>
<?php echo view('Mail\mail/form/recip_field', $param_cci);?>

<hr>
