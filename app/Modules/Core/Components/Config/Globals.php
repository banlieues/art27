<?php

namespace Components\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Components';
    public $path = APPPATH . 'Modules/Core/Components/';

    public $t_autorisation = 'user_autorisation';
    public $t_cell = 'tesorus_cell';
    // public $t_user = 'user_accounts';
    public $t_file = 'document_upload';
    // // public $t_attach = 'ma_attachment';

    // public $clientExt = 'contentByte_Type';
    // public $mimeType = 'contentByte_Type';
}