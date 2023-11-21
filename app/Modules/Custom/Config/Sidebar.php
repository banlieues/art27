<?php

namespace Custom\Config;

class Sidebar
{
    public $name = 'Test avec SAAL';
    public $logo = 'images/logos/logo-long.png';

    /**
     * @var array
     */   
    public $menu = [
        [
            'label' => 'Menu administrateur',
            'ref' => 'menu',
            'autorisation' => 'administrator',
            'nocollapse' => 1,
            'children' => [
                [
                    'label' => 'Tableau de bord',
                    'ref' => 'dashboard',
                    'autorisation' => 'administrator',
                    'href' => 'dashboard_default',
                ]
            ],
        ],
       
        [
            'label' => 'Gestion',
            'ref' => 'management',
            'autorisation' => 'member',
            'children' => [
                [
                    'label' => 'Partenaires sociaux',
                    'ref' => 'partenaire_social',
                    'access' => 'member',
                    'href' => 'partenaire_social',
              
                ],
            
                [
                    'label' => 'Partenaires culturels',
                    'ref' => 'partenaire_culturel',
                    'access' => 'member',
                    'href' => 'partenaire_culturel',
              
                ],
                [
                    'label' => 'Tickets',
                    'ref' => 'barre_code',
                    'access' => 'member',
                    'href' => 'ticket',
                  
                //     'color' => 'orange',
                // ],
                ],
              /*  [
                    'label' => 'Demandes',
                    'ref' => 'demande',
                    'autorisation' => 'member',
                    'href' => 'demande',
                ],*/
            
               /* [
                    'label' => 'Contacts',
                    'ref' => 'contact',
                    'autorisation' => 'member',
                    'href' => 'contact',
                ],*/
             /*   [
                    'label' => 'Biens',
                    'ref' => 'bien',
                    'autorisation' => 'member',
                    'href' => 'bien',
                   
                ],*/
               /* [
                    'label' => 'Rendez-vous',
                    'ref' => 'rdv',
                    'autorisation' => 'member',
                    'href' => 'rdv',
                ],*/
              /* [
                    'label' => 'Tâche',
                    'ref' => 'tache',
                    'autorisation' => 'member',
                    'href' => 'tache',
                ],*/
              /*  [
                    'label' => 'Documents',
                    'ref' => 'document',
                    'autorisation' => 'member',
                    'href' => 'document',
                ],*/
            ],
        ], 
       /* [
            'label' => 'Nouvelle demande',
            'ref'   =>"insertion_data",
            'autorisation' =>"member",
            'children'=>
            [
                
                 [
                     'label' => 'Dépot Web',
                     'ref' => 'demande_web',
                     'autorisation' => 'demande_web_c',
                   'href' => 'demande/web',
                ],
                [
                    'label' => 'Téléphone',
                    'ref' => 'telephone',
                    'autorisation' => 'administrator',
                    'href' => 'demande/new/telephone',
                ],
                [
                    'label' => 'Guichet',
                    'ref' => 'guichet',
                    'autorisation' => 'administrator',
                    'href' => 'demande/new/guichet',
                ],
                [
                    'label' => 'Stand',
                    'ref' => 'stand',
                    'autorisation' => 'administrator',
                    'href' => 'demande/new/stand',
                ],
                [
                    'label' => 'Bureau',
                    'ref' => 'bureau',
                    'autorisation' => 'administrator',
                    'href' => 'demande/new/bureau',
                ],
               [
                    'label' => 'Outlook',
                    'ref' => 'outlook',
                    'autorisation' => 'administrator',
                    'href' => 'outlook/liste_message/1',
                ],
                
            ],
        ],    */   
        [
            'label' => 'Outils',
            'ref' => 'tools',
            'autorisation' => 'member',
            'children' => [
                [
                    'label' => 'Modélisation',
                    'ref' => 'modelisation',
                    'access' => 'member',
                    'href' => 'modelisation',
                    
                ],
                // [
                //     'label' => 'Calculateur',
                //     'ref' => 'calculator',
                //     'autorisation' => 'administrator',
                //     'href' => 'calculator',
                // ],
             /*   [
                    'label' => 'Calculette',
                    'ref' => 'calculator',
                    'autorisation' => 'calculator_r',
                    'href' => 'calculator',
                    'children' => [
                        [
                            'label' => 'Postes',
                            'ref' => 'road',
                            'autorisation' => 'calculator_u',
                            'href' => 'calculator/roads',
                        ],
                        [
                            'label' => 'Groupes de travaux',
                            'ref' => 'group',
                            'autorisation' => 'calculator_u',
                            'href' => 'calculator/groups',
                        ],
                        [
                            'label' => 'Thématiques',
                            'ref' => 'tesorus',
                            'autorisation' => 'calculator_u',
                            'href' => 'calculator/tesorus',
                        ],
                        [
                            'label' => 'Demandes',
                            'ref' => 'devis',
                            'autorisation' => 'calculator_u',
                            'href' => 'calculator/devis',
                        ],
                    ],
                ],*/
             /*   [                    
                    'label' => 'Enquêtes',
                    'ref' => 'enquete',
                    'autorisation' => 'enquete_r',
                    'href' => 'enquete',
                    'children' => [
                        [
                            'label' => 'Tableau des envois',
                            'ref' => 'send',
                            'autorisation' => 'enquete_r',
                            'href' => 'enquete/sends',
                        ],
                        [
                            'label' => 'Récapitulatif des réponses',
                            'ref' => 'answer',
                            'autorisation' => 'enquete_r',
                            'href' => 'enquete/answers',
                        ],
                        [
                            'label' => 'Graphes',
                            'ref' => 'chart',
                            'autorisation' => 'enquete_r',
                            'href' => 'enquete/charts',
                        ],
                        [
                            'label' => 'Tendances',
                            'ref' => 'trend',
                            'autorisation' => 'enquete_r',
                            'href' => 'enquete/trends',
                        ],
                        [
                            'label' => 'Formulaires',
                            'ref' => 'enquete',
                            'autorisation' => 'enquete_form_u',
                            'href' => 'enquete/enquetes',
                        ],
                        [
                            'label' => 'Questions',
                            'ref' => 'question',
                            'autorisation' => 'enquete_form_u',
                            'href' => 'enquete/questions',
                        ],
                    ],
                ],*/
              /*  [
                    'label' => 'Liste des listes',
                    'ref' => 'liste',
                    'autorisation' => 'liste_r',
                    'href' => 'liste',
                    
                ],*/
              /*  [
                    'label' => 'Ready to Renov',
                    'ref' => 'company',
                    'autorisation' => 'company_u',
                    'href' => 'company',
                    'children' => [
                        [
                            'label' => 'Dépôt',
                            'ref' => 'deposit',
                            'autorisation' => 'company_c',
                            'href' => 'company/deposits',
                        ],
                        [
                            'label' => 'Liste des entreprises',
                            'ref' => 'company',
                            'autorisation' => 'company_u',
                            'href' => 'company/companies',
                        ],
                    ],
                ],*/
              /*  [
                    'label' => 'Thesaurus',
                    'ref' => 'tesorus',
                    'autorisation' => 'tesorus_u',
                    'href' => 'tesorus',
                    'children' => [
                        [
                            'label' => 'Liste des chemins',
                            'ref' => 'road',
                            'autorisation' => 'tesorus_u',
                            'href' => 'tesorus/roads',
                        ],
                        [
                            'label' => 'Liste des cellules',
                            'ref' => 'cell',
                            'autorisation' => 'tesorus_u',
                            'href' => 'tesorus/cells',
                        ],
                    ],
                ],*/
               [
                    'label' => 'Modèle de documents',
                    'ref' => 'report',
                    'autorisation' => 'administrator',
                    'href' => 'autorisation/no_autorisation',
                    'children' => [
                        [
                            'label' => 'Schéma',
                            'ref' => 'schema',
                            'autorisation' => 'administrator',
                            'href' => 'autorisation/no_autorisation',
                        ],
                        [
                            'label' => 'Modèle',
                            'ref' => 'template',
                            'autorisation' => 'administrator',
                            'href' => 'autorisation/no_autorisation',
                        ],
                        [
                            'label' => 'Publication',
                            'ref' => 'publication',
                            'autorisation' => 'administrator',
                            'href' => 'autorisation/no_autorisation',
                        ],
                    ],
                ],

                [
                    'label' => 'Formulaires',
                    'ref' => 'enquete',
                    'autorisation' => 'partenaire_social',
                    'href' => 'autorisation/no_autorisation',
                ],
              /*  [
                    'label' => "Modèle d'email",
                    'ref' => 'mailing',
                    'autorisation' => 'email_template_u',
                    'href' => 'mailing/templates',
                    'children' => [
                        // [
                        //     'label' => 'Nouveau',
                        //     'ref' => 'mailing',
                        //     'autorisation' => 'administrator',
                        //     'href' => 'mailing/email',
                        // ],
                        // [
                        //     'label' => 'Liste des emails',
                        //     'ref' => 'email',
                        //     'autorisation' => 'administrator',
                        //     'href' => 'mailing/emails',
                        // ],
                        [
                            'label' => 'Liste des modèles',
                            'ref' => 'template',
                            'autorisation' => 'email_template_u',
                            'href' => 'mailing/templates',
                        ],
                        // [
                        //     'label' => 'Variables',
                        //     'ref' => 'variable',
                        //     'autorisation' => 'administrator',
                        //     'href' => 'mailing/variables',
                        // ],
                    ],
                ],*/
                // [
                //     'label' => 'Modèle d\'emails',
                //     'ref' => 'mailtemplate',
                //     'autorisation' => 'administrator',
                //     'href' => 'mailtemplate',
                //     'children' => [
                //         [
                //             'label' => 'Modèles',
                //             'ref' => 'template',
                //             'autorisation' => 'administrator',
                //             'href' => 'mailtemplate/templates',
                //         ],
                //         [
                //             'label' => 'Variables',
                //             'ref' => 'variable',
                //             'autorisation' => 'administrator',
                //             'href' => 'mailtemplate/variables',
                //         ],
                //     ],
                // ],
                [
                    'label' => 'Requête',
                    'ref' => 'queries',
                    'autorisation' => 'rrequete',
                    'href' => 'queries',
                    'children' => [
                        [
                            'label' => 'Nouvelle requête',
                            'ref' => 'queries_last',
                            'autorisation' => 'member',
                            'href' => 'queries/index',
                        ],
                        [
                            'label' => 'Requête en cours',
                            'ref' => 'queries_last',
                            'autorisation' => 'member',
                            'session' => 'urlLastQuery',
                        ],
                        [
                            'label' => 'Liste des requêtes',
                            'ref' => 'queries_list',
                            'autorisation' => 'member',
                            'href' => 'queries/list',
                        ],        
                    ],
                ],
             /*   [
                    'label' => 'Translator',
                    'ref' => 'translator',
                    'autorisation' => 'translator_u',
                    'href' => 'translator',
                ],*/
                // [
                //     'label' => 'Modélisation',
                //     'ref' => 'modelisation',
                //     'autorisation' => 'member',
                //     'href' => 'modelisation',
                //     'icon' => 'tools',
                //     'color' => 'blue',
                // ],
                // [
                //     'label' => 'Modèle de documents',
                //     'ref' => 'documentstemplates',
                //     'autorisation' => 'member',
                //     'href' => 'documentstemplates',
                //     'icon' => 'file',
                //     'color' => 'office',
                // ],
                // [
                //     'label' => 'Doublons',
                //     'ref' => 'doublons',
                //     'autorisation' => 'member',
                //     'href' => 'doublons',
                // ],
                 [
                    'label' => 'Importation',
                     'ref' => 'import',
                     'autorisation' => 'member',
                     'href' => 'import',
                 ],
                // [
                //     'label' => 'Papopi',
                //     'ref' => 'papopi',
                //     'autorisation' => 'member',
                //     'href' => 'mashmash',
                //     'icon' => 'file-import',
                //     'color' => 'purple',
                // ],
            ],

        ],
      /*  [
            'label' => 'Help',
            'ref' => 'help',
            'autorisation' => 'administrator',
            'children' => [
                [
                    'label' => 'Mail',
                    'ref' => 'mail',
                    'autorisation' => 'administrator',
                    'href' => 'mail',
                ],
            ],
        ],*/
    ];
}