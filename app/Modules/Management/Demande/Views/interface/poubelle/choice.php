<div class="container">
      
    <div  style="margin-top: 50px;">
    
 <?php if(!isset($fluid)):?> <a href="<?php echo base_url();?>app/destroy"><i class="fa fa-lock fa-2x" aria-hidden="true"></i> 
		    
		   
		</a> <?php endif;?>
	<h4  style="margin-bottom:20px" class="text-center">Choisir un module</h4>
	    
	<div class="row">
		
		
	   
		    <?php $nb=6;?>
		    <?php $nb_moyen=6;?>
	   
		<?php if($this->fh_dao->get_autorisation("binterface")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: MEDIUMPURPLE !important' class="panel panel-info" style="text-align:center;">
			    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/index_bureau"><?php echo icon("bureau","3x","MEDIUMPURPLE");?> <br>Tableau de bord</a>
			</div>
		    </div>
		<?php endif;?>
	    
	         <?php if($this->fh_dao->get_autorisation("bdinterface2")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color :TOMATO  !important' class="panel panel-info" style="text-align:center;">
			   <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/index_base_donnee"><?php echo icon("data","3x","TOMATO");?><br> Base de données (Datas)</a>
			</div>
		    </div>
		    <?php endif;?>
		
		 <?php if($this->fh_dao->get_autorisation("tinterface")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: green !important' class="panel panel-info" style="text-align:center;">
			    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/index_telephone"><?php echo icon("telephone","3x","green");?><br> Téléphone</a>
			 </div>
		    </div>
		<?php endif; ?>
            
            
		
		
		
		<?php if($this->fh_dao->get_autorisation("ginterface")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: orange !important' class="panel panel-info" style="text-align:center;">
		    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/index_guichet"><?php echo icon("guichet","3x","orange");?><br> Guichet</a>
			</div>
		    </div>
		<?php endif;?>
	    
	    <?php if($this->fh_dao->get_autorisation("ginterface")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: salmon !important' class="panel panel-info" style="text-align:center;">
		    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/index_stand"><?php echo icon("stand","3x","salmon");?><br> Stand</a>
			</div>
		    </div>
		<?php endif;?>
		
		<?php if($this->fh_dao->get_autorisation_mail("email_all_r")): ?>
	     <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: SLATEGRAY !important' class="panel panel-info" style="text-align:center;">
		    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>fh/myoutlook/sync_outlook/1"><?php echo icon("outlook","3x","SLATEGRAY");?><br> Outlook</a>
			</div>
	     </div>
		<?php endif;?>

		<?php if($this->fh_dao->get_autorisation_mail("winterface2")): ?>
	     <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color: MediumSlateBlue !important' class="panel panel-info" style="text-align:center;">
		    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>re/deposit/list"><?php echo icon("web","3x","MediumSlateBlue");?><br>Dépot Web</a>
			</div>
	     </div>
		<?php endif;?>
		
	
		
		  
		
		
		    <?php if($this->fh_dao->get_autorisation("ruser")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color :SIENNA  !important' class="panel panel-info" style="text-align:center;">
			   <a class="btn btn-default list-group-item" href="<?php echo base_url()?>fh/fhc_autorisation"><?php echo icon("user","3x","SIENNA");?><br> Gestion utilisateur</a>
			</div>
		    </div>
		    <?php endif;?>
	    
	    
	      <?php if($this->fh_dao->get_autorisation("rrequete")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color :GREY  !important' class="panel panel-info" style="text-align:center;">
			    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/requete"><?php echo icon("list","3x","GREY");?> <br> Requête</a>
			</div>
		    </div>
		    <?php endif;?>
	    
	     <?php if($this->fh_dao->get_autorisation("rdoublon")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color :blueviolet  !important' class="panel panel-info" style="text-align:center;">
			    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>app/doublon"><?php echo icon("data","3x","BlueViolet");?> <?php echo icon("data","3x","BlueViolet");?> <br> Doublon</a>
			</div>
		    </div>
		    <?php endif;?>
            
              <?php if($this->fh_dao->get_autorisation("enquete_r")): ?>
		    <div  class="col-lg-<?php echo $nb;?> col-md-<?php echo $nb_moyen;?>">
			<div style='border-color :Salmon  !important' class="panel panel-info" style="text-align:center;">
			    <a class="btn btn-default list-group-item" href="<?php echo base_url()?>en/answer"><?php echo icon("check","3x","Salmon");?> <br> Enquete</a>
			</div>
		    </div>
		    <?php endif;?>
	    
	
 
	</div>
    </div>
</div>

