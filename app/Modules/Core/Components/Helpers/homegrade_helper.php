 <?php
 
 if (!function_exists('signature_homegrade'))
{
   function signature_homegrade()
   {
      
         $view="";
         $view.='
         <div style="margin-top: 5px">
            <a href="https://homegrade.brussels" >
                <img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">
            </a>
        </div>
        <div style="margin-top: 10px; font-size: 10px">
            <span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br/>
            <span style="color: #F02D37"><strong>Tel</strong></span> <span style="color: #3C3C3C;"> 1810</span>
        </div>
        <div style="margin-top: 5px; font-size: 10px">
            <a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;">
                <span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span>
            </a>
        </div>
        <div style="margin-top: 15px">
            <a href="https://www.homegrade.brussels" >
                <img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg">
            </a>
        </div>
    ';         
   
//    $view.='<div style="margin-top: 15px"><a href="https://www.homegrade.brussels" ><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg"></a></div>
//    <div style="margin-top: 10px; font-size: 10px"><span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br/>
//    <span style="color: #F02D37"><strong>Tel</strong></span><span style="color: #3C3C3C;"> 1810</span><br/>
//    <span style="color: #F02D37"><strong>@</strong></span> <a href="mailto:info@homegrade.brussels"> <span style="color: #3C3C3C;">info@homegrade.brussels</span></a></div>
//    <div style="margin-top: 5px; font-size: 10px"><a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;"><span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span></a></div>
//    <div style="margin-top: 15px"><a href="https://www.homegrade.brussels" ><img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg"></a></div>';
      
      return $view; 
   }
}

function signature_homegrade_old()
{
   return '<div style="margin-top: 15px"><a href="https://www.homegrade.brussels"><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg"></a></div>';
}

function signature_homegrade_old_2()
{
   return '<div style="margin-top: 10px; font-size: 10px"><span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br>
   <span style="color: #F02D37"><strong>Tel</strong></span><span style="color: #3C3C3C;"> 1810</span><br>
   <span style="color: #F02D37"><strong>@</strong></span> <a href="mailto:info@homegrade.brussels"> <span style="color: #3C3C3C;">info@homegrade.brussels</span></a></div>';
}


if (!function_exists('from_demande_homegrade'))
{

      function from_demande_homegrade($sender_email,$subject) 
      {
         if(
            strpos($subject,"Re:")!== false
            ||strpos($subject,"RE:")!== false
            ||strpos($subject,"Fwd:")!== false
            ||strpos($subject,"TR:")!== false
            ||strpos($subject,"Tr:")!== false
         ) 
         {
            if(strpos($sender_email,"@homegrade.brussels")!== false) 
            {
               return "conseiller";
            }
            else
            {
               return "demandeur";
            }

         }



         if(strpos($sender_email,"@homegrade.brussels")!== false) 
         {
            
            if(strpos($subject,"Accusé de réception")!== false)
            {

               
               return "crm";

            }
            elseif(strpos($subject,"Update d'une demande")!== false)
            {

               return "crm";

            }
         
            elseif(strpos($subject,"Assignation d'une demande")!== false)
            {

               return "crm";

            }
            
            elseif(strpos($subject,"Updating van een aanvraag")!== false)
            {

               return "crm";

            }

            elseif(strpos($subject,"Toewijzing van een aanvraag")!== false)
            {

               return "crm";

            }

            elseif(strpos($subject,"Ontvangstbevestiging van uw aanvraag")!== false)
            {

               return "crm";

            }

         
            else
            {
               return "conseiller";
            }


         }
         else
         {
            return "demandeur";
         }
      }
}


function nettoye_body_mail($body_mail,$body_mail_deja_affiche)
{
   
   $element_deleted=[
      '<img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg">',
      '<img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">'
   ];
   //debug($body_mail_deja_affiche);
   $body_mail_corrige=str_replace($element_deleted,NULL,$body_mail);
   $body_mail_corrige=str_replace("<img","<img width='100%' ",$body_mail_corrige);

   /*foreach($body_mail_deja_affiche as $bm)
   {
      //$body_mail_corrige="<b>$bm</b>";
      $body_mail_corrige=str_replace($bm,"....",$body_mail_corrige);

   }*/

   /*
   $body_a=explode("<hr",$body_mail_corrige);

   if(!empty($body_a))
   {
      $body_mail_corrige=$body_a[0];
   }*/
   


   return $body_mail_corrige;
}

