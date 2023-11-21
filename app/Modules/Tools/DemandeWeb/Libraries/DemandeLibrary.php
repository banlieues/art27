<?php

namespace DemandeWeb\Libraries;

use Api\Libraries\GravityFormApiLibrary;
use Base\Libraries\BaseLibrary;
use Base\Libraries\InitLibrary;
use Components\Libraries\ListLibrary;
use Components\Libraries\FileLibrary;
use DemandeWeb\Models\DemandeModel;

class DemandeLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();

        $InitLibrary = new InitLibrary();
        $globals = $InitLibrary->GetGlobals(__NAMESPACE__);
        foreach($globals as $key=>$value) $this->$key = $value;

        $this->FileLibrary = new FileLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
        $this->DWModel = new DemandeModel();
        $this->gravity = new GravityFormApiLibrary();
    }

    public function get_info($id_deposit)
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/deposit/form.json'));        
        $posts = $this->DWModel->DepositGet($id_deposit);

        $datas = (object) [];

        foreach($posts as $ref=>$post) :           
            $title = !empty($fields->$ref) ? $fields->$ref->label_fr : $ref;
            if(!empty($post)) :
                $object = (object) [];
                $lists = $this->ListLibrary->lists;
                if(!empty($lists->$ref)) :
                    $object = $this->ListLibrary->get_selected_object($post, $ref);
                else :
                    $object->value = $post;
                    $object->label = $post;
                endif;
                $object->title = $title;
                $datas->$ref = $object;     
            endif;
        endforeach;

        return $datas;
    }



    // public function deposit_import_doublon_process()
    // {
    //     $deposits = $this->DWModel->DepositsGet();
        
    //     $n = 0;
    //     foreach($deposits as $deposit) :
    //         $doublons = $this->DWModel->DemandeGetIdenticalDoublon($deposit);
    //         if(!empty($deposit->urls_file)) :
    //             foreach($doublons as $doublon) :
    //                 $this->DemandeImportInsertFile($doublon->id_demande, $deposit->urls_file);
    //             endforeach;
    //         endif;
    //         if(!empty($doublons)) :
    //             $this->DWModel->DepositDelete($deposit->id_deposit);
    //             $n++;
    //         endif;
    //     endforeach;

    //     return $n;
    // }

    public function ContactProfilGetDublon($deposit, $id_building=null)
    {
        $profils = $this->DWModel->ContactProfilGetDublon($deposit, $id_building) ?? [];

        $deposit->id_contact_profil = null;
        $deposit->id_contact = null;
        array_unshift($profils, $deposit);

        return $profils;

        // $profil_new = [$deposit];
        // $doublons = $this->DWModel->ContactProfilGetDublon($deposit, $id_building);
        
        // if(!empty($doublons)) $datas = array_merge($profil_new, $doublons);
        // else $datas = $profil_new;

        // return $datas;
    }

    public function BuildingGetDublon($deposit, $id_contact=null, $id_contact_profil=null)
    {
        $buildings = $this->DWModel->BuildingGetDublon($deposit, $id_contact, $id_contact_profil) ?? [];

        $deposit->id_building = null;
        array_unshift($buildings, $deposit);

        return $buildings;

        // $datas = [];
        // $deposit->id_building = null;
        // $building_new = [$deposit];
        // $doublons = $this->DWModel->BuildingGetDublon($deposit, $id_contact_profil);
        // if(!empty($deposit->address_street)) :
        //     if(!empty($doublons)) $datas = array_merge($building_new, $doublons);
        //     else $datas = $building_new;
        // else :
        //     if(!empty($doublons)) $datas = $doublons;
        // endif;

        // return $datas;
    }

    public function DemandeGetDublon($deposit, $id_contact=null, $id_contact_profil=null, $id_building=null)
    {
        $demandes = $this->DWModel->DemandeGetDublon($deposit, $id_contact, $id_contact_profil, $id_building) ?? [];

        $deposit->id_demande = null;
        array_unshift($demandes, $deposit);

        return $demandes;

        // $deposit->id_demande = null;
        // $demande_new = [$deposit];

        // if(!empty($id_contact_profil) || !empty($id_building)) :
        //     $doublons = $this->DWModel->DemandeGetDublon($deposit, $id_contact_profil, $id_building);

        //     if(!empty($doublons)) $datas = array_merge($demande_new, $doublons);
        //     else $datas = $demande_new;
        // else :
        //     $datas = $demande_new;
        // endif;

        // return $datas;
    }
}