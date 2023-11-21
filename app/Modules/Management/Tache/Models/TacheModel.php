<?php

namespace Tache\Models;
use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;

class TacheModel extends Model
{
	protected $table="tache";
	protected $primaryKey = 'id_tache';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct();

		   $dataViewConstructor=new DataViewConstructor();
		   $this->fields=$dataViewConstructor->getFields();
    

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListTache($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				demande_tache.id_demande,
				tache.id_tache,
				tache.sujet,
				tache.date_tache,
				tache.echeance,
				tache.date_rappel,
				tache.id_type_tache,
				tache.id_statut_tache,
				tache.id_user_tache,
				tache.type_tache_libre,
				tache.note,
				liste_tache_type.label as type_tache,
				liste_tache_statut.label as statut_tache

				
              
			");

	
		$this->join("liste_tache_type","liste_tache_type.id=tache.id_type_tache","left");
		$this->join("liste_tache_statut","liste_tache_statut.id=tache.id_statut_tache","left");
		$this->join("demande_tache","demande_tache.id_tache=tache.id_tache","left");
		
		//debug($request->getVar("mes_tache"));

		if($request->getVar("mes_tache")==1)
		{
			$id_user=session()->get("loggedUserId");
			$this->where("FIND_IN_SET($id_user,tache.id_user_tache)<>",0);
		}
		
		//$this->join("demande_tache","demande_tache.id_tache=tache.id_tache","left");
		

		//itemSearch exists

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			

			 $items=explode(" ",$request->getVar("itemSearch"));
			 $fieldSearchs=[
				 "demande_tache.id_demande",
				 "tache.sujet",
				 "liste_tache_type.label",
				 "liste_tache_statut.label"

				];
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
		//$this->like("nom","Wil");
		return $this->paginate(50);
	}

	

	public function getTache($id_tache)
	{
		/*$this->select("
				$this->table.id_demande, 
				$this->table.date, 
				$this->table.id_type_demande,
				liste_demande_type.label as type,
				$this->table.id_demande_statut,
				liste_demande_statut.label as statut,
				$this->table.nom as sujet,
				createur.id as id_createur,
				createur.nom as nom_createur,
				createur.prenom as prenom_createur,
				encharge.id as id_encharge,
				encharge.nom as nom_encharge,
				encharge.prenom as prenom_encharge,
				backup.nom as nom_backup,
				backup.prenom as prenom_backup,
				$this->table.date_insert,
				$this->table.date_modification
			");

			$this->join("liste_demande_type","liste_demande_type.id=$this->table.id_type_demande","left");
			$this->join("liste_demande_statut","liste_demande_statut.id=$this->table.id_demande_statut","left");
			
			$this->join("user_accounts as createur","createur.id=$this->table.id_user_create","left");
			$this->join("user_accounts as encharge","encharge.id=$this->table.id_utilisateur","left");
			$this->join("user_accounts as backup","backup.id=$this->table.id_utilisateur_2","left");
	
			$this->join("personne_bien","personne_bien.id_demande=$this->table.id_demande","left");

			$this->where("$this->table.id_demande",$id_demande);
			$this->groupBy("personne_bien.id_demande");*/

			$this->where("id_tache",$id_tache);
			return $this->get()->getRow();

	}


	public function set_tache($data,$id_demande,$id_tache)
	{

		if($id_tache>0)
		{
			$builder=$this->db->table("tache");
			$builder->where("id_tache",$id_tache);
			$builder->update($data);
		}
		else
		{
			$builder=$this->db->table("tache");
			$builder->insert($data);

			$id_tache=$this->db->insertId();
		}

		if($id_demande>0)
		{
			//On vérifie si la relation existe
			$builder=$this->db->table("demande_tache");
			$builder->where("id_demande",$id_demande);
			$builder->where("id_tache",$id_tache);
			$demande_tache=$builder->get()->getRow();
			
			if(empty($demande_tache))
			{
				$builder=$this->db->table("demande_tache");
				$data_tache_demande["id_demande"]=$id_demande;
				$data_tache_demande["id_tache"]=$id_tache;

				$builder->insert($data_tache_demande);

			}
			
		}

		return $id_tache;

	}

	public function setType($id_tache,$id_type_tache)
	{
		$builder=$this->db->table("tache");
		$builder->where("id_tache",$id_tache);
		$data["id_type_tache"]=$id_type_tache;

		$builder->update($data);
	}

	public function setStatut($id_tache,$id_statut_tache)
	{
		$builder=$this->db->table("tache");
		$builder->where("id_tache",$id_tache);
		$data["id_statut_tache"]=$id_statut_tache;

		$builder->update($data);
	}
	

	public function setCommentaire($id_tache,$note)
	{
		$builder=$this->db->table("tache");
		$builder->where("id_tache",$id_tache);
		$data["note"]=$note;

		$builder->update($data);
	}

}
