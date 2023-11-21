<?php $ldashboard = \Config\Services::ldashboard();?>
<?php $fields_orig=explode(",",$options->field);?>

    <div class="row">
    <?php $i=0;foreach($fields_orig as $field_orig):?>
	<div class="col-lg-4">
	    
	    <input <?php if($i==0):?>checked<?php endif;?> type="checkbox" name="field[]" value="<?php echo $field_orig;?>"> <?php echo $ldashboard->convert_label($field_orig);?>
	</div>
	<?php $i=$i=1; ?>
    <?php endforeach;?>
    </div>

    