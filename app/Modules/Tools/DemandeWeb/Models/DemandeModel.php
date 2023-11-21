<?php

namespace DemandeWeb\Models;

use Base\Models\BaseModel;
use Components\Libraries\ListLibrary;
use DataView\Libraries\DataViewConstructor;

class DemandeModel extends BaseModel 
{
    protected $allowedFields;
	protected $fields;
	protected $primaryKey = 'id_deposit';
	protected $returnType = 'object';
	protected $useAutoIncrement = true;
	protected $table = 're_deposit';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    public function BuildingAddByDemande($post)
    {
        $data = (object) [];
        $data->adresse_fr = $post->address_fr;
        $data->adresse_nl = $post->address_nl;
        $data->bt = !empty($post->address_box) ? $post->address_box : null;
        $data->etage_logement = !empty($post->address_floor) ? $post->address_floor : null;
        $data->id_crm_user = session('loggedUserId');
        $data->is_delete = 0;
        $this->db->table($this->t_bien)->set(database_encode($this->t_bien, $data))->insert();
        // dbdebug();
        $id_building = $this->db->insertID();

        $data = (object) [];
        $data->id_bien = $id_building;
        $data->id_demande = $post->id_demande;
        $data->id_user = session('loggedUserId');
        $data->id_user_create = session('loggedUserId');
        $data->is_delete = 0;
        $data->is_filtre = 0;
        $this->db->table($this->t_bien_demande)->set(database_encode($this->t_bien_demande, $data))->insert();
        // dbdebug();
    }

    public function DemandeImportEmailInsert($deposit, $id_demande)
    {
        // créer un email relié à la demande $id_demande
        $email = (object) [];
        $email->created_datetime = $deposit->gf_date_created;
        $email->received_datetime = $deposit->date_import;
        $email->importance = 'normal';
        $email->subject = 'Homegrade.brussels : ' . $deposit->subject;
        $email->body_type = 'text';
        $email->body_preview = $deposit->comment;
        $email->body_content = $deposit->comment;
        $email->sender_mail = $deposit->contact_email;
        $email->to_mail = 'info@homegrade.brussels';
        $email->send_name = fullname($deposit->contact_name, $deposit->contact_lastname);

        $this->db->table($this->t_email)->set(database_encode($this->t_email, $email))->insert();
        $id_email = $this->db->insertID();
        
        $link = (object) [];
        $link->id_demande = $id_demande;
        $link->id_email = $id_email;
        $this->db->table($this->t_demande_email)->set(database_encode($this->t_demande_email, $link))->insert();
    }   

    public function ContactProfilSave($data, $id_contact_profil=null)
    {
        $data->updated_by = session('loggedUserId');
        if(!empty($id_contact_profil)) :
            $data->updated_at = date('Y-m-d H:i:s');
            $profil = $this->db->table($this->t_profil)->where('id_contact_profil', $id_contact_profil)->get()->getRow();

            if(!empty(database_encode($this->t_contact, $data))) :
                $this->db->table($this->t_contact)->set(database_encode($this->t_contact, $data))->where('id_contact', $profil->id_contact)->update();
            endif;
            if(!empty(database_encode($this->t_profil, $data))) :
                $this->db->table($this->t_profil)->set(database_encode($this->t_profil, $data))->where('id_contact_profil', $id_contact_profil)->update();
            endif;
        else :
            $data->created_by = session('loggedUserId');
            $this->db->table($this->t_contact)->set(database_encode($this->t_contact, $data))->insert();
            $data->id_contact = $this->db->InsertID();
            $this->db->table($this->t_profil)->set(database_encode($this->t_profil, $data))->insert();
            $id_contact_profil = $this->db->InsertID();
        endif;

        return $id_contact_profil;
    }

    // public function BuildingSave($data, $id_building=null)
    // {
    //     if(!empty($id_building)) :
    //         $this->db->table($this->t_building)->set(database_encode($this->t_building, $data))->where('id_bien', $id_building)->update();
    //     else :
    //         $this->db->table($this->t_building)->set(database_encode($this->t_building, $data))->insert();
    //         $id_building = $this->db->insertID();
    //     endif;

