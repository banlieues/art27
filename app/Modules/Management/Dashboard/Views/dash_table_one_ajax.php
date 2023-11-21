


<div id_onglet="<?php echo $options->id_user_table_onglet;?>" data-index="<?php echo $options->id_user_table;?>" data-rank="<?php echo $i+1;?>" id="r<?php echo $options->id_user_table;?>" id_user_table="<?php echo $options->id_user_table;?>" class="panel panel-default load_table_ajax panel-information">
  <div class="panel-heading"> 
      <div class="row">
	  <div class="col-lg-10">
	      <h5>
		  <a href="#" class="dash_expand"  <?php if($size==12):?>style="display:none"<?php endif;?>><i class="fas fa-expand-alt" aria-hidden="true"></i>
		  </a>
		  <button type="button"
            <?php if($size==6):?>style="display:none"<?php endif;?>
            class="dash_compress btn btn-link link-dark"
            >
            <?php echo fontawesome('down-left-and-up-right-to-center');?>
          </button>
	    <?php echo $nom_table;?></h5>
	</div>
	  <div style="" class="col-lg-2 text-right c_option">
	      <a href="" class="dash_option_table"><i class="fa fa-cog"></i></a>
	      <a style="display:none" href="" class="dash_option_table_close"><i class="fa fa-list"></i></a>
	  </div>
      </div>
      
      </div>
  <div class="panel-body">
      <div style="text-align:center; margin: 40px 0; ">
	  <i class="fas fa-circle-notch fa-spin fa-2x"></i>
      </div>
  </div>
</div>



