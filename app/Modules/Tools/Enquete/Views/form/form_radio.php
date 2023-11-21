<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="<?php echo $name_question?>" id="<?php echo $name_question?>_yes" value="1">
  <label class="form-check-label" for="<?php echo $name_question?>_yes">
      <?php if($lang == 'fr'):?> Oui
      <?php elseif($lang == 'nl'):?> Ja
      <?php endif;?>
  </label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="<?php echo $name_question?>" id="<?php echo $name_question?>_no" value="0">
    <label class="form-check-label" for="<?php echo $name_question?>_no">
        <?php if($lang == 'fr'):?> Non
        <?php elseif($lang == 'nl'):?> Nee
        <?php endif;?>
    </label>
</div>
