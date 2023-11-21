<?php if(!is_array($value)): $value=explode(",",$value); endif;?>
<div class="form-check">
    <input id="Check<?php echo $index;?>" class="form-check-input" type="radio" 
        name="<?php echo $index;?>" value="1"
        <?php if(!is_null($value) && $value==1):?> checked <?php endif;?>
    />
    <label class="form-check-label" for="Check<?php echo $index?>"> Oui </label>
</div>
<div class="form-check">
    <input id="Check<?php echo $index;?>" class="form-check-input" type="radio" 
        name="<?php echo $index;?>" value="0"
        <?php if(!is_null($value) && $value==0):?> checked <?php endif;?>
    />
    <label class="form-check-label" for="Check<?php echo $index;?>"> Non </label>
</div>