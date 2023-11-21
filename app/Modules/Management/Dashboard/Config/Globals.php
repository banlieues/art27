<?php

namespace Dashboard\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Dashboard';
    public $path = APPPATH . 'Modules/Management/Dashboard';

    public $t_contact = 'contact';

}