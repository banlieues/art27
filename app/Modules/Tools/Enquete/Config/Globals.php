<?php

namespace Enquete\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Enquete';
    public $path = APPPATH . 'Modules/Tools/Enquete/';

    public $t_answer = 'en_answer';
    public $t_autorisation = 'user_autorisation';
    public $t_date = 'en_dates';
    // public $t_demande = 'demande';
    // public $t_demande_carac = 'demande_caracteristique';
    // public $t_mail_template = 'mt_template';
    public $t_enquete = 'en_enquete';
    // public $t_cell = 'tesorus_cell';
    public $t_contact = 'contact';
    public $t_profil = 'contact_profil';
    // public $t_bien_contact_demande_profil = 'personne_bien';
    // public $t_question = 'en_question';

    // public $t_user = 'users';

    // public $t_list_accomp_type = 'liste_type_accompagnement';
    // public $t_list_origin = 'liste_origine_contact';
    // public $t_list_demande_type = 'liste_demande_type';
    // public $t_list_lang = 'liste_langue';
    public $t_list_answer_statut = 'en_liste_statut_answer';
    // public $t_list_question_type = 'liste_questions_type';

    // public $emodel_ref = 'demande_close';
}