<?php

namespace DemandeWeb\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\FileLibrary;
use Components\Libraries\ListLibrary;
use DemandeWeb\Libraries\DemandeLibrary;
use DemandeWeb\Models\DemandeModel;
use Mailing\Libraries\MailingLibrary;

class Demande extends BaseController 
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        // if(!$this->fh_dao->get_autorisation("winterface2"))
        // die("Vous n'avez pas l'autorisation de voir le dépot Web");

        // $this->mysql = new MysqlLibrary();
        // $this->mysql->check_database();

        $this->DWModel = new DemandeModel();
        $this->DWLibrary = new DemandeLibrary();
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);

        $this->datas->context = 'demande_web';
    }
    
    // public function BuildingsProfilsGetByDemande($id_demande)
    // {
    //     $demandes = $this->db->table($this->t_bien_contact_demande_profil)->where('id_demande', $id_demande)->get()->getResult();
    //     $data = (object) [];
    //     foreach($demandes as $demande) :
    //         if(!empty($demande->id_contact)) $data->ids_contact[] = $demande->id_contact;
    //         if(!empty($demande->id_contact_profil)) $data->ids_contact_profil[] = $demande->id_contact_profil;
    //         if(!empty($demande->id_bien)) $data->ids_building[] = $demande->id_bien;
    //     endforeach;

    //     echo json_encode($data);
    // }

    public function rule()
    {
        $columns = json_decode(file_get_contents($this->path . 'Config/Json/deposit/table.json'));
        $column_list = array_keys((array) $columns); 
        
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/deposit/form.json'));

        $post_fields = (object) [];
        $post_lists = (object) [];
        foreach($column_list as $col) :
            if(!empty($fields->$col)) $post_fields->$col = $fields->$col;
            $list = $this->ListLibrary->get_list_by_ref($col);
            if(!empty($list)) $post_lists->$col = $list;
        endforeach;

        $this->datas->id_form = 12;
        $this->datas->fields = $post_fields;
        $this->datas->lists = $post_lists;

        return view($this->module . '\rule', (array) $this->datas);
    }

    public function DemandeImport()
    {
        $post = database_decode($this->request->getPost());
        // debugd($post);

        foreach($post as $ref=>$value) if($value=='on') unset($post->$ref);
        $deposit = $this->DWModel->DepositGet($post->id_deposit);

        // update a contact profil
        if(!empty($post->id_contact_profil)) :
            $this->ContactProfilUpdate($post);
        endif;
        if(!empty($post->id_building)) :
            unset($deposit->address_fr);
            unset($deposit->address_nl);
        endif;
        if(!empty($post->id_demande)) :
            // create email and link with demande
            $this->db->transStart();
            $this->DemandeImportExisting($deposit, $post);
            if($this->db->transComplete() == FALSE) :
                $messagetype = 'warning';
                $message = "Erreur :  La demande n'a pas pu être ajoutée comme email dans la demande existante.";
            else :
                $messagetype = 'success';
                $message = "La demande a bien été ajoutée comme email dans la demande existante.";
            endif;

            return redirect()->to(base_url('demande/web/deposit'))->with($messagetype, $message);
        else :
            // $profil = $this->DWModel->ContactProfilGet($id_contact_profil);
            // if(!empty($id_building)) $building = $this->DWModel->BuildingGet($id_building);

            $this->DemandeImportNewRedirect($deposit, $post);
            return redirect()->to(base_url('demande/new_web'));
        endif;
    }
    
    private function DemandeImportExisting($deposit, $post)
    {
        if(!empty($deposit->urls_file)) :
            $FileLibrary = new FileLibrary();
            foreach($deposit->urls_file as $url) :
                $id_file = $FileLibrary->FileUploadFromUrl($url);
                $FileLibrary->FileLinkDemandeInsert($id_file, $post->id_demande);
            endforeach;
        endif;

        if(empty($post->id_building) && !empty($post->address_fr) && !empty($post->address_nl)) :
            $this->DWModel->BuildingAddByDemande($post);
        endif;
        $this->DWModel->DemandeImportEmailInsert($deposit, $post->id_demande);
        $this->DWModel->DepositDelete($deposit->id_deposit);

        $MailingLibrary = new MailingLibrary();
        $MailingLibrary->EmailSendByTemplate('update_assign', (object) ['id_demande' => $post->id_demande]);
    }
    
    private function DemandeImportNewRedirect($deposit, $post)
    {
        $deposit->id_contact_profil = !empty($post->id_contact_profil) ? $post->id_contact_profil : 0;
        $deposit->id_contact = !empty($post->id_contact) ? $post->id_contact : 0;
        $deposit->id_bien = !empty($post->id_building) ? $post->id_building : 0;
        
        if($deposit->id_request_type==1) $deposit->id_type_demande = 1;
        elseif($deposit->id_request_type==2) $deposit->id_type_demande = 3;

        if(!empty($deposit->subject)) $deposit->subject = "Homegrade.brussels : $deposit->subject";
        if(!empty($deposit->ids_profile)) $deposit->rel_personne_bien = $deposit->ids_profile;

        // $data->infos = $this->DWLibrary->get_info($deposit->id_deposit);

        $this->session->remove('importDemande');
        $this->session->set('importDemande', $deposit);
    }

    // private function BuildingUpdate($deposit, $post)
    // {
    //     $data = (object) [];
    //     if(!empty($post->id_building)) :
    //         if(!empty($post->id_building_type)) $data->id_type = $post->id_building_type;
    //         if(!empty($post->address_fr)) $data->adresse_fr = $post->address_fr;
    //         if(!empty($post->address_nl)) $data->adresse_nl = $post->address_nl;          
    //         if(!empty($post->address_box)) $data->bt = $post->address_box;        
    //         if(!empty($post->address_floor)) $data->etage_logement = $post->address_floor;
    //         if(!empty($deposit->address_pc)) $data->adresse_fr_cp = $deposit->address_pc;       
    //         if(!empty((array) $data)) : 
    //             $this->DWModel->BuildingSave($data, $post->id_building); 
    //         endif;
    //         $id_building = $post->id_building;
    //     elseif(!empty($deposit->address_street)) :
    //         $data->id_type = $deposit->id_building_type;
    //         if(isset($deposit->address_fr)) $data->adresse_fr = $deposit->address_fr;
    //         if(isset($deposit->address_nl)) $data->adresse_nl = $deposit->address_nl;
    //         if(isset($deposit->address_pc)) $data->adresse_fr_cp = $deposit->address_pc;
    //         if(isset($deposit->address_box)) $data->bt = $deposit->address_box;        
    //         if(isset($deposit->address_floor)) $data->etage_logement = $deposit->address_floor;       
    //         if(!empty((array) $data)) : 
    //             $id_building = $this->DWModel->BuildingSave($data); 
    //         endif;
    //     else :
    //         $id_building = null;
    //     endif;
        
    //     return $id_building;
    // }

    private function ContactProfilUpdate($post)
    {
        $data = (object) [];

        if(!empty($post->contact_name)) $data->prenom_contact = $post->contact_name;
        if(!empty($post->contact_lastname)) $data->nom_contact = $post->contact_lastname;

        $contact = $this->DWModel->ContactProfilGet($post->id_contact_profil);
        if(!empty($post->contact_email)) :
            $data->email = $post->contact_email;
            if(!empty($post->contact_email2)) $data->email2 = $post->contact_email2;
            if(!empty($contact->contact_email2)) $data->email3 = $contact->contact_email2;
        endif;
        if(!empty($post->contact_phone)) :
            $data->telephone = $post->contact_phone;
            if(!empty($post->contact_phone2)) $data->telephone2 = $post->contact_phone2;
            if(!empty($contact->contact_phone2)) $data->telephone3 = $contact->contact_phone2;
            if(!empty($contact->contact_phone3)) $data->telephone4 = $contact->contact_phone3;
            endif;

        if(!empty((array) $data)) $this->DWModel->ContactProfilSave($data, $post->id_contact_profil);
    }

}

