
 <div style="margin-top:10px">
<div id="accordion" class="row">
    <?php $i=0;?>
<?php  foreach($tables as $table):?>

    <div style="" size="<?php echo $table["size"];?>" class="mb-3 col-lg-<?php echo $table["size"];?> lg_dynamique c_table">
	
	    <?php echo $table["data_table"];?>
    </div>
<?php endforeach;?>
</div>
     </div>

