<?php

namespace Company\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Company';
    public $path = APPPATH . 'Modules/Tools/Company/';

    public $t_autorisation = 'user_autorisation';
    public $t_company = 'co_company';
    public $t_deposit = 'co_deposit';
    public $t_file = 'document_upload';
    // public $t_fe_translation = 'fe_translation';
    // // public $t_file = 'co_file';
    public $t_user = 'user_accounts';

    public $t_list_company_status = 'co_list_status';
    public $t_list_contact_type = 'co_list_contact_type';
    public $t_list_contact_schedule = 'co_list_contact_schedule';
    public $t_list_juridic_form = 'co_list_juridic_form';
    public $t_list_lang = 'liste_langue';
}