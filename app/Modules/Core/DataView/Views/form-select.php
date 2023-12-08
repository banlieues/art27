<select class="<?php if($is_ajax):?>select_modif<?php endif;?> <?php if($is_dselect):?>dselect_form<?php else:?>dselect_form form-select<?php endif;?> form-control" name="<?php echo $index?><?=$numero_multiple?>">
        <option value="">Choisir</Option>
    <?php foreach($selects as $select):?>
        <option <?php if(!is_null($value) && $value==$select->key):?>selected<?php endif;?> value="<?=$select->key?>"><?=$select->label?></option>
    <?php endforeach;?>    
</select>