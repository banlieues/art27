<?php

namespace Tesorus\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleRoad()
    {
        return [
            'label_fr' => [
                'label' => 'Label', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }
}