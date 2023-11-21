

<script>
jQuery(document).ready(function()
{
  
     <?php if($importDemande->id_contact_profil==0):?>
              
          $("input[name_checkbox='demande_is_premier_contact']").each(function(){
                   
                   if($(this).val()==1)
                   {
                       
                       if($(this).is(":checked")){}else{$(this).trigger("click")};
                   }
              });

     <?php else: ?>
               $("input[name_checkbox='demande_is_premier_contact']").each(function(){
                              
                    if($(this).val()==2)
                    {
                    
                    if($(this).is(":checked")){}else{$(this).trigger("click")};
                    }
               });


     <?php endif;?>
    

    <?php if($importDemande->id_contact_profil==0):?>
           
          <?php if(isset($importDemande->contact_name)&&!empty(trim($importDemande->contact_name))): ?>
               $("input[name='prenom_personne']").val("<?=str_replace('"', '\"', trim($importDemande->contact_name))?>");
          <?php endif;?> 


          <?php if(isset($importDemande->contact_lastname)&&!empty(trim($importDemande->contact_lastname))): ?>
               $("input[name='nom_personne']").val("<?=str_replace('"', '\"', trim($importDemande->contact_lastname))?>");
          <?php endif;?> 


          <?php if(isset($importDemande->contact_email)&&!empty(trim($importDemande->contact_email))): ?>
               $("input[name='email_personne']").val("<?=str_replace('"', '\"', trim($importDemande->contact_email))?>");
          <?php endif;?> 


          <?php if(isset($importDemande->contact_phone)&&!empty(trim($importDemande->contact_phone))): ?>
               $("input[name='telephone_personne']").val("<?=str_replace('"', '\"', trim($importDemande->contact_phone))?>");
          <?php endif;?> 


          

          <?php if(isset($importDemande->address_street)&&!empty(trim($importDemande->address_street))): ?>
               // $("input[name='adresse_personne']").val("<?=str_replace('"', '\"', trim($importDemande->address_street))?>");
          <?php endif;?> 

          <?php if(!empty($importDemande->address_pc)): ?>
               // $("select[name='localite_personne_form']").val("<?=str_replace('"', '\"', trim($importDemande->address_pc))?>");
               // $("select[name='localite_personne_form']").trigger("chosen:updated");
          <?php endif;?> 

          <?php if(!empty($pays_contact)): ?>
               // $("select[name='pays_personne']").val("<?=$pays_contact?>");
               // $("select[name='pays_personne']").trigger("chosen:updated");
          <?php endif;?> 

<?php endif;?>


          <?php if(isset($importDemande->id_gender)&&$importDemande->id_gender>0): ?>
               var id_sexe_demande=<?=$importDemande->id_gender?>;

               $("input[name_checkbox='civilite_personne']").each(function(){
               
                    if($(this).val()==id_sexe_demande)
                    {
                         if($(this).is(":checked")){}else{$(this).trigger("click")};
                    }
               });
          <?php endif; ?>

          <?php if(isset($importDemande->id_lang)&&$importDemande->id_lang>0): ?>
              
               var id_lang_demande=<?=$importDemande->id_lang;?>;
             
               $("input[name_checkbox='langue_personne']").each(function(){
                   
                    if($(this).val()==id_lang_demande)
                    {
                        
                        if($(this).is(":checked")){}else{$(this).trigger("click")};
                    }
               });
          <?php endif; ?>
   

   //ce qui concerne la demande
<?php if(!empty($importDemande->id_demande_origine)): ?>
     $("select[name='demande_origine']").val("<?=$id_demande_origine?>");
     $("select[name='demande_origine']").trigger("chosen:updated");
<?php endif;?> 

   <?php if(!empty($importDemande->address_pc)): ?>
        $("select[name='adresse_fr_cp']").val("<?=str_replace('"', '\"', trim($importDemande->address_pc))?>");
        $("select[name='adresse_fr_cp']").trigger("chosen:updated");
   <?php endif;?> 

   <?php if(isset($importDemande->address_fr)): ?>
        $("input[name='adresse_fr_bien']").val("<?=str_replace('"', '\"', trim($importDemande->address_fr))?>");
   <?php endif;?> 

   <?php if(isset($importDemande->adress_nl)): ?>
        $("input[name='adresse_nl_bien']").val("<?=str_replace('"', '\"', trim($importDemande->adress_nl))?>");
   <?php endif;?> 


   <?php if(isset($importDemande->subject)): ?>
        $("input[name='nom_demande']").val("<?=str_replace('"', '\"', trim($importDemande->subject))?>");
   <?php endif;?> 

   <?php if(isset($importDemande->subject)): ?>
        $("input[name='visite_nom_demande']").val("<?=str_replace('"', '\"', trim($importDemande->subject))?>");
   <?php endif;?> 

   <?php if(isset($importDemande->subject)): ?>
        $("input[name='accompagnement_nom_demande']").val("<?=str_replace('"', '\"', trim($importDemande->subject))?>");
   <?php endif;?> 


   <?php if(isset($importDemande->id_type_demande)): ?>
    var id_type_demande=<?=$importDemande->id_type_demande;?>;
   $("input[name_checkbox='id_type_demande']").each(function(){
 
        if($(this).val()==id_type_demande)
        {
          if($(this).is(":checked")){}else{$(this).trigger("click")};
        }
    });
   <?php endif; ?>

  


 
  
     
   
   //ce qui concerne le bien
   
   <?php if($importDemande->id_bien==0):?>
        

          <?php if(isset($importDemande->bt)): ?>
               $("input[name='adresse_bt']").val("<?=$importDemande->bt?>");
          <?php endif;?> 

          <?php if(isset($importDemande->etage_logement_bien)): ?>
               $("input[name='etage_logement_bien']").val("<?=$importDemande->etage_logement_bien?>");
          <?php endif;?> 
          
         
   
<?php endif;?>

<?php if(isset($importDemande->id_building_type)&&$importDemande->id_building_type>0): ?>
          var id_type_bien_coche=<?=$importDemande->id_building_type;?>;
          $("input[name_checkbox='id_type_bien_coche']").each(function(){
          
               if($(this).val()==id_type_bien_coche)
               {
                    if($(this).is(":checked")){}else{$(this).trigger("click")};
               }
          });
<?php endif; ?>

<?php if(isset($importDemande->rel_personne_bien)): ?>
               $("select[name='rel_personne_bien']").val("<?=$importDemande->rel_personne_bien[0]?>");
               $("select[name='rel_personne_bien']").trigger("chosen:updated");
          <?php endif;?> 

   <?php if(isset($importDemande->id_bien)&&!empty($importDemande->id_bien)&&$importDemande->id_bien>0): ?>
        $("input[name='id_entity_bien']").val("<?=$importDemande->id_bien?>");
   <?php endif;?> 

   <?php if(isset($importDemande->id_personne)&&!empty($importDemande->id_personne)&&$importDemande->id_personne>0): ?>
        $("input[name='id_entity_personne']").val("<?=$importDemande->id_personne?>");
   <?php endif;?> 

     //updateTextareaHeight(c);
     $.each($('textarea'), function() {
        var offset = this.offsetHeight - this.clientHeight;
        var resizeTextarea = function(e) {
            $(e).css('height', 'auto').css('height', e.scrollHeight + offset);
        };
        $(this).on('keyup input', function() { resizeTextarea(this); });
        resizeTextarea(this);
    });
})
</script> 