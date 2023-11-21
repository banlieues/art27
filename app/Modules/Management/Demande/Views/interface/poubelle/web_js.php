<?php
     
     $personnes=$this->ban_crud_model->read_data("personne","adresse,localite,pays","id_personne=".$importDemande->id_personne);
    // print_r($personnes);
     $adresse_contact=NULL;
     $localite_contact=NULL;
     $pays_contact=NULL;

   
     if(!empty($personnes)&&!empty($personnes[0]->adresse))
     {
          $adresse_contact=$personnes[0]->adresse;
          $localite_contact=$personnes[0]->localite;
          if(!empty($personnes[0]->pays))
          {
               $pays_contact=$personnes[0]->pays;

          }
     }
    else
    {
        /* echo '<h1>toot';
         echo $importDemande->adresse_fr;
         echo '</h1>';*/
     if(!empty($importDemande->adresse_nl))
     {
          $adresse_contact=$importDemande->adresse_nl;  
          
     }    
     if(!empty($importDemande->adresse_fr))
     {
          $adresse_contact=$importDemande->adresse_fr;
     }
  
     if(!empty($importDemande->adresse_fr_cp))

          {
               $localites=$this->ban_crud_model->read_data("liste_localite");

               $where_cp=array();
               $where_condition_cp["cp"]=$importDemande->adresse_fr_cp;
               array_push($where_cp, $where_condition_cp);

               $localites=$this->ban_crud_model->read_data("liste_localite","label",$where_cp);
               $cp_localite_fr=$importDemande->adresse_fr_cp;

               if(!empty($localites))
               {
                    $localite_contact=$localites[0]->label;
               }
               else
               {
                    if(!empty($importDemande->infos->address_city->value))
                    {

                         $where_cp_2=array();
                         $where_condition_cp_2["cp"]=$importDemande->infos->address_city->value;
                         array_push($where_cp_2, $where_condition_cp_2);
                         $localites_2=$this->ban_crud_model->read_data("liste_localite","label",$where_cp_2);
                              if(!empty($localites_2))
                              {
                                   $localite_contact=$localites_2[0]->label;
                                   $cp_localite_fr=$importDemande->infos->address_city->value;

                              }
                    }
                    else
                    {
                         $localite_contact=$importDemande->adresse_fr_cp;
                    }
                    
               }
          }


    }

/*echo "<h1>Voic les adresses</h1>";
    print_r($adresse_contact);
    echo "<br>";
    print_r($localite_contact);*/

?>

