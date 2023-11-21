<?php

namespace Mailing\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleTemplate() 
    {
        return [
            'label' => [
                'label' => 'Label', 
                'rules' => 'required'
            ],
            'ref' => [
                'label' => 'Référence', 
                'rules' => 'required'
            ],
        ];
    }

}