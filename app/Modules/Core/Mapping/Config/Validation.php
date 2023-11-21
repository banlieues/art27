<?php

namespace Mapping\Config;

class Validation
{
    public function __construct()
    {
        $globals = new \Custom\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $globals_module = new \Mapping\Config\Globals();
        foreach($globals_module as $global_module=>$value) $this->$global_module = $value;
    }

    public function ruleTerritory() 
    {
        return [
            // 'location_type' => [
            //     'label' => 'Type de lieu', 
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => 'Le type de lieu est requis.',
            //     ],
            // ],
            'radius' => [
                'label' => 'Rayon', 
                'rules' => 'required|greater_than[0]',
                'errors' => [
                    'required' => 'Le rayon doit être encodé.',
                    'greater_than' => 'Le rayon doit être un nombre entier supérieur à 0.',
                ],
            ],
        ];
    }

}