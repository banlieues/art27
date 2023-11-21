<?php

namespace Translator\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Translator';
    public $path  = APPPATH . 'Modules/Core/Translator/';

    public $t_translator = 'translator';
}