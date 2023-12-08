<?php

     /** ----------------          Jointure de partenaire social      -------------------------------------------*/
     $jointure["partenaire_social"]["partenaire_social_convention"]=
    [ 
        ["table"=>"partenaire_social_convention","condition"=> "partenaire_social_convention.id_partenaire_social=partenaire_social.id_partenaire_social"]
    ];


    $jointure["partenaire_social"]["partenaire_culturel"]=
    [ 
        ["table"=>"convention_barcode","condition"=> "convention_barcode.id_partenaire_social=partenaire_social.id_partenaire_social"],
        ["table"=>"ticket","condition"=> "ticket.id_ticket=convention_barcode.id_ticket"],
        ["table"=>"partenaire_culturel","condition"=> "partenaire_culturel.id_partenaire_culturel=ticket.id_partenaire_culturel"],
      
    ];
     /** ----------------          Jointure de partenaire social convention     -------------------------------------------*/

     $jointure["partenaire_social_convention"]["partenaire_social"]=
     [ 
         ["table"=>"partenaire_social","condition"=> "partenaire_social.id_partenaire_social=partenaire_social_convention.id_partenaire_social"]
     ];

     $jointure["partenaire_social_convention"]["partenaire_culturel"]=
     [ 
        ["table"=>"partenaire_social","condition"=> "partenaire_social.id_partenaire_social=partenaire_social_convention.id_partenaire_social"],
        ["table"=>"convention_barcode","condition"=> "convention_barcode.annee=partenaire_social_convention.annee_convention_partenaire_social"],
         ["table"=>"convention_barcode","condition"=> "convention_barcode.id_partenaire_social=partenaire_social.id_partenaire_social"],
         ["table"=>"ticket","condition"=> "ticket.id_ticket=convention_barcode.id_ticket"],
         ["table"=>"partenaire_culturel","condition"=> "partenaire_culturel.id_partenaire_culturel=ticket.id_partenaire_culturel"],
       
     ];

     /** ----------------          Jointure de partenaire culturel      -------------------------------------------*/
     $jointure["partenaire_culturel"]["partenaire_social"]=
    [ 
        ["table"=>"ticket","condition"=> "ticket.id_partenaire_culturel=partenaire_culturel.id_partenaire_culturel"],
        ["table"=>"convention_barcode","condition"=> "convention_barcode.id_ticket=ticket.id_ticket"],
        ["table"=>"partenaire_social","condition"=> "partenaire_social.id_partenaire_social=convention_barcode.id_partenaire_social"],
    ];

    $jointure["partenaire_culturel"]["partenaire_social_convention"]=
    [ 
        ["table"=>"ticket","condition"=> "ticket.id_partenaire_culturel=partenaire_culturel.id_partenaire_culturel"],
        ["table"=>"convention_barcode","condition"=> "convention_barcode.id_ticket=ticket.id_ticket"],
        ["table"=>"partenaire_social","condition"=> "partenaire_social.id_partenaire_social=convention_barcode.id_partenaire_social"],
        ["table"=>"partenaire_social_convention","condition"=> "partenaire_social_convention.annee_convention_partenaire_social=convention_barcode.annee"]

    ];


    /** ----------------          Jointure de contact       -------------------------------------------*/

    $jointure["contact"]["contact_profil"]=
    [ 
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact=contact.id_contact"]
    ];

    $jointure["contact"]["demande_caracteristique"]=
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["contact"]["demande"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"]
    ];

    $jointure["contact"]["bien_caracteristique"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["contact"]["personne_bien"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"]
    ];

    $jointure["contact"]["bien"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["contact"]["rdv"] =
    [ 
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_contact=contact.id_contact"],["table"=>"rdv","condition"=> "rdv.id_rdv=contact_rdv.id_rdv"]
    ];

    $jointure["contact"]["tache"] =
    [ 
        ["table"=>"contact_tache","condition"=> "contact_tache.id_contact=contact.id_contact"],
        ["table"=>"tache","condition"=> "tache.id_tache=contact_tache.id_tache"]
    ];

    $jointure["contact"]["email_outlook"] =
    [ 
        
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact=contact.id_contact"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["contact"]["encodage"] =
    [ 
        ["table"=>"encodage","condition"=> "encodage.id_contact=contact.id_contact"]
    ];

    /** ----------------          Jointure de contact_profil      -------------------------------------------*/


    $jointure["contact_profil"]["contact"] =
    [ 
        
        ["table"=>"contact","condition"=> "contact.id_contact=contact_profil.id_contact"]
    ];

    $jointure["contact_profil"]["demande_caracteristique"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["contact_profil"]["demande"] =
    [  
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"]
    ];

    $jointure["contact_profil"]["bien_caracteristique"] =
    [  
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["contact_profil"]["personne_bien"] =
    [  
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"]
    ];

    $jointure["contact_profil"]["bien"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["contact_profil"]["rdv"] =
    [  
        ["table"=>"contact","condition"=> "contact.id_contact=contact_profil.id_contact"],
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_contact=contact.id_contact"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=contact_rdv.id_rdv"]
    ];

    $jointure["contact_profil"]["tache"] =
    [ 
        ["table"=>"contact","condition"=> "contact.id_contact=contact_profil.id_contact"],
        ["table"=>"contact_tache","condition"=> "contact_tache.id_contact=contact.id_contact"],
        ["table"=>"tache","condition"=> "tache.id_tache=contact_tache.id_tache"]
    ];

    $jointure["contact_profil"]["email_outlook"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_contact_profil=contact_profil.id_contact_profil"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["contact_profil"]["encodage"] =
    [ 
        ["table"=>"contact","condition"=> "contact.id_contact=contact_profil.id_contact"],
        ["table"=>"encodage","condition"=> "encodage.id_contact=contact.id_contact"]
    ];


    /** ----------------          Jointure de demande      -------------------------------------------*/


    $jointure["demande"]["contact_profil"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact_profil=personne_bien.id_contact_profil"]
    ];

    $jointure["demande"]["demande_caracteristique"] =
    [ 
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["demande"]["contact"] =
    [
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"contact","condition"=> "contact.id_contact=personne_bien.id_contact"]
    ];

    $jointure["demande"]["bien_caracteristique"] =
    [   
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["demande"]["personne_bien"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"]
    ];

    $jointure["demande"]["bien"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["demande"]["rdv"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=demande.id_demande"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=demande_rdv.id_rdv"]
    ];

    $jointure["demande"]["tache"] =
    [
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=demande.id_demande"],
        ["table"=>"tache","condition"=> "tache.id_tache=demande_tache.id_tache"]
    ];

    $jointure["demande"]["email_outlook"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["demande"]["encodage"] =
    [ 
        ["table"=>"encodage","condition"=> "encodage.id_encodage=demande.id_encodage"]
    ];

    /** ----------------          Jointure de demande_caracteristique      -------------------------------------------*/


    $jointure["demande_caracteristique"]["contact_profil"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact_profil=personne_bien.id_contact_profil"]
    ];

    $jointure["demande_caracteristique"]["demande"] =
    [ 
        
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"]
    ];

    $jointure["demande_caracteristique"]["contact"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"contact","condition"=> "contact.id_contact=personne_bien.id_contact"]
    ];

    $jointure["demande_caracteristique"]["bien_caracteristique"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["demande_caracteristique"]["personne_bien"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"]
    ];

    $jointure["demande_caracteristique"]["bien"] =
    [
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["demande_caracteristique"]["rdv"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=demande.id_demande"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=demande_rdv.id_rdv"]
    ];

    $jointure["demande_caracteristique"]["tache"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=demande.id_demande"],
        ["table"=>"tache","condition"=> "tache.id_tache=demande_tache.id_tache"]
    ];

    $jointure["demande_caracteristique"]["email_outlook"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["demande_caracteristique"]["encodage"] =
    [     
        ["table"=>"demande","condition"=> "demande.id_demande=demande_caracteristique.id_demande"],
        ["table"=>"encodage","condition"=> "encodage.id_encodage=demande.id_encodage"]
    ];

    /** ----------------          Jointure de bien     -------------------------------------------*/


    $jointure["bien"]["contact_profil"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact_profil=personne_bien.id_contact_profil"]
    ];

    $jointure["bien"]["demande_caracteristique"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande","condition"=> "demande.id_demande=personnne_bien.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["bien"]["contact"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"contact","condition"=> "contact.id_contact=personne_bien.id_contact"]
    ];

    $jointure["bien"]["bien_caracteristique"] =
    [ 
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["bien"]["personne_bien"] =
    [  
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"]
    ];

    $jointure["bien"]["demande"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"]
    ];

    $jointure["bien"]["rdv"] =
    [  
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=personne_bien.id_demande"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=demande_rdv.id_rdv"]
    ];

    $jointure["bien"]["tache"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=personne_bien.id_demande"],
        ["table"=>"tache","condition"=> "tache.id_tache=demande_tache.id_tache"]
    ];

    $jointure["bien"]["email_outlook"] =
    [ 
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["bien"]["encodage"] =
    [ 
        ["table"=>"encodage","condition"=> "encodage.id_bien=contact.id_bien"]
    ];

    /** ----------------          Jointure de bien_caracterisque      -------------------------------------------*/

    $jointure["bien_caracteristique"]["contact_profil"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact_profil=personne_bien.id_contact_profil"]
    ];

    $jointure["bien_caracteristique"]["demande_caracteristique"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande","condition"=> "demande.id_demande=personnne_bien.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["bien_caracteristique"]["contact"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"contact","condition"=> "contact.id_contact=personne_bien.id_contact"]
    ];

    $jointure["bien_caracteristique"]["bien"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"]
    ];

    $jointure["bien_caracteristique"]["personne_bien"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"]
    ];

    $jointure["bien_caracteristique"]["demande"] =
    [
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"]
    ];

    $jointure["bien_caracteristique"]["rdv"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=personne_bien.id_demande"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=demande_rdv.id_rdv"]
    ];

    $jointure["bien_caracteristique"]["tache"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=personne_bien.id_demande"],
        ["table"=>"tache","condition"=> "tache.id_tache=demande_tache.id_tache"]
    ];

    $jointure["bien_caracteristique"]["email_outlook"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=bien_caracteristique.id_bien"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_bien=bien.id_bien"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=personne_bien.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["bien_caracteristique"]["encodage"] =
    [ 
        ["table"=>"encodage","condition"=> "encodage.id_bien=bien_caracteristique.id_bien"]
    ];


    /** ----------------          Jointure de rdv     -------------------------------------------*/

    $jointure["rdv"]["contact_profil"] =
    [ 
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact=contact_rdv.id_contact"]
    ];

    $jointure["rdv"]["demande_caracteristique"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_rdv.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["rdv"]["demande"] =
    [  
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_rdv.id_demande"]
    ];

    $jointure["rdv"]["bien_caracteristique"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_rdv.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=>"bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["rdv"]["personne_bien"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_rdv.id_demande"]
    ];

    $jointure["rdv"]["bien"] =
    [
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_rdv.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["rdv"]["contact"] =
    [ 
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"contact","condition"=> "contact.id_contact=contact_rdv.id_contact"]
    ];

    $jointure["rdv"]["tache"] =
    [ 
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"contact_tache","condition"=> "contact_tache.id_contact=contact_rdv.id_contact"],
        ["table"=>"tache","condition"=> "tache.id_tache=contact_tache.id_tache"]
    ];

    $jointure["rdv"]["email_outlook"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=demande_rdv.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["rdv"]["encodage"] =
    [ 
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_rdv=rdv.id_rdv"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_rdv.id_demande"],
        ["table"=>"encodage","condition"=> "encodage.id_encodage=demande.id_encodage"]
    ];

    /** ----------------          Jointure de tache      -------------------------------------------*/


    $jointure["tache"]["contact_profil"] =
    [ 
        ["table"=>"contact_tache","condition"=> "contact_tache.id_tache=tache.id_tache"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact=contact_tache.id_contact"]
    ];

    $jointure["tache"]["demande_caracteristique"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_tache.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["tache"]["demande"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_tache.id_demande"]
    ];

    $jointure["tache"]["bien_caracteristique"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_tache.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=>"bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["tache"]["personne_bien"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_tache.id_demande"]
    ];

    $jointure["tache"]["bien"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande_tache.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"]
    ];

    $jointure["tache"]["contact"] =
    [ 
        ["table"=>"contact_tache","condition"=> "contact_tache.id_tache=tache.id_tache"],
        ["table"=>"contact","condition"=> "contact.id_contact=contact_tache.id_contact"]
    ];

    $jointure["tache"]["rdv"] =
    [ 
        ["table"=>"contact_tache","condition"=> "contact_tache.id_tache=tache.id_tache"],
        ["table"=>"contact_rdv","condition"=> "contact_rdv.id_contact=contact_tache.id_contact"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=contact_rdv.id_rdv"],
    ];

    $jointure["tache"]["email_outlook"] =
    [ 
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=demande_tache.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];

    $jointure["tache"]["encodage"] =
    [  
        ["table"=>"demande_tache","condition"=> "demande_tache.id_tache=tache.id_tache"],
        ["table"=>"demande","condition"=> "demande.id_demande=demande_tache.id_demande"],
        ["table"=>"encodage","condition"=> "encodage.id_encodage=demande.id_encodage"]
    ];

    /** ----------------          Jointure de  email_outlook      -------------------------------------------*/


    $jointure["outlook_email"]["contact_profil"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact=personne_bien.id_contact"]
    ];

    $jointure["outlook_email"]["demande_caracteristique"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["outlook_email"]["demande"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"demande","condition"=> "demande.id_demande=personne_bien.id_demande"],
    ];

    $jointure["outlook_email"]["bien_caracteristique"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=bien.id_bien"]
    ];

    $jointure["outlook_email"]["personne_bien"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
    ];

    $jointure["outlook_email"]["bien"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"bien","condition"=> "bien.id_bien=personne_bien.id_bien"],
    ];

    $jointure["outlook_email"]["contact"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"contact","condition"=> "contact.id_contact=contact.id_contact"],
    ];

    $jointure["outlook_email"]["rdv"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=personne_bien.id_demande"],
        ["table"=>"rdv","condition"=> "rdv.id_rdv=demande_rdv.id_rdv"],
    ];

    $jointure["outlook_email"]["tache"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=personne_bien.id_demande"],
        ["table"=>"tache","condition"=> "tache.id_tache=demande_tache.id_tache"],
    ];

    $jointure["outlook_email"]["encodage"] =
    [ 
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_outlook=email_outlook.id_primary"],
        ["table"=>"demande","condition"=> "demande.id_demande=email_outlook_lien.id_demande"],
        ["table"=>"encodage","condition"=> "encodage.id_encodage=demande.id_encodage"]
    ];

    /** ----------------           Jointure de encodage      -------------------------------------------*/


    $jointure["encodage"]["contact_profil"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
        ["table"=>"contact_profil","condition"=> "contact_profil.id_contact_profil=personne_bien.id_contact_profil"], 
    ];

    $jointure["encodage"]["demande_caracteristique"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"demande_caracteristique","condition"=> "demande_caracteristique.id_demande=demande.id_demande"]
    ];

    $jointure["encodage"]["demande"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
    ];

    $jointure["encodage"]["bien_caracteristique"] =
    [ 
        ["table"=>"bien_caracteristique","condition"=> "bien_caracteristique.id_bien=encodage.id_bien"]
    ];

    $jointure["encodage"]["personne_bien"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"personne_bien","condition"=> "personne_bien.id_demande=demande.id_demande"],
    ];

    $jointure["encodage"]["bien"] =
    [ 
        ["table"=>"bien","condition"=> "bien.id_bien=encodage.id_bien"]
    ];

    $jointure["encodage"]["contact"] =
    [ 
        ["table"=>"contact","condition"=> "contact.id_contact=encodage.id_contact"]
    ];

    $jointure["encodage"]["rdv"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"demande_rdv","condition"=> "demande_rdv.id_demande=demande.id_demande"]

    ];

    $jointure["encodage"]["tache"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"demande_tache","condition"=> "demande_tache.id_demande=demande.id_demande"]
    ];

    $jointure["encodage"]["email_outlook"] =
    [ 
        ["table"=>"demande","condition"=> "demande.id_encodage=encodage.id_encodage"],
        ["table"=>"email_outlook_lien","condition"=> "email_outlook_lien.id_demande=demande.id_demande"],
        ["table"=>"email_outlook","condition"=> "email_outlook.id_primary=email_outlook_lien.id_email"]
    ];





?>
