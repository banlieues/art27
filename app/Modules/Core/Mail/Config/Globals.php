<?php

namespace Mail\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Mail';
    public $path  = APPPATH . 'Modules/Core/Mail/';

    public $t_email = 'email_outlook';
    public $t_list_lang = 'liste_langue';
    public $t_reminder = 'reminder';
    public $t_demande_email = 'email_outlook_lien';
    public $t_mt_template = 'mt_template';
    public $t_user = 'user_accounts';

    // public $t_attach = 'ma_attachment';
    // public $t_attach_ev = 'ev_attach';
    // public $t_email = 'email';
    // public $t_user = 'user_accounts';

    // public $attach_route = WRITEPATH . 'uploads/attachment';
}