function nettoye_body_mail_image($body_mail)
{
   
   //debug($body_mail_deja_affiche);
  
   $body_mail_corrige=str_replace("<img","<img width='100%' ",$body_mail);

  // $body_mail_corrige=str_replace($body_mail_deja_affiche,"..Voir message précédent..",$body_mail_corrige);

   return $body_mail_corrige;
}



function is_image($name_file)
{
   $extensions=[".jpg",".JPG",".JPEG",".jpeg",".JPG",".png",".PNG",".gif",".GIF"];

   foreach($extensions as $extension)

   if(strpos($name_file,$extension)!== false)
      return true;


   return false;   
}

function affiche_type_demande($string)
{
   switch ($string)
   {
      case "telephone":
         return "Téléphone";

      default:
         return $string;
   }
}

function affiche_contact_bien($adresses,$separator="@SEPARATOR@")
{
   $view=NULL;
   if(!empty($adresses))
   {
      $adresses_array=explode($separator,$adresses);

      if(!empty($adresses_array))
      {
         foreach($adresses_array as $adresse)
         {
            if(!empty(trim($adresse)))
            {
               $view.="<a class='btn btn-sm btn-success mx-1 mb-1'>";
                 
                  $rels=explode("@@rel@@",trim($adresse));
                  if(!empty($rels[0]))
                  {
                     $view.=$rels[0];
                     $view.="<br>";
                  }

                  if(!empty($rels[1]))
                  {
                     
                       
                           $view.="<small>".$rels[1]."</small>";

                     
                     
                     
                  }
                  
               
               $view.="</a>";
            }
         }
      }
   }
}

function affiche_adresse_bien($adresses,$separator="@SEPARATOR@")
{
   //debug($adresses);
   $view=NULL;
   if(!empty($adresses))
   {
      $adresses_array=explode($separator,$adresses);

      if(!empty($adresses_array))
      {
         foreach($adresses_array as $adresse)
         {
            if(!empty(trim($adresse)))
            {  
               $rels=explode("@@rel@@",trim($adresse));
               if(isset($rels[2])&&!empty($rels[2]))
               {
               $view.="<a href='".base_url()."/bien/fiche/".$rels[2]."' class='btn btn-sm btn-orange mx-1 mb-1'>";
               $view.= '<i class="far fa-building"></i> ';
                 
                
                  if(!empty($rels[0]))
                  {
                     $view.=$rels[0];
                     $view.="<br>";
                  }
                  else
                  {
                     $view.="<i>Adresse inconnue</i><br>";
                  }

                  if(!empty($rels[1]))
                  {
                     
                       
                           $view.="<small>".$rels[1]."</small>";

                     
                     
                     
                  }
                  
               
               $view.="</a>";
               }
            }
         }
      }
   }



   return $view;
}

function affiche_adresse_contact($adresses,$separator="@SEPARATOR@")
{
   //debug($adresses);
   $view=NULL;
   if(!empty($adresses))
   {
      $adresses_array=explode($separator,$adresses);

      if(!empty($adresses_array))
      {
         foreach($adresses_array as $adresse)
         {
            if(!empty(trim($adresse)))
            {  
               $rels=explode("@@rel@@",trim($adresse));

               if(isset($rels[2])&&!empty($rels[2]))
               {
                     $view.="<a href='".base_url()."/contact/fiche/".$rels[2]."' class='btn btn-sm btn-success mx-1 mb-1'>";
                     $view.= '<i class="fas fa-user"></i> ';
                     
                     
                        if(!empty($rels[0]))
                        {
                           $view.=$rels[0];
                           $view.="<br>";
                        }

                        if(!empty($rels[1]))
                        {
                           
                           
                                 $view.="<small>".$rels[1]."</small>";

                           
                           
                           
                        }
                        
                     
                     $view.="</a>";
               }
            }
         }
      }
   }



   return $view;
}

