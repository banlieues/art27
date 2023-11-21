<?php

namespace Calculator\Models;

use Base\Models\BaseModel;
use CodeIgniter\Database\RawSql;

class DemandeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->table = $this->t_demande;
        $this->primaryKey = get_primary_key($this->table);
        $this->useAutoIncrement = true;
        $this->returnType = 'object';
    }

	public function DemandesGet($mes_demandes, $request, $orderBy=NULL, $orderDirection=NULL)
	{
        $sq1 = $this->db->table($this->t_bien_contact);
        $sq1->select("
            GROUP_CONCAT(
                DISTINCT CONCAT_WS('',
                    UPPER(COALESCE($this->t_contact.nom_contact, '')),
                    ' ',
                    COALESCE($this->t_contact.prenom_contact, ''),
                    '@@rel@@',
                    COALESCE($this->t_l_bien_contact_type.label, ''),
                    '@@rel@@',
                    $this->t_contact.id_contact
                ) SEPARATOR '@SEPARATOR@'
            )
        ");
        $sq1->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact.id_contact", 'left');
        $sq1->join($this->t_profil, "$this->t_profil.id_contact_profil = $this->t_bien_contact.id_contact_profil", 'left');
        $sq1->join($this->t_l_bien_contact_type, "$this->t_l_bien_contact_type.id = $this->t_bien_contact.rel_personne_bien", 'left');
        $sq1->where("$this->t_bien_contact.id_demande", new RawSql("$this->t_demande.id_demande"));
        $sq1_query = $sq1->getCompiledSelect();

        $sq2 = $this->db->table($this->t_bien_contact);
        $sq2->select("
            GROUP_CONCAT(
                DISTINCT CONCAT_WS('',
                    COALESCE($this->t_bien.adresse_fr, ''),
                    '@@rel@@',
                    COALESCE($this->t_l_bien_contact_type.label, ''),
                    '@@rel@@',
                    $this->t_bien.id_bien
                ) SEPARATOR '@SEPARATOR@'
            )
        ");
        $sq2->join($this->t_bien, "$this->t_bien.id_bien = $this->t_bien_contact.id_bien", 'left');
        $sq2->join($this->t_l_bien_contact_type, "$this->t_l_bien_contact_type.id = $this->t_bien_contact.rel_personne_bien", 'left');
        $sq2->where("$this->t_bien_contact.id_demande", new RawSql("$this->t_demande.id_demande"));
        $sq2_query = $sq2->getCompiledSelect();

        $sq3 = $this->db->table($this->t_work);
        $sq3->select("CONCAT_WS('', '[', GROUP_CONCAT($this->t_work.id_work), ']')");
        $sq3->where("$this->t_work.id_demande", new RawSql("$this->t_demande.id_demande"));
        $sq3_query = $sq3->getCompiledSelect();

        $sq4 = $this->db->table($this->t_bien_contact);
        $sq4->select("$this->t_bien_contact.*");
        $sq4->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact.id_contact", 'left');
        $sq4->where("$this->t_bien_contact.id_demande", new RawSql("$this->t_demande.id_demande"));
        $sq4->notLike("$this->t_contact.nom_contact", "%ANONYM%");
        $sq4_query = $sq4->getCompiledSelect();

		$this->select("
            $this->table.id_demande, 
            $this->table.date,
            $this->table.id_type_demande,
            $this->table.id_demande_statut,
            $this->table.nom as sujet,
            $this->table.id_utilisateur,
            $this->t_l_demande_statut.label as statut,
            $this->t_l_demande_type.label as type,
            user_created.id as id_createur,
            user_created.nom as nom_createur,
            user_created.prenom as prenom_createur,
            user_affected.id as id_encharge,
            user_affected.nom as nom_encharge,
            user_affected.prenom as prenom_encharge,
            ($sq1_query) as contact_associee,
            ($sq2_query) as bien_associe,
            ($sq3_query) as works
        ");

		$this->join($this->t_l_demande_type,"$this->t_l_demande_type.id = $this->table.id_type_demande", "left");
		$this->join($this->t_l_demande_statut,"$this->t_l_demande_statut.id = $this->table.id_demande_statut", "left");
		$this->join("$this->t_user as user_created","user_created.id = $this->table.id_user_create", "left");
		$this->join("$this->t_user as user_affected","user_affected.id = $this->table.id_utilisateur", "left");
		$this->join($this->t_bien_contact,"$this->t_bien_contact.id_demande = $this->table.id_demande", "left");

		$this->where("$this->t_bien_contact.id_bien > ", 0);
		$this->where("$this->t_bien_contact.id_contact > ", 0);
		$this->where("exists($sq4_query)");

		if($request->getGet("statut_demande") && !empty(trim($request->getGet("statut_demande")))) :
			$this->where("id_demande_statut", $request->getGet("statut_demande"));
			if(
				$request->getGet("mes_demandes") &&
                !empty(trim($request->getGet("mes_demandes"))) &&
				$request->getGet("homegrade") &&
                !empty(trim($request->getGet("homegrade")))
			) :
				$this->groupStart();
					$this->where("$this->t_demande.id_utilisateur", session("loggedUserId"));
					$this->orWhere("$this->t_demande.id_utilisateur", 25);
				$this->groupEnd();
            endif;
		elseif($mes_demandes==1) :
			$this->where("$this->t_demande.id_utilisateur", session("loggedUserId"));
		elseif($request->getGet("homegrade") && !empty(trim($request->getGet("homegrade")))) :
			$this->where("$this->t_demande.id_utilisateur", 25);
        endif;

		//itemSearch exists
        if($request->getGet("itemSearch") && !empty(trim($request->getGet("itemSearch")))) :
            $items = explode(" ", $request->getGet("itemSearch"));
            $fieldSearchs = [
                "$this->table.id_demande",
                "$this->t_contact.nom_contact",
                "$this->t_contact.prenom_contact",
                "user_created.nom",
				"user_created.prenom",
				"user_affected.nom",
				"user_affected.prenom",
				"($sq1_query)",
				"($sq2_query)",
			];
            $this->join($this->t_contact,"$this->t_contact.id_contact = $this->t_bien_contact.id_contact","left");
            $this->groupStart();
                foreach($items as $item):
                    $this->groupStart();
                    foreach($fieldSearchs as $fieldSearch):
                        $this->orLike($fieldSearch, $item);
                    endforeach;
                    $this->groupEnd();
                endforeach;
            $this->groupEnd();
        endif;
		 
        $this->groupBy("personne_bien.id_demande");
        if(!is_null($orderBy)) $this->orderBy(sql_orderByDirection($orderBy, $orderDirection));
	 
		//$this->like("nom","Wil");
		return database_decode($this->paginate(50));
	}


	public function get_demandeurs($id_demande)
	{
		$builder=$this->db->table("demande");

		$builder->select("
			personne_bien.id_contact,
			contact.nom_contact,
			contact.prenom_contact,
			contact_profil.email,
			contact_profil.adresse,
			contact_profil.localite,
			contact_profil.telephone,
			liste_rel_personne_bien.label as type_demandeur,
			liste_langue.label as langue,
		");

		$builder->join("personne_bien","personne_bien.id_demande=demande.id_demande","left");
		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");
		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");
		$builder->join("liste_rel_personne_bien","liste_rel_personne_bien.id=personne_bien.rel_personne_bien","left");
		$builder->join("liste_langue","liste_langue.id=contact.id_langue","left");

		$builder->where("personne_bien.id_demande",$id_demande);
		$builder->where("personne_bien.id_contact>",0);


		return $builder->get()->getResult();

	}

	public function get_bien($id_demande)
	{
		$builder=$this->db->table("demande");

		$builder->select("
			personne_bien.id_bien,
			bien.adresse_fr,
			bien.adresse_nl,
			liste_bien_type.label as type,
			bien.bt,
			bien.etage_logement,

		");

		$builder->join("personne_bien","personne_bien.id_demande=demande.id_demande","left");
		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");
		$builder->join("liste_bien_type","liste_bien_type.id=bien.id_type","left");


		$builder->where("personne_bien.id_demande",$id_demande);
		$builder->where("personne_bien.id_bien>",0);

		

		return $builder->get()->getRow();

	}


	public function statut_demande()
	{
		$builder=$this->db->table("liste_demande_statut");
		$builder->orderBy("rank,label");

		return $builder->get()->getResult();
	}
  
 


}