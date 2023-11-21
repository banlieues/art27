<?php

namespace Custom\Config;

class Liste
{
    public $config = [
        'bien' => [
            'label' => 'Bien',
            'tables' => [
                "liste_type_peb"        => "Certificat PEB",
                "liste_bien_chauffage"  => "Système de chauffage et ECS",
                "liste_type_eau"        => "Type d'eau chaude sanitaire",
                "liste_bien_type"       => "Type de bien",
                "liste_type_chauffage"  => "Type de chauffage",
                "liste_type_cuisiniere" => "Type de cuisinière",
                "liste_type_four"       => "Type de four",
            ],
        ],
        'calculator' => [
            'label' => 'Calculette',
            'tables' => [
                "list_price_origin" => "Source du prix unitaire",
            ],
        ],
        'demande' => [
            'label' => 'Demande',
            'tables' => [
                "liste_intervention_non"            => "Petites interventions",
                "liste_pole"                        => "Pôle",
                "liste_demande_statut"              => "Statut de la demande",
                "liste_travaux"                     => "Travaux prioritaires conseillés",
                "liste_type_accompagnement"         => "Type d'accompagnement spécifique",
                "liste_type_info_conseil"           => "Type d'info conseil ",
                "liste_type_suivi_accompagnement"   => "Type suivi accompagnement ",
            ],
        ],
        'demande_th' => [
            'label' => 'Demande - Thématiques',
            'tables' => [
                "liste_thematique"   => "Thématiques d'entrée et autres thématiques",
            ],
        ],
        'demande_ths' => [
            'label' => 'Demande - Sous-thématiques',
            'tables' => [
                "liste_th_accompagnement"   => "Accompagnement spécifique",
                "liste_th_acoustique"       => "Acoustique",
                "liste_th_energie"          => "Énergie",
                "liste_th_logement"         => "Logement",
                "liste_th_patrimoine"       => "Patrimoine",
                "liste_th_prime"            => "Prime",
                "liste_th_projet_type"      => "Projet type",
                "liste_th_renovation"       => "Rénovation",
                "liste_th_urbanisme"        => "Urbanisme",
                "liste_th_visite"           => "Visite",
            ],
        ],
        'demande_thss' => [
            'label' => 'Demande - Sous-sous-thématiques',
            'tables' => [
                "liste_ths_aide_achat"              => "Aide achat",
                "liste_ths_aide_juridique"          => "Aide juridique",
                "liste_ths_aide_locative"           => "Aide locative",
                "liste_ths_batiment_durable"        => "Batiment durable",
                "liste_ths_energie_renouvable"      => "Energie renouvelable",
                "liste_ths_geste"                   => "Conseils gestes URE donnés",
                "liste_ths_insalubrite"             => "Insalubrité",
                "liste_ths_isolation"               => "Isolation",
                "liste_ths_logement_inoccupe"       => "Logement inoccupé",
                "liste_ths_peb"                     => "PEB",
                "liste_ths_petit_patrimoine"        => "Petit patrimoine",
                "liste_ths_petit_patrimoine_acc"    => "Accompagnement spécifique - Petit patrimoine",
            ],
        ],
        'demandeur' => [
            'label' => 'Demandeur',
            'tables' => [
                "liste_type_appartenance"   => "Appartenance",
                "liste_rel_personne_bien"   => "Profil du demandeur",
                "liste_type_organisme"      => "Type d'organisme",
            ],
        ],
        'document' => [
            'label' => 'Document',
            'tables' => [
                "list_types_depot" => "Type de document",
            ],
        ],
        'encodage' => [
            'label' => 'Encodage',
            'tables' => [
                "liste_demande_origine" => "Origine de la demande",
                "liste_contact_duree" => "Durée du contact",
            ],
        ],
        'rdv' => [
            'label' => 'Rendez-vous',
            'tables' => [
                "liste_rdv_type"    => "Type de rendez-vous",
                "liste_rdv_statut"  => "Statut du rendez-vous",
            ],
        ],
        'tache' => [
            'label' => 'Tâche',
            'tables' => [
                "liste_tache_statut"    => "Statut de tâche",
                "liste_tache_type"      => "Type de tâche",
            ],
        ],
    ];
}