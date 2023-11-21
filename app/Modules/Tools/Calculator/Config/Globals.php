<?php

namespace Calculator\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Calculator';
    public $path = APPPATH . 'Modules/Tools/Calculator/';

    public $t_autorisation = 'user_autorisation';
    public $t_bien = 'bien';
    public $t_bien_contact = 'personne_bien';
    public $t_cell = 'tesorus_cell';
    public $t_calculator_demande = 'tesorus_road_calculator_demande';
    public $t_contact = 'contact';
    public $t_demande = 'demande';
    public $t_demande_rdv = 'demande_rdv';
    public $t_devis = 'tesorus_road_calculator_devis';
    public $t_group_work = 'tesorus_road_calculator_group_work';
    public $t_profil = 'contact_profil';
    public $t_work = 'tesorus_road_calculator_work';
    // public $t_devis_room = 'tesorus_road_calculator_devis_room';
    public $t_estimation = 'tesorus_road_calculator_estimation';
    public $t_group = 'tesorus_road_calculator_group';
    // public $t_group_room = 'tesorus_road_calculator_group_room';
    public $t_price = 'tesorus_road_calculator_price';
    public $t_rdv = 'rdv';
    public $t_road = 'tesorus_road_calculator';
    public $t_user = 'user_accounts';

    public $t_l_bien_type = 'liste_bien_type';
    public $t_l_bien_contact_type = 'liste_rel_personne_bien';
    public $t_l_demande_statut = 'liste_demande_statut';
    public $t_l_demande_type = 'liste_demande_type';
    public $t_l_price_origin = 'list_price_origin';
}