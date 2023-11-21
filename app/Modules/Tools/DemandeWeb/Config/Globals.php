<?php

namespace DemandeWeb\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'DemandeWeb';
    public $path = APPPATH . 'Modules/Tools/DemandeWeb/';

    public $t_bien_demande = 'personne_bien';
    public $t_bien = 'bien';
    public $t_building = 'bien';
    public $t_deposit = 're_deposit';
    public $t_file = 'document_upload';
    public $t_demande_file = 'document_upload_lien';
    // public $t_demande = 'demande';
    // public $t_email = 'email_outlook';
    // public $t_demande_email = 'email_outlook_lien';
    // public $t_file = 'document_upload';
    // public $t_bien_contact_demande_profil = 'personne_bien';
    // public $t_user = 'users';

    // public $t_list_lang = 'liste_langue';
}