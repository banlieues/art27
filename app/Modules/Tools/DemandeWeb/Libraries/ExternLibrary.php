<?php

namespace DemandeWeb\Libraries;

use Api\Libraries\GravityFormApiLibrary;
use Base\Libraries\BaseLibrary;

class ExternLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function CronMinutes15()
    {
        debug('----------- start module DemandeWeb - cron 15 minutes ---------------');

        $forms = $this->FormsSetParam();
        foreach($forms as $form):
            $this->GravityToCrm($form->id, $form->fields);
        endforeach;

        // $nb_dublons = $this->request_library->deposit_import_dublon_process();
        // debug($nb_dublons . ' doublons détectés');

        debug('----------- end module DemandeWeb - cron 15 minutes ---------------');
    }

    private function FormsSetParam()
    {
        $form_old = (object) [];
        $form_old->id = 12;
        $form_old->fields = [
            'address_city', 
            'address_pc', 
            'address_street', 
            'comment', 
            'contact_email', 
            'contact_lastname', 
            'contact_name', 
            'contact_phone', 
            'id_building_type',
            'id_lang',
            'id_request_type',
            'ids_profile', 
            'subject', 
            'urls_file',
        ];

        $form_new = (object) [];
        $form_new->id = 16;
        $form_new->fields = [
            'address_city', 
            'address_pc', 
            'address_street', 
            'comment', 
            'contact_email', 
            'contact_lastname', 
            'contact_name', 
            'contact_phone', 
            'id_building_type',
            'id_gender',
            'id_lang',
            'ids_profile', 
            'subject', 
            'urls_file',
        ];

        $form_reno = (object) [];
        $form_reno->id = 17;
        $form_reno->fields = [
            'address_city', 
            'address_pc', 
            'address_street', 
            'comment', 
            'contact_email', 
            'contact_lastname', 
            'contact_name', 
            'contact_phone', 
            'id_building_type',
            'id_gender',
            'id_lang',
            'ids_profile', 
            'subject', 
            'urls_file',
        ];

        return [$form_old, $form_new, $form_reno];
    }

    public function GravityToCrm($id_form, $fields_crm)
    {
        $GravityLibrary = new GravityFormApiLibrary();

        $gf_datas = $GravityLibrary->api($id_form, $fields_crm);
        if(empty($gf_datas)) :
            debug('Pas de données pour le formulaire ' . $id_form);
            return false;
        endif;

        
        switch($id_form) :
            case 17 :
                $i = 0;
                foreach($gf_datas as $data) :
                    $gf_datas[$i]->id_demande_origine = 16;
                    $gf_datas[$i]->id_moyen_contact = 14;
                    $i++;
                endforeach;
                break;
            default :
                $i = 0;
                foreach($gf_datas as $data) :
                    $gf_datas[$i]->id_demande_origine = 17;
                    $gf_datas[$i]->id_moyen_contact = 5;
                    $i++;
                endforeach;
        endswitch;

        $imports = $GravityLibrary->ImportDatas($this->t_deposit, $gf_datas);

        debug(count($imports) . ' nouvel(s) import(s) depuis le formulaire ' . $id_form);
        // debug($imports);
    }
}