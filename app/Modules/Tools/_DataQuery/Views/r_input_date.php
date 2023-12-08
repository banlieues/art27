<input style="width:200px" value="<?php echo convert_date_en_to_fr($value);?>" 
autocomplete="off" 
class="choice datepicker" 
name="<?php if(!is_null($i)): echo "##$i##_"; endif; ?>value" 
type='text' />

<script>
$(document).ready(function() {
   
    $('.datepicker').flatpickr({
        altInput: false,
        dateFormat: 'd/m/Y',
        locale: 'fr',
        allowInput: true
    });
});  
</script>