    //     return $id_building;
    // }

    // private function deposit_import_ids_contact_schedule($value_gf)
    // {
    //     $value_crm = [];
    //     foreach($value_gf as $v) :
    //         if(is_numeric($v)) :
    //             $value_crm[] = $v;
    //         elseif(is_json($v)) :
    //             $v_array = json_decode($v);
    //             $value_crm = array_merge($v_array, $value_crm);
    //         endif;
    //     endforeach;

    //     return array_filter($value_crm);
    // }



    // public function demande_create($id_deposit)
    // {
    //     $deposit = $this->DepositGet($id_deposit);
    //     $deposit->id_contact_source = 1;
    //     // $deposit->comment = $deposit->comment; 
    //     $id_demande = $this->DWModel->insert($deposit);

    //     $this->DepositDelete($id_deposit);

    //     return $id_demande;
    // }

    // public function demande_update_by_id($id_demande, $data)
    // {
    //     $this->load->model('demande_model');

    //     $data = $this->DWModel->demande_clean_for_database($data);
    //     $data->updated_by = $_SESSION['id'];
    //     $data->updated_at = date('Y-m-d H:i:s');
    //     $file = $this->path . 'Config/Json/deposit/table.json';
    //     $nullable_fields = get_nullable_fields($file);
    //     $data = database_encode($this->t_deposit, $data, $nullable_fields);
    //     $this->db->where('id_demande', $id_demande)->update($this->t_demande, $data);

    //     $this->DepositDelete($id_deposit);

    //     return $id_demande;
    // }

    public function DepositDelete($id_deposit)
    {
        $data = (object) [];
        $data->is_deleted = 1;

        $this->db->table($this->t_deposit)
            ->set(database_encode($this->t_deposit, $data))
            ->where('id_deposit', $id_deposit)
            ->update();
    }

    public function DepositModelData($order=null, $request=null)
    {
        $DataView = new DataViewConstructor();

        $this->select("
            $this->t_deposit.*,
            $this->t_deposit.gf_date_created as created_at,
            $this->t_list_lang.label as language,
            CONCAT_WS(' ', $this->t_user.prenom, $this->t_user.nom) as user_on_work,
        ");
        $this->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_deposit.id_lang", 'left');
        $this->join($this->t_user, "$this->t_user.id = $this->t_deposit.id_user_on_work", 'left');
        $this->where('is_deleted', 0);

        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_deposit.contact_name",
                "$this->t_deposit.contact_lastname",
                "$this->t_deposit.contact_email",
                "$this->t_deposit.address_city",
                "$this->t_deposit.subject",
                "$this->t_deposit.comment",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('created_at', 'desc');

        return $this;
    }

    public function DepositsGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->DepositModelData($order, $request)->where('is_deleted !=', 1);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $deposits = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $deposits = $modeldata->paginate($per_page);
        endif;
        $deposits = database_decode($deposits);

        foreach($deposits as $deposit) :
            $deposit->comment = nl2br($deposit->comment);
            if(!empty($deposit->id_user_on_work)) :
                $user_on_work = sessionUser($deposit->id_user_on_work);
                if(isset($user_on_work)) $deposit->user_on_work = fullname($user_on_work->prenom, $user_on_work->nom);
            endif;
        endforeach;

        return $deposits;
    }

    public function DepositsPagerGet()
    {
        return $this->pager;
    }

    public function DepositGet($id_deposit)
    {
        $q = $this->db->table($this->t_deposit);
        $q->select("$this->t_deposit.*");
        $q->select("CONCAT_WS('', $this->t_user.prenom, ' ', $this->t_user.nom) as user_on_work");
        $q->join($this->t_user, "$this->t_user.id = $this->t_deposit.id_user_on_work", 'left');
        $q->where('id_deposit', $id_deposit);
        $deposit = $q->get()->getRow();

        if(empty((array) $deposit)) return false;

        $deposit->address_fr = $deposit->address_street . ' ' . $deposit->address_pc . ' ' . $deposit->address_city;
        $deposit->contact_phone = str_replace(['(', ')', ' ', '/', '-'], '', $deposit->contact_phone);

        return database_decode($deposit);
    }

