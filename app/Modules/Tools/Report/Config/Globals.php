<?php

namespace Report\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Report';
    public $module_short  = 'rp';
    public $path  = APPPATH . 'Modules/Tools/Report/';

    public $t_block = 'rp_block';
    // public $t_file = 'rp_file';
    public $t_person = 'personne';
    public $t_person_building_request = 'personne_bien';
    public $t_report = 'rp_report';
    public $t_report_block = 'rp_report_block';
    public $t_request = 'demande';
    public $t_tag = 'rp_tag';
    public $t_user = 'users';
}