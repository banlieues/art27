<?php

namespace Mailing\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Mailing';
    public $path = APPPATH . 'Modules/Tools/Mailing/';

    public $t_email = 'email_outlook';
    // public $t_list_lang = 'liste_langue';
    // public $t_reminder = 'reminder';
    // public $t_demande_email = 'email_outlook_lien';
    public $t_template = 'mt_template';
    public $t_user = 'user_accounts';
    public $t_variable = 'em_variable';
}