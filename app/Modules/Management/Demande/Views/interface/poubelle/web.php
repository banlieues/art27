<?php $this->load->view("interface/choice_module"); ?>
<?php $this->load->view("interface/search_all"); ?>
<div style="margin-bottom:20px" class='entities c_rubrique'>
    
    <div style="margin-bottom:0 !important; margin:10px; border-color: rgb(161, 169, 236) !important" class="panel panel-info">
    <div style='background-color: rgb(161, 169, 236) !important; border-color: rgb(161, 169, 236) !important' class="panel-heading">
	<div class='row'>
	    <div style='font-size:24px; min-height: 40px' class='col-lg-6'>
	    <?php echo icon("web");?> Web - Formulaire de contact <?php if($interface=="renolution"):?> Rénolution <?php endif;?>
		    
	    </div>
	   <!-- <div style='font-size:18px; min-height: 40px' class='col-lg-6 text-right'><a class="btn btn-info" href="<?php echo base_url();?>app/telephone_demande">Voir les demandes que j'ai créées</a></div>-->
	</div>
    </div>

	<div style="padding:0 !important" class="panel-body">
	    <?php $this->load->view("interface/permanence");?>
	</div>
    </div>