<div class="col-10">
    <input type="text" class="form-control" list="<?php echo $recip_type;?>Datalist" 
        name="<?php echo $recip_type;?>_text[]"
        value="<?php echo htmlspecialchars($recip);?>"
        />
    <?php if(isset($recip_option_text)):?>
        <datalist id="<?php echo $recip_type;?>Datalist">
            <?php foreach($recip_option_text as $recip_opt):?>
                <option value="<?php echo htmlspecialchars($recip_opt->name . ' ' . $recip_opt->lastname . ' <' . $recip_opt->email . '>');?>">
            <?php endforeach; ?>
        </datalist>
    <?php endif;?>
</div>
<div class="col-2">
    <?php echo view('Mail\mail/form/button_row_delete');?>
    <?php echo view('Mail\mail/form/button_row_add');?>    
</div>