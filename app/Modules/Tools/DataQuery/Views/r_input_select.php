<?php if(!is_array($value)): $values=array($value); else: $values=$value; endif;?>


<select  type="select" data-placeholer="Choisir" multiple=""<?php if($is_multiple):?><?php endif;?> class="bs-multi-select dselect_exist choice from-control" name="<?php if(!is_null($i)): echo "##$i##_"; endif; ?>value[]">

    <?php foreach($options as $option):?>
	<option <?php if(in_array($option->id,$values)):?>selected<?php endif;?> value="<?php echo $option->id; ?>"><?php echo $option->label;?></option>
    <?php endforeach;?>
</select>



<script>
$(document).ready(function() {
  /*  const config = {
    search: true, // Toggle search feature. Default: false
    creatable: true, // Creatable selection. Default: false
    clearable: true, // Clearable selection. Default: false
    maxHeight: '360px', // Max height for showing scrollbar. Default: 360px
    size: 'sm', // Can be "sm" or "lg". Default ''
}

    
    
    const deselect_present=document.querySelectorAll(".dselect");

    deselect_present.forEach(function(ds)
    {
        dselect(ds,config);
    });*/

    $('select.bs-multi-select').each(function() {
            bs_multi_select(this);
        });
   
});  
</script>
