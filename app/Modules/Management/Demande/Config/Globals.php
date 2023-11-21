<?php

namespace Demande\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Demande';
    public $path = APPPATH . 'Modules/Management/Demande';

    public $t_bien = 'bien';
    public $t_bien_demande = 'personne_bien';
    public $t_bien_geocode = 'bien_geocode';
    public $t_demande = 'demande';
}