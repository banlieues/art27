<?php

namespace Translator\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleRow() 
    {
        return [
            'label_fr' => [
                'label' => "Label FR", 
                'rules' => 'required',
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
            'ref' => [
                'label' => "Référence", 
                'rules' => "is_unique[$this->t_translator.ref,id_transl,{id_transl}]",
                'errors' => [
                    'required' => "Le champ {field} est requis.",
                ],
            ],
        ];
    }
}