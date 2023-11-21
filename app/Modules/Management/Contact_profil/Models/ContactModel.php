<?php

namespace Contact\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;


class ContactModel extends Model
{
	protected $table="contact";
	protected $primaryKey = 'id_contact';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct();
        // $activiteModel = new ActivitiesModel();
        // $inscriptionModel = new RegistrationsModel();

        // $this->tableInscription=$activiteModel->getTable();
        // $this->tableInscription=$inscriptionModel->getTable();

	


		$dataViewConstructor=new DataViewConstructor();
		$this->fields=$dataViewConstructor->getFields();

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListContact($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				contact.id_contact, 

				contact.nom_contact,
				contact.prenom_contact,
		
				contact.id_type_personne,
				liste_type_personne.label as type_personne,
				contact.id_langue,
				liste_langue.label as langue,
				contact.id_civilite,
				liste_civilite.label as civilite,

				(SELECT count(DISTINCT personne_bien.id_demande) FROM personne_bien WHERE personne_bien.id_personne=contact.id_contact) as nb_demande,

				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(bien.adresse_fr,'@@rel@@',liste_rel_personne_bien.label) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_personne=contact.id_contact 

				
				)
				as bien_associe 

			
				"
			);

			

		$this->join("liste_type_personne","liste_type_personne.id=contact.id_type_personne","left");
		$this->join("liste_langue","liste_langue.id=contact.id_langue","left");
		$this->join("liste_civilite","liste_civilite.id=contact.id_civilite","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 	"contact.id_contact",
				 	"contact.nom_contact",
					 "contact.prenom_contact",
					"liste_type_personne.label",
					"
					(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(bien.adresse_fr,'@@rel@@',liste_rel_personne_bien.label) SEPARATOR '@SEPARATOR@') 
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien


					WHERE personne_bien.id_personne=contact.id_contact 

				
				)
					",
				 	
			
				 )
				;
			


			 $this->groupStart();
				 foreach($items as $item):
					 $this->groupStart();
					 foreach($fieldSearchs as $fieldSearch):
						 $this->orLike($fieldSearch,$item);
					 endforeach;
					 $this->groupEnd();
				 endforeach;
			 $this->groupEnd();

		 }
		 
		 if(!is_null($orderBy))
		 	$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
		 
		return $this->paginate(20);
	}


	public function contact_profil($id_contact)
	{
		$builder=$this->db->table("contact_profil");

		$builder->where("id_contact",$id_contact);

		return $builder->get()->getResult();
	}

	

	public function insertData($data)
	{
		$builder=$this->db->table($this->table);
		$builder->insert($data);
		return $this->db->insertID();
	}

	public function updateData($data,$id_contact)
	{
		$builder=$this->db->table($this->table);
		$where[$this->primaryKey]=$id_contact;

		$builder->update($data,$where);
		return $id_contact;
	}	



}
