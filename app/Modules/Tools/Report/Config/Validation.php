<?php

namespace Report\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleBlock() 
    {
        return [
            'label' => [
                'label' => 'Nom du bloc', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
            'id_file[]' => [
                'label' => 'Fichier associé', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }

    public function rulePublication() 
    {
        return [
            'label' => [
                'label' => 'Nom de la publication', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }

    public function ruleSchema() 
    {
        return [
            'label' => [
                'label' => 'Nom du schéma', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }

    public function ruleTemplate() 
    {
        return [
            'label' => [
                'label' => 'Nom du modèle de rapport', 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }
}