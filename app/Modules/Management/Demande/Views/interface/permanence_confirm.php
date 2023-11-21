
<?php $autorisationManager = \Config\Services::autorisationModel();?>

<div class="container">
    
    
    
    
<div class="row" style="margin-top: 50px; margin-bottom: 50px; text-align:center">
    <?php if(isset($interface)&&$interface==="outlook" ):?>
    <h3>
	<?php if(isset($is_new_demande_message)&&$is_new_demande_message):?>
	<?php if($compte>1):?>
	    <?php echo $compte;?> nouvelles demandes ont été créées et le message a été associé à ces demandes
	   <?php else: ?> 
	    La demande a été créée et le message a été associé à cette demande
	<?php endif; ?>
	
	<?php else: ?>
	    
	Le message a été associé à la demande
	<?php endif; ?>
   
	<br>
	<small><?php echo $this->profil->get_info_profil($this->session->userdata("id"),"pseudo");?>, que veux-tu faire?</small>
	
    </h3>
    
    <?php if(isset($id)): ?>
    
    
     <a style='margin-bottom:10px'  class="btn btn-info fh_dao_fiche" 
	fh-descriptor="fhd_liste_demande" 
	href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id;?>" 
	href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id;?>" 
	href="<?php echo base_url();?>/demande/fiche/<?php echo $id;?>"
       
       >Ouvrir la demande associée au message</a>
    <?php endif;?>
    
     <?php if(isset($id_accompagnement)): ?>
    
    
     <a style='margin-bottom:10px'  class="btn btn-info fh_dao_fiche" 
	fh-descriptor="fhd_liste_demande" 
	href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id_accompagnement;?>" 
	href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id_accompagnement;?>" 
	href="<?php echo base_url();?>/demande/fiche/<?php echo $id_accompagnement;?>"
       
       >Ouvrir la demande associée au message</a>
    <?php endif;?>
    
     <?php if(isset($id_visite)): ?>
    
    
     <a style='margin-bottom:10px' class="btn btn-info fh_dao_fiche" 
	fh-descriptor="fhd_liste_demande" 
	href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id_visite;?>" 
	href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id_visite;?>" 
	href="<?php echo base_url();?>/demande/fiche/<?php echo $id_visite;?>"
       
       >Ouvrir la demande associée au message</a>
    <?php endif;?>
    
    <br><a style='margin-bottom:10px'  class="btn btn-success" href="<?php echo base_url();?>fh/myoutlook/sync_outlook/0">Retour à la liste de tous les emails</a>
    <a style='margin-bottom:10px'  class="btn btn-success" href="<?php echo base_url();?>fh/myoutlook/sync_outlook/1">Retour à la liste des emails non traités</a>
  
    
    
    <?php else: ?>
    <h3>
	<?php if($compte>1): ?>
	<?php echo $compte;?> nouvelles demandes ont été créées
	<?php else: ?>
	La demande a été créée
	<?php endif; ?>
	<br>
	<small> que veux-tu faire?</small><br>
	
    </h3>
    
    
  
	    <?php if(isset($id)):?>
	    <a target='_blank' style='margin-bottom:10px' class="btn btn-info fh_dao_fiche" 
		fh-descriptor="fhd_liste_demande" 
		href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id;?>" 
		href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id;?>" 
		href="<?php echo base_url();?>/demande/fiche/<?php echo $id;?>"

	       >Ouvrir la demande d'information qui vient d'être créée</a>
	    <?php endif; ?>

	      <?php if(isset($id_accompagnement)):?>
	     <a target='_blank' style='margin-bottom:10px'  class="btn btn-info fh_dao_fiche" 
		fh-descriptor="fhd_liste_demande" 
		href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id_accompagnement;?>" 
		href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id_accompagnement;?>" 
		href="<?php echo base_url();?>/demande/fiche/<?php echo $id_accompagnement;?>"

	       >Ouvrir la demande d'accompagnement qui vient d'être créée</a>
	    <?php endif; ?>

	     <?php if(isset($id_visite)):?>
	     <a target='_blank' style='margin-bottom:10px'  class="btn btn-info fh_dao_fiche" 
		fh-descriptor="fhd_liste_demande" 
		href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/<?php echo $id_visite;?>" 
		href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/<?php echo $id_visite;?>" 
		href="<?php echo base_url();?>/demande/fiche/<?php echo $id_visite;?>"

	       >Ouvrir la demande de visite qui vient d'être créée</a>
	    <?php endif; ?>
    
    

	



    


   
	
    <?php endif;?>
</div>
</div>



