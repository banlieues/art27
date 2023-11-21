
    <div class="row">
        <div class="col-6">
        <input id="<?=$index?>" name="<?=$index?>" 
        type="text" placeholder="dd/mm/yyyy" class="form-control datepicker" value="<?=$value?>">
        
    </div>

    <div class="col-6">
    <input id="<?=$index?>_h" name="<?=$index?>_h" 
    type="text" placeholder="hh:mm" class="form-control" value="<?=date_only_h_m($value)?>">
    </div>

</div>
<script src="<?php echo base_url('node_modules/flatpickr/dist/flatpickr.min.js');?>" charset="utf-8"></script>
<script src="<?php echo base_url('node_modules/flatpickr/dist/l10n/fr.js');?>" charset="utf-8"></script>
<script>

var elem=$("#<?=$index?>_h");

elem.flatpickr({
        allowInput: false,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        locale: 'fr',
    });


</script>



