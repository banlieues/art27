<?php

namespace Administrator\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Administrator';
    public $path  = APPPATH . 'Modules/Core/Administrator/';

    public $t_autorisation = 'user_autorisation';
    public $t_l_user_role = 'list_role';
    public $t_user = 'user_accounts';
    public $t_user_profile = 'user_profiles';
}