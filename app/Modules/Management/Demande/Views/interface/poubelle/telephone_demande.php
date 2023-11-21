<?php $this->load->view("interface/choice_module"); ?>
<?php $this->load->view("interface/search_all"); ?>
<style>
    .c_global .btn-success {
        display:none;
    }
 
    
</style>
<?php
   switch($interface):
       case 'guichet':
           $icon="guichet";
           $color="ORANGE";
           $label="Guichet";
           $url="app/index_$interface";
           break;
       case 'stand':
           $icon="stand";
           $color="SALMON";
           $label="Stand";
             $url="app/index_$interface";
           
           break;
       case 'outlook':
           $icon="outlook";
           $color="SLATEGRAY";
           $label="Outlook";
             $url="fh/myoutlook/sync_outlook/1";
           break;
       default:
           $icon="telephone";
           $color="GREEN";
           $label="Téléphone";
             $url="app/index_$interface";
   endswitch;


?>
<div style="margin-bottom:20px" class='entities c_rubrique'>
    
    <div style="margin-bottom:0 !important; margin:10px; border-color: <?php echo $color;?> !important" class="panel panel-info">
        
     
    <div style='background-color: <?php echo $color;?> !important; border-color: <?php echo $color;?> !important' class="panel-heading">
	<div class='row'>
            
	    <div style='font-size:24px; min-height: 40px' class='col-lg-6'>
	    <?php echo icon($icon);?> <?php echo $label;?>
		    
	    </div>
            
            
            
	    <div style='font-size:18px; min-height: 40px' class='col-lg-6 text-right'><a class="btn btn-default" href="<?php echo base_url();?><?php echo $url;?>">Nouvelle demande</a></div>
	</div>
    </div>

	<div style="padding:0 !important" class="panel-body">
	    <?php echo $view; ?>
	</div>
    </div>