<?php if(!empty($id)):?>
<?php if($descriptor=="demande"):?>
    <a href="<?php echo base_url();?>demande/fiche/<?php echo $id;?>"
    class="fh_dao_fiche btn btn-info btn-xs" 
   target="_blank" 
   fh-descriptor="<?php echo $descriptor;?>" 
   href-ajax="<?php echo base_url();?>/fh/fhc_dao/get_fiche/<?php echo $id;?>" 
   href-title="<?php echo base_url();?>/fh/fhc_dao/get_fiche_title/<?php echo $id;?>">
    <?php echo $id; ?>
</a>
<?php endif;?>

<?php if($descriptor=="bien"):?>
    <a href="<?php echo base_url();?>bien/fiche/<?php echo $id;?>"
    class="fh_dao_fiche btn btn-info btn-xs" 
   target="_blank" 
   fh-descriptor="<?php echo $descriptor;?>" 
   href-ajax="<?php echo base_url();?>/fh/fhc_dao/get_fiche/<?php echo $id;?>" 
   href-title="<?php echo base_url();?>/fh/fhc_dao/get_fiche_title/<?php echo $id;?>">
    <?php echo $id; ?>
</a>
<?php endif;?>

<?php if($descriptor=="contact"):?>
    <a href="<?php echo base_url();?>contact/fiche/<?php echo $id;?>"
    class="fh_dao_fiche btn btn-info btn-xs" 
   target="_blank" 
   fh-descriptor="<?php echo $descriptor;?>" 
   href-ajax="<?php echo base_url();?>/fh/fhc_dao/get_fiche/<?php echo $id;?>" 
   href-title="<?php echo base_url();?>/fh/fhc_dao/get_fiche_title/<?php echo $id;?>">
    <?php echo $id; ?>
</a>
<?php endif;?>

<?php if($descriptor=="rdv"):?>
    <a href="<?php echo base_url();?>rdv/form_rdv/0/<?php echo $id;?>"
    class="fh_dao_fiche btn btn-info btn-xs" 
   target="_blank" 
   fh-descriptor="<?php echo $descriptor;?>" 
   href-ajax="<?php echo base_url();?>/fh/fhc_dao/get_fiche/<?php echo $id;?>" 
   href-title="<?php echo base_url();?>/fh/fhc_dao/get_fiche_title/<?php echo $id;?>">
    <?php echo $id; ?>
</a>
<?php endif;?>

<?php if($descriptor=="tache"):?>
    <a href="<?php echo base_url();?>tache/form_tache/0/<?php echo $id;?>"
    class="fh_dao_fiche btn btn-info btn-xs" 
   target="_blank" 
   fh-descriptor="<?php echo $descriptor;?>" 
   href-ajax="<?php echo base_url();?>/fh/fhc_dao/get_fiche/<?php echo $id;?>" 
   href-title="<?php echo base_url();?>/fh/fhc_dao/get_fiche_title/<?php echo $id;?>">
    <?php echo $id; ?>
</a>
<?php endif;?>


  
<?php endif;?>

