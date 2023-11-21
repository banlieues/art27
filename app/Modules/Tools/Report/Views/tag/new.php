<?php 

defined('BASEPATH') OR exit('No direct script access allowed');?>

<form id="<?php echo $form_id;?>" class="needs-validation py-2" action="<?php echo base_url('report/tag_new');?>" method="post" novalidate>
            
    <?php echo $controls->label;?>
    <?php echo $controls->comment;?>
            
</form>