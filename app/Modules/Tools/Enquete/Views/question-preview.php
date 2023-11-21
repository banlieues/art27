<div class="p-2 mb-2">
    <p class="mb-1"><?php if($name_question!=="suggestions"):?><span class="oli">*</span><?php endif;?>
        <?php echo $question->{'question_' . $lang};?>
    </p>
    <p class="font-italic ml-1">
        <?php echo $question->{'aide_question_' . $lang};?>
    </p>
    <div class="px-4 py-3">
        <?php echo $question->html->$lang;?>
    </div>
    <hr style="border-color: #00B896;">
</div>

