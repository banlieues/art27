<?php

namespace Mapping\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Mapping';
    public $path = APPPATH . 'Modules/Core/Mapping';

    public $t_bien = 'bien';
    public $t_bien_geocode = 'bien_geocode';
}