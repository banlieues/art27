<?php

namespace Custom\Config;

use CodeIgniter\Config\BaseConfig;

class Autorisation extends BaseConfig
{
    public $entities = [
        "autorisation" => "Autorisations",
        "partenaire_social"=>"Partenaires sociaux",
        "partenaire_culturel"=>"Partenaires culturels",
        //"bien" => "Biens",
        //"calculator" => "Calculette",
        //"contact" => "Contacts",
        //"demande" => "Demandes",
        "document"=>"Documents",
        //"doublon"=>"Doublons",
        //"email" => "Emails",
        //"enquete" => "Enquetes",
        //"liste" => "Liste des listes",
        // "formulaire"=>"Formulaire",
        //"email_template" => "Modèle d'emails",
        //"pole" => "Pôles",
        //"company" => "Ready to renov",
        //"rdv" => "Rendez-vous",
        "requete" => "Requêtes",
        "dashboard" => "Tableau de bord",
        //"tache" => "Tâches",
        //"tesorus" => "Thesaurus",
         "modelisation"=>"Modélisation",
        "user" => "Utilisateurs",
    ];

    public $outils = [
        "dashboard_admin" => "Accès aux dashboards de tous les utilisateurs",
        "dashboard_user" => "Accès au dashboard",
       // "demande_web_c" => "Traiter les demandes du dépôt web",
        //"email_all_r" => "Voir tous les emails",
        //"enquete_all_r" => "Voir les enquêtes de tous les utilisateurs",
        //"enquete_form_r" => "Voir les formulaires et leurs questions",
        //"enquete_form_u" => "Modifier les formulaires et les questions",
        //"enquete_form_d" => "Supprimer les formulaires et les questions",

        // "requete_r"=>"Création de requête",
        // "requetelist"=>"Liste de requête",
        // "requete_effacer"=>"Effacer une requête dans la liste",

        // "modelisation"=>"Accès au module Modélisation",

        // "formulaire"=>"Création de formulaire",

        // "document"=>"Création de modèle de documents",
        // "document_send"=>"Envoi de documents",
        // "document_download"=>"Téléchargement de documents",
        // "document_certificat"=>"Téléchargement de certificat",
        // "document_liste"=>"Téléchargement de liste",

        // "doublon"=>"Accès au module Doublon",

        // "importation"=>"Importation",
    ];

    public $default_autorisation = [ 
       
        "dashboard_user",
        "partenaire_social_c",
        "partenaire_social_r",
        "partenaire_social_u",
        "partenaire_social_d",
        "partenaire_culturel_c",
        "partenaire_culturel_r",
        "partenaire_culturel_u",
        "partenaire_culturel_d",
	    
	    // "binterface",
	    // "tinterface",
	    // "ginterface",
	    // "einterface2",
	    // "bdinterface2",
    ];

}






