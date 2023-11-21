<?php $ldashboard = \Config\Services::ldashboard();?>
<?php if(!empty(strstr($options->query,"FROM demande" ))||!empty(strstr($options->query,"LEFT JOIN demande"))): ?>
 <div class="row">  
    <div class="col-lg-4 mt-2  mt-2">
        <?php echo $ldashboard->get_filtre_utilisateur_en_charge();?>
    </div>

    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_utilisateur_back_up();?>
    </div>

     <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_statut_demande();?>
    </div>
 </div>
<?php endif;?>

<?php if(!empty(strstr($options->query,"FROM demande" ))||!empty(strstr($options->query,"LEFT JOIN demande"))): ?>
 <div class="row">  
    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_type_demande();?>
    </div>

    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_type_accompagnement();?>
    </div>
     
     <div class="col-lg-4 mt-2  mt-2 ">
         <?php //echo $ldashboard->get_filtre_type_is_not_accompagnement_specifique();?>
       
    </div>
     
<!--     ds_is_accompagnement_specifique-->
     
 </div>
<?php endif;?>

<?php if(!empty(strstr($options->query,"FROM rdv" ))||!empty(strstr($options->query,"LEFT JOIN rdv"))): ?>
 <div class="row">  
    <div class="col-lg-4 mt-2  mt-2 ">
       <?php echo $ldashboard->get_filtre_user_rdv();?>
    </div>

    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_statut_rdv();?>
    </div>
 </div>
<?php endif;?>

<?php if(!empty(strstr($options->query,"FROM tache" ))||!empty(strstr($options->query,"LEFT JOIN tache"))): ?>
 <div class="row">  
    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_user_tache();?>
    </div>

    <div class="col-lg-4 mt-2  mt-2 ">
        <?php echo $ldashboard->get_filtre_statut_tache();?>
    </div>
 </div>
<?php endif;?>



<script>
$(document).ready( function () {
  
   // $(".selectfiltre").selectpicker();
    
    
  $(".selectfiltre").chosen({
            disable_search_threshold: 10,
            search_contains: true,
            no_results_text: "Pas de résultat pour ",
            width: "100%",
            placeholder_text_multiple: "Vous pouvez choisir plusieurs éléments",
         });  
    
} );
</script>
