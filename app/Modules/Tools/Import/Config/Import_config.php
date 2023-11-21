<?php

namespace Import\Config;

use CodeIgniter\Config\BaseConfig;

class Import_config extends BaseConfig
{
    public  $import_config=
    [
        "entity_import"=>["contact","registration"], //entity possible pour la traque des doublons
        "entity_liaison"=>["activities"], //entity qui assure liaison entre les entity possible, si définit, alors chaque fichier doit être lié à une activity
    ];
}






