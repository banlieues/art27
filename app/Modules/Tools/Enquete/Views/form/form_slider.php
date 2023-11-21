<?php $name_question_id = $name_question . '_' . $lang;?>

<style>
    .slider-input {
        width: 50px;
    }
</style>
<div class="slider-group" id="<?php echo $name_question_id;?>">
    <?php if(!empty($is_not_required)):?>
        <div class="d-flex mb-2">
            <input class="form-check-input" type="checkbox" data-target="<?php echo $name_question_id;?>" onclick="js_disable_slider(this);"/>
            <label class="form-check-label"> 
                <?php if($lang == 'fr') echo 'Non pertinent'; elseif($lang == 'nl') echo 'Immaterieel';?> 
            </label>
        </div>
    <?php endif;?>

    <div class="row align-items-center">
        <div class="col-auto">
            <input type="int" 
                id="input_<?php echo $name_question_id;?>" 
                class="form-control slider-input" 
                name="<?php echo $name_question?>" 
                readonly
            />
        </div>
        <div id="slider_<?php echo $name_question_id;?>" class="col">
            <div id="handle_<?php echo $name_question_id;?>" class="ui-slider-handle">
            </div>
        </div>
    </div>
</div>
