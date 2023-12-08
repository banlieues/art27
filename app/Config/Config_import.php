<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Config_import extends BaseConfig
{

    public  $config=
    [
        "entity_import"=>["partenaire_social","partenaire_culturel"], //entity possible pour la traque des doublons
        "doublons"=>["name_partenaire_social","name_partenaire_culturel"], //entity possible pour la traque des doublons
      
      
    ];

  
}






