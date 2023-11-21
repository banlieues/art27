<div>
    <?php $i=0;?>
    <?php foreach($checks as $check):?>
        <?php $i=$i+1;?>
        <?php if(!is_array($value)): $value=explode(",",$value); endif;?>
        <div class="form-check <?php if(count($checks)<4):?> form-check-inline <?php endif;?>">
            <input name_visibility="<?=$index?>"
                name_checkbox="<?=$index?>"
                name="<?=$index?>[]"
                class="ssth_direct form-check-input"
                type="checkbox" value="<?=$check->key?>"
                id="Check<?=$index.$i?>"
                <?php if(!is_null($value) && in_array($check->key,$value)):?>checked<?php endif;?>
            />
            <label class="form-check-label" for="Check<?=$index.$i?>">
                <?=$check->label?>
            </label>
            <?php if(isset($validation)&&$validation->getError('name')) {?>
                <div class='alert alert-danger mt-2'>
                    <?= $error = $validation->getError('name'); ?>
                </div>
            <?php }?>
        </div>
    <?php endforeach;?>
</div>

