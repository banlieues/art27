<select class="bs-multi-select form-control border" multiple
    data-style="btn-white" data-live-search="true" title="- Sélectionner -" data-selected-text-format="count" data-actions-box="true" data-size="5"
    name-option = "<?php echo $name_option;?>"
    name="<?php echo $recip_type;?>_selected[]"
    >
    <?php foreach($recip_selected as $recip):?>
        <option value="<?php echo htmlspecialchars(json_encode($recip));?>"
            <?php if(!empty($selected)):?> selected  <?php endif;?>
            >
            <?php if(!empty($recip)) echo htmlspecialchars($recip->name . ' ' . $recip->lastname . ' <' . $recip->email . '>');?>
        </option>
    <?php endforeach;?>
</select>
<!-- <select class="bootstrap-select form-control border" multiple
    data-style="btn-white" data-live-search="true" title="- Sélectionner -" data-selected-text-format="count" data-actions-box="true" data-size="5"
    name-option = "<?php echo $name_option;?>"
    name="<?php echo $recip_type;?>_selected[]"
    >
    <?php foreach($recip_selected as $recip):?>
        <option value="<?php echo htmlspecialchars(json_encode($recip));?>"
            <?php if(!empty($selected)):?> selected  <?php endif;?>
            >
            <?php if(!empty($recip)) echo htmlspecialchars($recip->name . ' ' . $recip->lastname . ' <' . $recip->email . '>');?>
        </option>
    <?php endforeach;?>
</select> -->