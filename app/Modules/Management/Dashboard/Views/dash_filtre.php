<?php 
if(!is_null($value)): 
    $values=explode(",",$value);
else:
    $values=NULL;
endif;
//print_r($values)."toto";

?>

<div class="form-group mb-2">
    <label>
	<label style="margin-right: 30px"><b><?php echo $label;?></b></label>
	<select class="selectfiltre" name="<?php echo $name;?>[]"  multiple >
            <?php if($name=="type_accompagnement"):?>
            <option  <?php if(!is_null($values)&&in_array(666,$values)):?>selected<?php endif;?> value="666">
                Sans accompagnement spécifique
            </option> 
            <?php endif;?>
	    <?php foreach($datas as $data):?>
            
	    <option  <?php if(!is_null($values)&&in_array($data->id,$values)):?>selected<?php endif;?> value="<?php echo $data->id;?>">
		<?php echo $data->label;?>
	    </option>
	    <?php endforeach;?>
	 
	</select>
      
    
    </label>
</div>


