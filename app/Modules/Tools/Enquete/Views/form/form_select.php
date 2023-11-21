<select class="form-select" name="<?php echo $name_question?>" id="<?php echo $name_question?>">
    <option disabled selected>
        <?php echo ${'aide_question_' . $lang}?>
    </option>
    <?php foreach ($options as $option):?>
        <option value="<?php echo $option->id;?>">
            <?php echo $option->{'label_' . $lang};?>
        </option>
    <?php endforeach;?>
</select>
