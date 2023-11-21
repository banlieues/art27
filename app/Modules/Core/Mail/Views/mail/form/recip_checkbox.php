<input type="checkbox" 
    class="form-check-input"
    name-option = "<?php echo $name_option;?>"
    name="<?php echo $recip_type;?>_selected[]" 
    value="<?php echo htmlspecialchars(json_encode($recip));?>" 
    <?php if(!empty($recip) && !empty($recip_selected)): foreach($recip_selected as $recip_sel): if(!empty($recip_sel) && $recip->email==$recip_sel->email):?> 
        checked 
    <?php endif; endforeach; endif;?>
    />
<label class="form-check-label"> 
    <?php if(!empty($recip)) echo htmlspecialchars($recip->name . ' ' . $recip->lastname . ' <' . $recip->email . '>');?>
</label>