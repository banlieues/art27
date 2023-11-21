<?php

namespace Mailing\Models;

use Base\Models\BaseModel;
use Components\Libraries\JsonLibrary;
use DataView\Libraries\DataViewConstructor;

class MailingModel extends BaseModel 
{   
	protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
   
        $this->table = $this->t_email;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function ProfilsGetByDemande($id_demande)
    {
        $q = $this->db->table($this->t_bien_contact_demande_profil);
        $q->distinct();
        $q->select('id_contact');
        $q->select('id_contact_profil');
        $q->where('id_demande', $id_demande);
        $q->where('id_contact>0');
        $contacts = $q->get()->getResult();

        $i = 0;
        foreach($contacts as $contact) :
            $contacts[$i] = $this->ProfilGet($contact->id_contact, $contact->id_contact_profil=null);
            $i++;
        endforeach;

        return $contacts;
    }

    public function ProfilGet($id_contact, $id_contact_profil=null)
    {
        $q = $this->db->table($this->t_contact);
        $q->distinct();
        $q->select("
            $this->t_contact.id_contact, 
            $this->t_profil.id_contact_profil, 
            $this->t_contact.prenom_contact, 
            $this->t_contact.nom_contact,
            $this->t_profil.email, 
            $this->t_profil.telephone,

            $this->t_list_lang.label as langue,

            $this->t_list_gender.label as civilite, 
            $this->t_list_gender.label as civilite_fr, 
            $this->t_list_gender.label_nl as civilite_nl,
        ");
        $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
        $q->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_contact.id_langue", 'left');
        $q->join($this->t_list_gender, "$this->t_list_gender.id = $this->t_contact.id_civilite", 'left');

        if(!empty($id_contact_profil)) $q->where("$this->t_profil.id_contact_profil", $id_contact_profil);
        else $q->where("$this->t_contact.id_contact", $id_contact);

        $profil = $q->get()->getRow();

        return $profil;
    }

    
    public function EmailsGetByDemandeOutMessage($id_demande, $id_message)
    {
        $q = $this->db->table($this->t_demande_email);
        $q->where('id_demande', $id_demande);
        $q->where_not_in('id_email', $id_message);
        $mails = $q->get()->getResult();

        return $mails;
    }

    public function DemandeGet($id_demande)
    {
        $q = $this->db->table($this->t_demande);

        $q->select("
            $this->t_demande.id_demande, 
            $this->t_demande.id_type_demande,
            $this->t_demande.nom as subject_demande, 
            $this->t_demande.id_user,
            $this->t_demande.id_utilisateur,

            $this->t_user.prenom as prenom_assign, 
            $this->t_user.nom as nom_assign,
            CONCAT($this->t_user.prenom, ' ', $this->t_user.nom) as name_assign,
            $this->t_user.email as assign_mail,
            $this->t_user.id as assign_id,

            user_suiveur.email as suiveur_mail,
            user_suiveur.id as suiveur_id,
            user_suiveur.email as suiveur_mail,
            user_suiveur.id as suiveur_id,

            user_suiveur_default.email as suiveur_mail_default,
            user_suiveur_default.id as suiveur_id_default,
        ");

        $q->join($this->t_user, "$this->t_user.id = $this->t_demande.id_utilisateur", 'left');
        $q->join("$this->t_user as user_suiveur", "user_suiveur.id = $this->t_demande.id_utilisateur_2",'left');
        $q->join("$this->t_user as user_suiveur_default", "user_suiveur_default.id = $this->t_user.id_user_back_up",'left');

        $q->where('id_demande', $id_demande);
        $demande = $q->get()->getRow();

        return $demande;
    }

    public function EmailsGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->EmailModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $emails = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $emails = $modeldata->paginate($per_page);
        endif;
        $emails = database_decode($emails);

        return $emails;
    }

    public function EmailsPagerGet()
    {
        return $this->pager;
    }

    private function EmailModelData($order=null, $request=null)
	{
        $DataView = new DataViewConstructor();

        $this->resetQuery();
		$this->select("
            $this->t_email.*,
            coalesce($this->t_email.received_datetime, $this->t_email.send_datetime) as datetime,
        ");
		// $this->select("
        //     $this->t_email.*,
        //     $this->t_user.nom as author_lastname,
        //     $this->t_user.prenom as author_name,
        // ");
        // $this->join($this->t_user, "$this->t_user.id = $this->t_email.sender_mail", 'left');
        // $this->where("$this->t_email.sender_mail", session('loggedUserId'));

        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_email.to_mail",  
                "$this->t_email.cc_mail", 
                "$this->t_email.bcc_mail",
                "$this->t_email.subject", 
                "$this->t_email.body_content",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('datetime', 'desc');

        return $this;
    }

    public function languages_get()
    {
        $q = $this->db->table($this->t_list_lang);
        $q->select('*, LOWER(label) as label');
        $q->where('is_actif IS NOT NULL AND is_actif = 1');
        $q->orderBy('rank');
        return $q->get()->getResult();
    }

    public function variable_delete($id_var)
    {
        $this->db->table($this->t_mt_variable)->where('id_var', $id_var)->delete();
    }

    public function variable_get($id_var)
    {
        $variable = $this->db->table($this->t_mt_variable)->where('id_var', $id_var)->get()->getRow();

        return $variable;
    }

    public function variable_insert($post)
    {
        $post->created_by = session('loggedUserId');
        $post->updated_by = session('loggedUserId');
        $data = database_encode($this->t_mt_variable, $post);
        if(empty($data)) return false;

        $this->db->table($this->t_mt_variable)->set($data)->insert();
        $id_var = $this->db->getInsertID();

        return $id_var;
    }

    public function variables_get()
    {
        $variables = $this->db->table($this->t_mt_variable)->get()->getResult();

        return $variables;
    }

    public function variablesGetByContact($id_profil)
    {
        $ContactLibrary = new \Contact\Libraries\ContactLibrary();
        $profil = $ContactLibrary->contactGetByProfil($id_profil);
        
    }


}
