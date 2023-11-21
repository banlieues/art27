<?php

namespace DemandeWeb\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleTemplate() 
    {
        // return [
        //     'label' => [
        //         'label' => 'Label', 
        //         'rules' => 'required',
        //         'errors' => [
        //             'required' => "Le champ {field} est requis.",
        //         ],
        //     ],
        //     'ref' => [
        //         'label' => 'Référence', 
        //         'rules' => 'required',
        //         'errors' => [
        //             'required' => "Le champ {field} est requis.",
        //         ],
        //     ],
        // ];
    }

}