<script>
jQuery(document).ready(function()
{

   <?php if(isset($importDemande->prenom)): ?>
        $("input[name='prenom_personne_no_obligatoire']").val("<?=$importDemande->prenom?>");
   <?php endif;?> 


   <?php if(isset($importDemande->nom)): ?>
        $("input[name='nom_personne_no_obligatoire']").val("<?=$importDemande->nom?>");
   <?php endif;?> 


   <?php if(isset($importDemande->email)): ?>
        $("input[name='email_personne']").val("<?=$importDemande->email?>");
   <?php endif;?> 


   <?php if(isset($importDemande->telephone)): ?>
        $("input[name='telephone_personne']").val("<?=$importDemande->telephone?>");
   <?php endif;?> 

   <?php if(isset($importDemande->telephone)): ?>
        $("input[name='telephone_personne']").val("<?=$importDemande->telephone?>");
   <?php endif;?> 

   <?php if(!empty($adresse_contact)): ?>
        $("input[name='adresse_personne']").val("<?=$adresse_contact?>");
   <?php endif;?> 

   <?php if(!empty($localite_contact)): ?>
        $("select[name='localite_personne']").val("<?=$localite_contact?>");
        $("select[name='localite_personne']").trigger("chosen:updated");
   <?php endif;?> 

   <?php if(!empty($pays_contact)): ?>
        $("select[name='pays_personne']").val("<?=$pays_contact?>");
        $("select[name='pays_personne']").trigger("chosen:updated");
   <?php endif;?> 


   

<?php if(!empty($importDemande->id_demande_origine)): ?>
     $("select[name='demande_origine']").val("<?=$id_demande_origine?>");
     $("select[name='demande_origine']").trigger("chosen:updated");
<?php endif;?> 

   <?php if(!empty($importDemande->adresse_fr_cp)): ?>
        $("select[name='adresse_fr_cp']").val("<?=$cp_localite_fr?>");
        $("select[name='adresse_fr_cp']").trigger("chosen:updated");
   <?php endif;?> 

   <?php if(isset($importDemande->adresse_fr)): ?>
        $("input[name='adresse_fr_bien']").val("<?=$importDemande->adresse_fr?>");
   <?php endif;?> 

   <?php if(isset($importDemande->adresse_nl)): ?>
        $("input[name='adresse_nl_bien']").val("<?=$importDemande->adresse_nl?>");
   <?php endif;?> 


   <?php if(isset($importDemande->subject)): ?>
        $("input[name='nom_demande']").val("<?=$importDemande->subject?>");
   <?php endif;?> 

   <?php if(isset($importDemande->subject)): ?>
        $("input[name='visite_nom_demande']").val("<?=$importDemande->subject?>");
   <?php endif;?> 

   <?php if(isset($importDemande->subject)): ?>
        $("input[name='accompagnement_nom_demande']").val("<?=$importDemande->subject?>");
   <?php endif;?> 


   <?php if(isset($importDemande->id_type_demande)): ?>
    var id_type_demande=<?=$importDemande->id_type_demande;?>;
   $("input[name_checkbox='id_type_demande']").each(function(){
 
        if($(this).val()==id_type_demande)
        {
           $(this).trigger("click");
        }
    });
   <?php endif; ?>

   <?php if(isset($importDemande->id_sexe)): ?>
    var id_sexe_demande=<?=$importDemande->id_sexe;?>;
   $("input[name_checkbox='civilite_personne']").each(function(){
 
        if($(this).val()==id_sexe_demande)
        {
           $(this).trigger("click");
        }
    });
   <?php endif; ?>


   <?php if(isset($importDemande->id_type_bien_coche)): ?>
    var id_type_bien_coche=<?=$importDemande->id_type_bien_coche;?>;
   $("input[name_checkbox='id_type_bien_coche']").each(function(){
 
        if($(this).val()==id_type_bien_coche)
        {
           $(this).trigger("click");
        }
    });
   <?php endif; ?>
  
   <?php if(isset($importDemande->infos->id_lang->value)): ?>
    var id_lang_demande=<?=$importDemande->infos->id_lang->value;?>;
   $("input[name_checkbox='langue_personne']").each(function(){
 
        if($(this).val()==id_lang_demande)
        {
           $(this).trigger("click");
        }
    });
   <?php endif; ?>


   

   <?php if(isset($importDemande->bt)): ?>
        $("input[name='adresse_bt']").val("<?=$importDemande->bt?>");
   <?php endif;?> 

   <?php if(isset($importDemande->etage_logement_bien)): ?>
        $("input[name='etage_logement_bien']").val("<?=$importDemande->etage_logement_bien?>");
   <?php endif;?> 
   
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

 
})
</script> 




<?php
/*

  [rel_personne_bien] => Array
        (
            [0] => 2
        )

    [id_personne] => 172613
    [prenom] => Florence
    [nom] => Lerat
    [email] => florence.lerat@gmail.com
    [email2] => 
    [telephone] => 0484610520
    [telephone2] => 
    [id_bien] => 109631796
    [id_type_bien_coche] => 1
    [adresse_fr] => Avenue Ernest Claes 10 1160 Auderghem
    [adresse_nl] => Ernest Claeslaan 10 1160 Oudergem
    [bt] => 
    [moyen_contact] => 5

    */
   ?> 