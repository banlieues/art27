<?php

namespace Company\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleCompany() 
    {
        return [
            'label' => [
                'label' => "Dénomination de l'entreprise", 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
            'address_pc' => [
                'label' => "Code postal", 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }
}