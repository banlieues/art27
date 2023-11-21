<?php if(is_array($value)): $value=implode(",",$value); endif;?>
<input autocomplete="off" 
class="choice form-control" name="<?php if(!is_null($i)): echo "##$i##_"; endif; ?>value" value="<?php if(isset($value)):?><?php echo $value;?><?php endif;?>"  type='text' />
