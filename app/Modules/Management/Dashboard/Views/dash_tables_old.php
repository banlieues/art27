

    <?php $i=0;?>
<?php  foreach($tables as $table):?>
 <?php if($i==0):?>
    <div style="margin-top:20px" class="row">
	<?php endif;?>
	
    <div size="<?php echo $table["size"];?>" class="col-lg-<?php echo $table["size"];?> lg_dynamique c_table">
	
	    <?php echo $table["data_table"];?>
    </div>
  <?php if($i==1):?>
	</div>
<?php endif;?>
<?php $i=$i+1;?>
<?php endforeach; ?>

