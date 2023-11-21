<?php

namespace Administrator\Config;

class Validation
{     
    public function __construct()
    {
        $globals = new \Custom\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $globals_module = new \Administrator\Config\Globals();
        foreach($globals_module as $global_module=>$value) $this->$global_module = $value;

    }

    public function ruleUserAdd() 
    {
        return [
            'username' => [
                'rules' => 'required|is_unique[user_accounts.username]',
                'errors' => [
                    'required' => "Le nom d'utilisateur est obligatoire.",
                    'is_unique' => "Ce nom d'utilisateur est déjà utilisé."
                ]
            ],
            'nom' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Le nom est obligatoire.",
                ]
            ],
            'prenom' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Le prénom est obligatoire.",
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[user_accounts.email]',
                'errors' => [
                    'required' => "Email est obligatoire.",
                    'valid_email' => "Cet email est invalide.",
                    'is_unique' => "Cet email est déjà utilisé."
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Le mot de passe obligatoire.',
                    'min_length' => 'Le mot de passe doit avoir 5 caractères.',
                    'max_length' => 'Le mot de passe doit avoir un maximum of 12 caractères.',
                    'regex_match' => "Le mot de passe ne peut avoir d'espace.",
                ]
            ],
            'confirm' => [
                'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Le mot de passe de confirmation est obligatoire.',
                    'min_length' => 'Le mot de passe de confirmation doit avoir 5 caractères.',
                    'max_length' => 'Le mot de passe de confirmation doit avoir un maximum of 12 caractères.',
                    'matches' => 'Les deux mots de passe sont différents.',
                    'regex_match' => "Le mot de passe de confrimation ne peut pas avoir d'espace.",
                ]
            ],
            'is_actif' => [
                'rules' => 'required|numeric|min_length[1]|max_length[1]',
                'errors' => [
                    'required' => 'Actived is required.',
                    'numeric' => 'Actived must be a numeric value.',
                    'min_length' => 'Actived must have a minimum of 1 characters.',
                    'max_length' => 'Actived must have a maximum of 1 characters.'
                ]
            ],
            'role_id' => [
                'rules' => 'required|numeric|min_length[1]|max_length[1]',
                'errors' => [
                    'required' => 'Role Id is required.',
                    'numeric' => 'Role Id must be a numeric value.',
                    'min_length' => 'Role Id must have a minimum of 1 characters.',
                    'max_length' => 'Role Id must have a maximum of 1 characters.'
                ]
            ],
        ];
    }

    public function ruleLogin() 
    {
        return [
            'username' => [
                'label' => "Identifiant",
                'rules' => 'required|is_not_unique[user_accounts.username]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => "Le nom d'utilisateur est requis.",
                    // 'is_not_unique' => 'Username not exist ...',
                    // 'regex_match' => 'Username must not have space ...',
                    'is_not_unique' => "Le nom d'utilisateur n'existe pas.",
                    'regex_match' => "Le nom d'utilisateur ne peut pas contenir d'espace.",
                ],
            ],
            // 'password' => [
            //     'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
            //     'errors' => [
            //         'required' => 'Password is required ...',
            //         // 'min_length' => 'Password must have a minimum of 5 characters ...',
            //         // 'max_length' => 'Password must have a maximum of 12 characters ...',
            //         // 'regex_match' => 'Password must not have space ...',
            //         'min_length' => 'Password error ...',
            //         'max_length' => 'Password error ...',
            //         'regex_match' => 'Password error ...',
            //     ]
            // ],
        ];
    }

    public function rulePassword() 
    {
        return [
            'new_password' => [
                'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Vous devez entrer un mot de passe ...',
                    'min_length' => 'Le mot de passe doit avoir au moins 5 caractères ...',
                    'max_length' => 'Le mot de passe ne doit pas avoir plus de 12 caractères ...',
                    'regex_match' => "Le mot de passe ne peut avoir d'espace ...",
                ]
            ],
            'confirm_password' => [
                'rules' => 'matches[new_password]',
                'errors' => [
                    'matches' => 'Les mots de passe ne correspondent pas ...',
                ]
            ],
        ];
    }

    public function ruleRegistration() 
    {
        return [
            'username' => [
                'rules' => 'required|is_unique[user_accounts.username]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Username is required ...',
                    'is_unique' => 'This username is already used ...',
                    'regex_match' => 'Username must not have space ...',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[user_accounts.email]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Email is required ...',
                    'valid_email' => 'This email is invalid ...',
                    'is_unique' => 'This email is already used ...',
                    'regex_match' => 'Email must not have space ...',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Password is required ...',
                    'min_length' => 'Password must have a minimum of 5 characters ...',
                    'max_length' => 'Password must have a maximum of 12 characters ...',
                    'regex_match' => 'Password must not have space ...',
                ]
            ],
            'confirm' => [
                'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Confirm password is required ...',
                    'min_length' => 'Confirm password must have a minimum of 5 characters ...',
                    'max_length' => 'Confirm password must have a maximum of 12 characters ...',
                    'matches' => 'Confirm password not match to password ...',
                    'regex_match' => 'Password must not have space ...',
                ]
            ],
        ];
    }

    public function ruleEmail() 
    {
        return [
            'id' => 'is_natural_no_zero',
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[user_accounts.email,id,{id}]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => "Le champ {field} est requis",
                    'valid_email' => "Le champ {field} n'est pas valide.",
                    'is_unique' => "Le champ {field} doit être unique.",
                    'regex_match' => "Le champ {field} ne doit pas comporter d'espace.",
                ]
            ],
        ];
    }

    public function rulePasswordReset() 
    {
        return [
            'password' => [
                'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Le mot de passe est obligatoire',
                    'min_length' => 'Le mot de passe  doit avoir un minimum de 5 caractères ...',
                    'max_length' => 'Le mot de passe  doit avoir un maximun de 12 caractères ...',
                    'regex_match' => 'Le mot de passe ne doit pas avoir d\'espace vide...',
                ]
            ],
            'confirm' => [
                'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Le mot de passe de confirmation est obligatoire',
                    'min_length' => 'Le mot de passe de confirmation doit avoir un minimum de 5 caractères...',
                    'max_length' => 'Le mot de passe de confirmation  doit avoir un maximun de 12 caractères...',
                    'matches' => 'Les deux mots de passe ne correspondent pas...',
                    'regex_match' => 'Le mot de passe ne doit pas avoir d\'espace vide...',
                ]
            ],
        ];
    }
}
