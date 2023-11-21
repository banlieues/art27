<?php

namespace Calculator\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleGroup() 
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
            // 'period_month_calcul' => [
            //     'label' => 'Période de calcul', 
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => "Le champ {field} est requis.",
            //     ],
            // ],
            // 'measure' => [
            //     'label' => 'Unité de mesure', 
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => "Le champ {field} est requis.",
            //     ],
            // ],
        ];
    }

    public function rulePrice() 
    {
        return [
            'unit_price' => [
                'label' => 'Prix unitaire', 
                'rules' => 'required|greater_than_equal_to[0]',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
            'date_devis' => [
                'label' => 'Date du devis', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
            'price_origin' => [
                'label' => 'Source du prix', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }

    public function ruleWork() 
    {
        return [
            'label' => [
                'label' => 'Nom de l\'ouvrage', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }
}