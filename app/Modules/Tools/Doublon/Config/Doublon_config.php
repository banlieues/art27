<?php

namespace Doublon\Config;

use CodeIgniter\Config\BaseConfig;

class Doublon_config extends BaseConfig
{

    public  $entity_doublon=
    [
        "contact"=>
            [
                    "index_key_entity"=>"id_contact_contact", //sous forme d'index descriptor et pas le fieldql
                    //champs à afficher ppour la recherche en comparaison de champs
                    "fields"=>
                    [
                        "nom_contact",
                        "prenom",
                        "nom_court_institution",
                        "nom_long_institution",
                        "email",
                        "email2",
                        "adresse",
                        "localite",
                        
                    ],

                    "table_update"=>["inscriptions","inscriptions_document"],

                    "mise_en_evidence"=>["id_contact_contact","date_creation","date_maj","maj"],

                    //moteur de recherche 
                    "modelSearch"=>"contactsModel", //nom du model
                    "methodSearch"=>"getListContact",//nom de la methode
                    "idSearch"=>"id_contact",//id à passer
                    "fieldSearch"=>["id_contact",["nom","prenom"],"nom_court_institution",["adresse","codepostal","localite"],"email","email2"], //les champs à afficher
                        
                 
            ]
    ];

    
}






