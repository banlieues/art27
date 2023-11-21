<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Config_autorisation extends BaseConfig
{

    public  $config=
    [

        "entities_config"=>[
            "bien"=>"Biens",
            "contact"=>"Contacts",
            "demande"=>"Demandes",
            "company"=>"Ready to renov",
            "tesorus"=>"Thesaurus",
            "calculator"=>"Calculateur",
            "enquete"=>"Enquetes",
            "requete"=>"Requêtes",
            "modelisation"=>"Modélisation",
            "document"=>"Document",
            "formulaire"=>"Formulaire",
        ],
  
       "outils_config"=>[
            "enquete_r_all"=>"Voir les enquêtes de tous les utilisateurs",
            "enquete_r_form"=>"Voir les formulaires et leurs questions",
            "enquete_u_form"=>"Modifier les formulaires et les questions",
            "enquete_d_form"=>"Supprimer les formulaires et les questions",

            "requete"=>"Création de requête",
            "requetelist"=>"Liste de requête",
            "requete_effacer"=>"Effacer une requête dans la liste",
            "modelisation"=>"Modèlisation",
            "formulaire"=>"Création de formulaire",

            "document"=>"Création de modèle de documents",

            "document_send"=>"Envoi de documents",
            "document_download"=>"Téléchargement de documents",
            "document_certificat"=>"Téléchargement de certificat",
            "document_liste"=>"Téléchargement de liste",

           

            "doublon"=>"Doublon",
            "importation"=>"Importation",
            "utilisateur"=>"Liste des utilisateurs",
            "utilisateur_ajouter"=>"Ajouter un utilisateur dans la liste des utilisateurs",
            "utilisateur_modifier"=>"Permettre à l'utilisateur connecté de modifier les données d'un autre utilisateur",
            "utilisateur_supprimer"=>"Permettre à l'utilisateur connecté de supprimer un autre utilisateur",
            
           

            "autorisation"=>"Permettre à l'utilisateur connecté de voir les autorisations d'un autre utilisateur",
            "autorisation_modifier"=>"Modifier les autorisations",
       ],
  
       "default_autorisation"=>[
            "contact_r",
            "activities_r",
            "registrations_r",
            "payements_r",
            "lieu_r",
            "requetelist_a",
           
       ],
       
    ];
}






