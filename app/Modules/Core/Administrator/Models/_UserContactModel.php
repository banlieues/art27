<?php

namespace Administrator\Models;

use CodeIgniter\Model;
use Administrator\Models\IdentificationModel;

class UserContactModel extends Model
{
    protected $table = "contact";
    protected $primaryKey = 'id_contact';
    // protected $inscriptions = "inscriptions";
    // protected $activities = "activities";

    protected $returnType     = 'object';

    public function __construct()
    {
        parent::__construct();

        $globals = new \Custom\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $globals_module = new \Administrator\Config\Globals();
        foreach($globals_module as $global_module=>$value) $this->$global_module = $value;

        $this->IdentificationModel = new IdentificationModel();
    }

    public function contact_unlink($id_contact, $id_user)
    {
        $q = $this->db->table($this->t_contact_user);

        $q->where("$this->t_contact_user.id_contact", $id_contact);
        $q->where("$this->t_contact_user.id_user", $id_user);

        $q->delete();
    }

    public function getContacts($id_user=null, $all_contacts=null)
    {
        if(empty($id_user)) $id_user = session('loggedUserId');

        $user = sessionUser($id_user);

        $this->select("
            $this->table.id_contact,
            $this->table.prenom_contact,
            $this->table.nom_contact,
            $this->table.date_naissance,

            (SELECT count(id_contact_profil) FROM $this->t_profil WHERE id_contact=$this->table.id_contact) as nb_profile
        ");

        if(empty($all_contacts)) :
            $this->join($this->t_contact_user, "$this->t_contact_user.id_contact = $this->table.id_contact", "left");
            $this->where("$this->t_contact_user.id_user", $id_user);
        endif;

        return $this->paginate(24);
    }

    public function getContactsFutur($request, $id_user=null)
    {
        if(!$request->getVar("itemSearch")) return [];

        if(empty($id_user)) $id_user = session('loggedUserId');

        $this->select("
            $this->table.id_contact,
            $this->table.nom_contact,
            $this->table.prenom_contact,
            $this->table.date_naissance,

            (SELECT count(id_contact_profil) FROM $this->t_profil WHERE id_contact=$this->table.id_contact) as nb_profile
        ");

        if($request->getVar("itemSearch") && !empty(trim($request->getVar("itemSearch"))))
        {
            $items = explode(" ", $request->getVar("itemSearch"));
            $fieldSearchs = array(
                "$this->table.id_contact",
                "$this->table.nom_contact",
                "$this->table.prenom_contact",
            );
            $this->groupStart();
                foreach($items as $item):
                    $this->groupStart();
                    foreach($fieldSearchs as $fieldSearch):
                        $this->orLike($fieldSearch, $item);
                    endforeach;
                    $this->groupEnd();
                endforeach;
            $this->groupEnd();
        };

        $this->where("$this->table.id_contact NOT IN (
            SELECT id_contact FROM $this->t_contact_user WHERE id_user = $id_user
        )");

        return $this->paginate(24);
    }


    public function getOneContact($id_contact)
    {
        return $this->find($id_contact);
    }

    public function getProfilesByContact($id_contact)
    {
        $q = $this->db->table($this->t_profil);
        $q->where('id_contact', $id_contact);

        return $q->get()->getResult();
    }


   public function setFuturContacts($var)
   {
      
        $builder=$this->db->table("user_contacts");

        $data["id_user"]=$var["id_user"];
        $data['created_by'] = session('loggedUserId');
        $data['updated_by'] = session('loggedUserId');
        foreach($var["id_contacts"] as $id_contact)
        {
            $data["id_contact"]=$id_contact;
    
            $builder->insert($data);
        }

        return;
   }

   public function getDocumentsInscription($id_inscription)
   {
        $builder=$this->db->table("inscriptions_document");
        $builder->where("id_inscription",$id_inscription);
        $builder->orderBy("created_at");
        return $builder->get()->getResult();


   }

}