function affiche_adresse_contact_from_sql($contacts)
{
   //debug($adresses);
   $view=NULL;

   $deja_affiche=[];
   foreach($contacts as $contact)
   {
      if(!empty($contact->id_contact)&&!in_array($contact->id_contact,$deja_affiche))
      {
         $view.="<a href='".base_url()."/contact/fiche/".$contact->id_contact."' class='btn btn-sm btn-success mx-1 mb-1'>";
         $view.= '<i class="fas fa-user"></i> ';
         $view.=$contact->prenom_contact.' ';   
         $view.=$contact->nom_contact.' ';
         if(!empty($contact->statut_relation_bien)) 
         {
            $view.="(";
            $view.=$contact->statut_relation_bien;  
            $view.=")";     
         }            
         
                        
                        
         $view.="</a>";

         $deja_affiche[]=$contact->id_contact;
      }
   }
  
                     
               
         



   return $view;
}

function affiche_adresse_bien_from_sql($biens)
{
   //debug($adresses);
   $view=NULL;

   $deja_affiche=[];
   foreach($biens as $bien)
   {
      if(!empty($bien->id_bien)&&!in_array($bien->id_bien,$deja_affiche))
      {
         $view.="<a href='".base_url()."/bien/fiche/".$bien->id_bien."' class='btn btn-sm btn-orange mx-1 mb-1'>";
         $view.= '<i class="far fa-building"></i> ';
         $view.=$bien->adresse_fr.' ';   
      
                     
         $view.="</a>";
         $deja_affiche[]=$bien->id_bien;
      }
      
      
   }
  

   return $view;
}

function affiche_adresse_demande_from_sql($demandes)
{
   //debug($adresses);
   $view=NULL;

   foreach($demandes as $demande)
   {
    
      $view.="<a href='".base_url()."/demande/fiche/".$demande->id_demande."' class='btn btn-sm btn-amethyst mx-1 mb-1'>";
      $view.= '<i class="far fa-clipboard"></i> N°';
      $view.=$demande->id_demande.' ';   
      
                     
      $view.="</a>";
      
   }
  

   return $view;
}

function corrige_personne_contact($query)
{

//echo $query;
   $query=str_replace("LEFT JOIN personne ON personne.id_personne = personne_bien.id_personne","LEFT JOIN contact ON contact.id_contact = personne_bien.id_contact LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil",$query);
   $query=str_replace("LEFT JOIN personne ON personne.id_personne=personne_bien.id_personne","LEFT JOIN contact ON contact.id_contact = personne_bien.id_contact LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil",$query);


   $query=str_replace("personne","contact",$query);
   $query=str_replace("contact_bien","personne_bien",$query);

   $query=str_replace("id_user","id",$query);
   $query=str_replace("id_create","id_user_create",$query);

   $query=str_replace("contact.prenom_contact","contact.prenom",$query);
   $query=str_replace("contact.nom_contact","contact.nom",$query);

   $query=str_replace("contact.prenom","contact.prenom_contact",$query);
   $query=str_replace("contact.nom","contact.nom_contact",$query);

   //corrige ce qui cocnerne contact profil

   $champs=["email","telephone","localite","adresse","id_type_personne"];

   foreach($champs as $champ)
   {
      $query=str_replace("contact.$champ","contact_profil.$champ",$query);
   }

   $query=str_replace("users.id_user","user_accounts.id",$query);

   $query=str_replace("users","user_accounts",$query);

   return $query;
}

function corrige_index_personne($index)
{
   $index=str_replace("prenom_personne","prenom_contact",$index);
   $index=str_replace("nom_personne","nom_contact",$index);
   $index=str_replace("email_personne","email",$index);
   $index=str_replace("id_personne","id_contact",$index);

   
   return $index;
}

/*
function correction_insert_demande($string)
{
   return str_replace("[]",NULL,$string);
}*/
