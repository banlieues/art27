<?php

namespace Layout\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Layout';
    public $path = APPPATH . 'Modules/Core/Layout/';

    public $t_autorisation = 'user_autorisation';
}