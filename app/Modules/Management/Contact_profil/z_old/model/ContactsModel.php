<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Modules\DataView\Libraries\DataViewConstructor;


class ContactsModel extends Model
{
	protected $table="contacts";
	protected $tableActivite="activities";
    protected $tableInscription="inscriptions";
	protected $primaryKey = 'id_contact';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct();
        $activiteModel = new ActivitiesModel();
        $inscriptionModel = new RegistrationsModel();

        $this->tableInscription=$activiteModel->getTable();
        $this->tableInscription=$inscriptionModel->getTable();

		$dataViewConstructor=new DataViewConstructor();
		$this->fields=$dataViewConstructor->getFields();

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListContact($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				$this->table.id_contact, 
				$this->table.typepart,
				$this->table.nom,
				$this->table.prenom,
				$this->table.nom_court_institution,
				$this->table.date_naissance,
				$this->table.codepostal,
				$this->table.localite,
				$this->table.email,
				$this->table.email2,
				$this->table.adresse,
				user_accounts.id as id_user,
				(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(date_naissance)), '%Y')+0) AS age,
				user_accounts.username,
				user_accounts.nom as nom_user,
				user_accounts.prenom as prenom_user,
                (SELECT count(id_inscription) FROM $this->tableInscription WHERE $this->tableInscription.id_contact=$this->table.id_contact) as nb_inscription");

		//$this->where("typepart<>", " ");
		//itemSearch exists
		$this->join("user_contacts","user_contacts.id_contact=$this->table.id_contact","left");
		$this->join("user_accounts","user_accounts.id=user_contacts.id_user","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "$this->table.id_contact",
				 "$this->table.nom",
				 "$this->table.prenom",
				 "$this->table.nom_court_institution",
				 "$this->table.nom_long_institution",
				 "$this->table.codepostal",
				 "$this->table.localite",
				 "$this->table.adresse",
				 "$this->table.email",
				 "$this->table.email2",
				 "user_accounts.username",
				 "user_accounts.nom",
				 "user_accounts.prenom");
			
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


	

	public function getListContactForRegistration($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				$this->table.id_contact, 
				$this->table.typepart,
				$this->table.nom,
				$this->table.prenom,
				$this->table.nom_court_institution,
				$this->table.date_naissance,
				$this->table.codepostal,
				$this->table.localite,
				$this->table.email,
				$this->table.email2,
				user_accounts.id as id_user,
				user_accounts.username,
				user_accounts.nom as nom_user,
				user_accounts.prenom as prenom_user,
				
                (SELECT count(id_inscription) FROM $this->tableInscription WHERE $this->tableInscription.id_contact=$this->table.id_contact) as nb_inscription");

		//$this->where("typepart<>", " ");
		//itemSearch exists
		$this->join("user_contacts","user_contacts.id_contact=$this->table.id_contact","left");
		$this->join("user_accounts","user_accounts.id=user_contacts.id_user","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));

			 $fieldSearchs=array(
				 "$this->table.id_contact",
				 "$this->table.nom",
				 "$this->table.prenom",
				 "$this->table.nom_court_institution",
				 "$this->table.nom_long_institution",
				 "$this->table.codepostal",
				 "$this->table.localite",
				 "user_accounts.username",
				 "user_accounts.nom",
				 "user_accounts.prenom");
			
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

	public function get_user($id_contact)
	{
		$this->select("user_accounts.id as id_user,
				user_accounts.username,
				user_accounts.nom as nom_user,
				user_accounts.prenom as prenom_user");
				$this->join("user_contacts","user_contacts.id_contact=$this->table.id_contact","left");
				$this->join("user_accounts","user_accounts.id=user_contacts.id_user","left");
		return $this->find($id_contact);
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