    public function BuildingGet($id_building)
    {
        $building = $this->db->table($this->t_building)->where('id_bien', $id_building)->get()->getRow();

        return $building;
    }

    public function DemandeGetIdenticalDoublon($deposit)
    {
        if(empty($deposit->comment)) return [];

        $q = $this->db->table($this->t_demande);
        $q->where("nom is not null");
        $q->where("descriptif is not null");
        $q->where("descriptif !=", "");
        $q->where("length(trim(descriptif)) > ", 50);
        $q->like("nom", "Homegrade.brussels");
        $q->like("REGEXP_REPLACE(descriptif, '[^A-Za-z0-9]', '')", preg_replace('/[^A-Za-z0-9]/', '', $deposit->comment));
        $demandes = $q->get()->getResult();
        
        return $demandes;
    }

    public function ContactProfilGet($id_contact_profil)
    {
        $q = $this->ContactProfilModelData();
        $q->where("$this->t_profil.id_contact_profil", $id_contact_profil);
        $profil = $q->get()->getRow();

        return database_decode($profil);
    }

    private function ContactProfilModelData()
    {
        $q = $this->db->table($this->t_contact);
        $q->distinct();
        $q->select("
            $this->t_contact.id_contact,
            $this->t_profil.id_contact_profil,
            $this->t_contact.prenom_contact as contact_name, 
            $this->t_contact.nom_contact as contact_lastname,
            $this->t_profil.email as contact_email, 
            $this->t_profil.email2 as contact_email2,
            $this->t_profil.telephone as contact_phone, 
            $this->t_profil.telephone2 as contact_phone2,
            $this->t_profil.telephone3 as contact_phone3,
        ");
        $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
        $q->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_contact.id_langue", 'left');
        $q->join($this->t_bien_contact_demande_profil, "$this->t_bien_contact_demande_profil.id_contact = $this->t_contact.id_contact", 'left');

        return $q;
    }

    public function ContactProfilGetDublon($deposit, $id_building=null)
    {
        $dublons = [];
        if(!empty($deposit->contact_name) && !empty($deposit->contact_lastname)) :
            $q = $this->ContactProfilModelData();
            $q->groupStart();
                $q->where('lower(trim(' . $this->t_contact . '.prenom_contact))', htmlspecialchars(mb_strtolower(trim($deposit->contact_name))));
                $q->where('lower(trim(' . $this->t_contact . '.nom_contact))', htmlspecialchars(mb_strtolower(trim($deposit->contact_lastname))));
            $q->groupEnd();
            $q->orGroupStart();
                $q->where('lower(trim(' . $this->t_contact . '.prenom_contact))', htmlspecialchars(mb_strtolower(trim($deposit->contact_lastname))));
                $q->where('lower(trim(' . $this->t_contact . '.nom_contact))', htmlspecialchars(mb_strtolower(trim($deposit->contact_name))));
            $q->groupEnd();
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;
        if(!empty($deposit->contact_email)) :
            $q = $this->ContactProfilModelData();
            $q->groupStart();
                $q->groupStart();
                    $q->where("1 = 0");
                    $q->orWhere("$this->t_profil.email is not null");
                    $q->orWhere("$this->t_profil.email2 is not null");
                    $q->orWhere("$this->t_profil.email3 is not null");
                $q->groupEnd();
                $q->groupStart();
                    $q->where("1 = 0");
                    $q->orWhere('lower(trim(' . $this->t_profil . '.email))', htmlspecialchars(mb_strtolower(trim($deposit->contact_email))));
                    $q->orWhere('lower(trim(' . $this->t_profil . '.email2))', htmlspecialchars(mb_strtolower(trim($deposit->contact_email))));
                    $q->orWhere('lower(trim(' . $this->t_profil . '.email3))', htmlspecialchars(mb_strtolower(trim($deposit->contact_email))));
                $q->groupEnd();
            $q->groupEnd();
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;
        if(!empty($deposit->contact_phone)) :
            $q = $this->ContactProfilModelData();
            $q->groupStart();
                $q->groupStart();
                    $q->where("1 = 0");
                    $q->orWhere("$this->t_profil.telephone is not null");
                    $q->orWhere("$this->t_profil.telephone2 is not null");
                    $q->orWhere("$this->t_profil.telephone3 is not null");
                    $q->orWhere("$this->t_profil.telephone4 is not null");
                $q->groupEnd();
                $q->groupStart();
                    $q->where("1 = 0");
                    $q->orWhere('lower(trim(' . $this->t_profil . '.telephone))', htmlspecialchars(mb_strtolower(trim($deposit->contact_phone))));
                    $q->orWhere('lower(trim(' . $this->t_profil . '.telephone2))', htmlspecialchars(mb_strtolower(trim($deposit->contact_phone))));
                    $q->orWhere('lower(trim(' . $this->t_profil . '.telephone3))', htmlspecialchars(mb_strtolower(trim($deposit->contact_phone))));
                    $q->orWhere('lower(trim(' . $this->t_profil . '.telephone4))', htmlspecialchars(mb_strtolower(trim($deposit->contact_phone))));
                $q->groupEnd();
            $q->groupEnd();
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;
        if(!empty($id_building)) :
            $q = $this->ContactProfilModelData();
            $q->where("$this->t_bien_contact_demande_profil.id_bien", $id_building);
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;

        return array_of_objects_unique(array_filter($dublons));

        // $q->resetQuery();
        // $q = $this->db->table($this->t_bien_contact_demande_profil);
        // if(!empty($id_building)) :
        //     $q->distinct();
        //     $q->select("
        //         $this->t_contact.id_contact,
        //         $this->t_profil.id_contact_profil,
        //         $this->t_contact.prenom_contact as contact_name, 
        //         $this->t_contact.nom_contact as contact_lastname,
        //         $this->t_profil.email as contact_email,
        //         $this->t_profil.email2 as contact_email2, 
        //         $this->t_profil.telephone as contact_phone,
        //         $this->t_profil.telephone2 as contact_phone2,
        //     ");
        //     $q->select("
        //         $this->t_bien_contact_demande_profil.id_bien as id_building,
        //         $this->t_building.adresse_fr as address_fr,
        //     ");
        //     $q->join($this->t_profil, "$this->t_profil.id_contact_profil = $this->t_bien_contact_demande_profil.id_contact_profil", 'left');
        //     $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_profil.id_contact", 'left');
        //     $q->join($this->t_building, "$this->t_building.id_bien = $this->t_bien_contact_demande_profil.id_bien", 'left');
        //     $q->where("$this->t_bien_contact_demande_profil.id_bien", $id_building);
        //     $doublons_by_building = $q->get()->getResult();

        //     $ids_contact = array_values(array_filter(array_column($doublons, 'id_contact')));
        //     $ids_contact_profil = array_values(array_filter(array_column($doublons, 'id_contact_profil')));

        //     foreach($doublons_by_building as $dbb) :
        //         if(!in_array($dbb->id_contact_profil, $ids_contact_profil) && !in_array($dbb->id_contact, $ids_contact)) :
        //             $doublons[] = $dbb;
        //         else :
        //             foreach($doublons as $doublon) :
        //                 if(
        //                     (!empty($doublon->id_contact_profil) && $doublon->id_contact_profil==$dbb->id_contact_profil) ||
        //                     (!empty($doublon->id_contact) && $doublon->id_contact==$dbb->id_contact)
        //                 ) $doublon = $dbb;
        //             endforeach;
        //         endif;
        //     endforeach;
        // endif;

        // return database_decode($doublons);
    }    
    
    public function BuildingGetDublonModel()
    {
        $q = $this->db->table($this->t_building);
        $q->distinct();
        $q->select("
            $this->t_building.id_bien as id_building,
            $this->t_building.adresse_fr as address_fr,
            $this->t_building.adresse_nl as address_nl,
            $this->t_building.adresse_fr_cp as address_pc,
            $this->t_building.bt as address_box,
            $this->t_building.etage_logement as address_floor,

            $this->t_contact.id_contact,
            $this->t_profil.id_contact_profil,
            $this->t_contact.prenom_contact as contact_name,
            $this->t_contact.nom_contact as contact_lastname,
        ");
        $q->join($this->t_bien_contact_demande_profil, "$this->t_bien_contact_demande_profil.id_bien = $this->t_building.id_bien", 'left');
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact_demande_profil.id_contact", 'left');
        $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');

        return $q;
    }

    public function BuildingGetDublon($deposit, $id_contact=null, $id_contact_profil=null)
    {
        $i = 0;
        $dublons = [];
        if(!empty($deposit->address_street) && !empty($deposit->address_pc)) :
            $q = $this->BuildingGetDublonModel();
            $q->where('trim(adresse_fr_cp)', trim(preg_replace('/[^0-9.]/', '', $deposit->address_pc)));
            $q->groupStart();
                $q->groupStart();
                    $q->where("$this->t_building.adresse_fr is not null");
                    $q->like("lower(trim($this->t_building.adresse_fr))", htmlspecialchars(trim($deposit->address_street)));
                $q->groupEnd();
                $q->orGroupStart();
                    $q->where("$this->t_building.adresse_nl is not null");
                    $q->like("lower(trim($this->t_building.adresse_nl))", htmlspecialchars(trim($deposit->address_street)));
                $q->groupEnd();
            $q->groupEnd();
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;
        // if(!empty($id_contact) || !empty($id_contact_profil)) :
            // $q->select("
            //     $this->t_contact.id_contact,
            //     $this->t_profil.id_contact_profil,
            //     $this->t_contact.prenom_contact as contact_name,
            //     $this->t_contact.nom_contact as contact_lastname,
            // ");
            // $q->join($this->t_bien_contact_demande_profil, "$this->t_bien_contact_demande_profil.id_bien = $this->t_building.id_bien", 'left');
            // $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact_demande_profil.id_contact", 'left');
            // $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
        if(!empty($id_contact_profil)) :
            $q = $this->BuildingGetDublonModel();
            $q->where("$this->t_bien_contact_demande_profil.id_contact_profil", $id_contact_profil);
            $dublons = array_merge($dublons, $q->get()->getResult());
        elseif(!empty($id_contact)) :
            $q = $this->BuildingGetDublonModel();
            $q->orWhere("$this->t_bien_contact_demande_profil.id_contact", $id_contact);
            $dublons = array_merge($dublons, $q->get()->getResult());
        endif;

        return array_values(array_of_objects_unique(array_filter($dublons)));
    }
    
    public function DemandeGetDublonModel()
    {
        $q = $this->db->table($this->t_bien_contact_demande_profil);
        $q->distinct();
        $q->select("
            $this->t_demande.id_demande as id_demande,
            $this->t_demande.date_insert as gf_date_created,
            $this->t_demande.nom as subject,
            $this->t_demande.id_demande_statut as id_demande_statut,
            $this->t_demande.descriptif as comment,

            $this->t_contact.id_contact,
            $this->t_profil.id_contact_profil,
            $this->t_contact.prenom_contact as contact_name,
            $this->t_contact.nom_contact as contact_lastname,

            $this->t_building.id_bien as id_building,
            $this->t_building.adresse_fr as address_fr,
        ");

        $q->join($this->t_demande, "$this->t_demande.id_demande = $this->t_bien_contact_demande_profil.id_demande", 'left');
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact_demande_profil.id_contact", 'left');
        $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
        $q->join($this->t_building, "$this->t_building.id_bien = $this->t_bien_contact_demande_profil.id_bien", 'left');

        return $q;
    }
    
    public function DemandeGetDublon($deposit, $id_contact=null, $id_contact_profil=null, $id_building=null)
    {
        $i = 0;
        $dublons = [];
        $q = $this->DemandeGetDublonModel();
        $q->groupStart();
            $q->where("$this->t_demande.nom", $deposit->subject);
            $q->orWhere("$this->t_demande.nom", "Homegrade.brussels : " . $deposit->subject);
            // $q->where("$this->t_demande.descriptif is not null");
        $q->groupEnd();
        $q->like("$this->t_demande.descriptif", $deposit->comment);
        $dublons = array_merge($dublons, $q->get()->getResult());
        // if(!empty($id_contact) || !empty($id_contact_profil)) :
            // $q->select("
            //     $this->t_contact.id_contact,
            //     $this->t_profil.id_contact_profil,
            //     $this->t_contact.prenom_contact as contact_name,
            //     $this->t_contact.nom_contact as contact_lastname
            // ");
            // $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact_demande_profil.id_contact", 'left');
            // $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
            if(!empty($id_contact_profil)) :
                $q = $this->DemandeGetDublonModel();
                $q->where("$this->t_bien_contact_demande_profil.id_contact_profil", $id_contact_profil);
                $dublons = array_merge($dublons, $q->get()->getResult());
            elseif(!empty($id_contact)) :
                $q = $this->DemandeGetDublonModel();
                $q->where("$this->t_bien_contact_demande_profil.id_contact", $id_contact);
                $dublons = array_merge($dublons, $q->get()->getResult());
            endif;
        // endif;
        if(!empty($id_building)) :
            $q = $this->DemandeGetDublonModel();
            // $q->select("
            //     $this->t_building.id_bien as id_building,
            //     $this->t_building.adresse_fr as address_fr
            // ");
            // $q->join($this->t_building, "$this->t_building.id_bien = $this->t_bien_contact_demande_profil.id_bien", 'left');
            $q->where("$this->t_bien_contact_demande_profil.id_bien", $id_building);        
            $dublons = array_merge($dublons, $q->get()->getResult());   
        endif;

        $dublons = database_decode(array_values(array_of_objects_unique(array_filter($dublons))));

        foreach($dublons as $dublon) :
            foreach($dublon as $ref=>$value) :
                $list = $this->ListLibrary->get_list_by_ref($ref);
                if(!empty($list)) :
                    $dublon->$ref = $this->ListLibrary->get_selected_object($value, $ref);
                endif;
            endforeach;
        endforeach;

        return $dublons;
    }

    // public function ContactProfilGet($id_contact, $id_contact_profil=null)
    // {
    //     $q = $this->db->table($this->t_contact);
    //     $q->select("
    //         $this->t_profil.id_contact_profil,
    //         $this->t_profil.id_contact,
    //         $this->t_contact.prenom_contact as contact_name, 
    //         $this->t_contact.nom_contact as contact_lastname,
    //         $this->t_profil.email, 
    //         $this->t_profil.email2, 
    //         $this->t_profil.email3, 
    //         $this->t_profil.telephone, 
    //         $this->t_profil.telephone2, 
    //         $this->t_profil.telephone3, 
    //         $this->t_profil.telephone4,
    //     ");
    //     $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
    //     $q->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_contact.id_langue", 'left');
    //     $q->where("$this->t_contact.id_contact", $id_contact);
    //     if(!empty($id_contact_profil)) $q->where("$this->t_profil.id_contact_profil", $id_contact_profil);

    //     $result = $q->get()->getRow();

    //     return $result;
    // }

    public function DepositSetWorker($id_deposit, $id_user=null)
    {
        $this->db->table($this->t_deposit)->set('id_user_on_work', $id_user)
            ->where('id_deposit', $id_deposit)
            ->update();
    }
}
