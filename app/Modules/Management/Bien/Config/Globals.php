<?php

namespace Bien\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Bien';
    public $path = APPPATH . 'Modules/Management/Bien';

    public $t_bien = 'bien';
    public $t_bien_geocode = 'bien_geocode';
}