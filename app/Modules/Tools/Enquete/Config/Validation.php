<?php

namespace Enquete\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleQuestion() 
    {
        return [
            'name_question' => [
                'label' => 'Référence', 
                'rules' => 'required|is_unique[' . $this->t_question . '.name_question,name_question,{name_question}]',
                'errors' => [
                    'required' => 'Le champ {field} est requis.',
                    'is_unique' => 'La référence existe déjà. Veuillez la modifier.',
                ],
    
            ],
            'type_question' => [
                'label' => 'Type de question', 
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Le champ {field} est requis.',
                    'integer' => 'La valeur du type de question doit être un nombre entier',
                ],
    
            ],
        ];
    }
}
