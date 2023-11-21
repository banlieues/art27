<?php

namespace Rdv\Models;
use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;

class RdvModel extends Model
{
	protected $table="rdv";
	protected $primaryKey = 'id_rdv';
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

	public function getListRdv($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				demande_rdv.id_demande,
				rdv.id_rdv,
				rdv.titre,
				rdv.date_rdv_debut,
				rdv.date_rdv_fin,
				rdv.temp_avant,
				rdv.temp_apres,
				rdv.id_type_rdv,
				rdv.id_statut_rdv,
				rdv.id_user_rdv,
				rdv.lieu,
				rdv.note,
				liste_rdv_type.label as type_rdv,
				liste_rdv_statut.label as statut_rdv,
				id_user_rdv

				
              
			");

	
		$this->join("liste_rdv_type","liste_rdv_type.id=rdv.id_type_rdv","left");
		$this->join("liste_rdv_statut","liste_rdv_statut.id=rdv.id_statut_rdv","left");
		$this->join("demande_rdv","demande_rdv.id_rdv=rdv.id_rdv","left");
		
		//debug($request->getVar("mes_rdv"));

		if($request->getVar("mes_rdv")==1)
		{
			$id_user=session()->get("loggedUserId");
			//$id_user=25;
			$this->where("FIND_IN_SET($id_user,rdv.id_user_rdv)<>",0);
			//$this->where("rdv.id_user_rdv IS NOT NULL");

		}
		
		//$this->join("demande_rdv","demande_rdv.id_rdv=rdv.id_rdv","left");
		

		//itemSearch exists

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			

			 $items=explode(" ",$request->getVar("itemSearch"));
			 $fieldSearchs=[
				 "demande_rdv.id_demande",
				 "rdv.titre",
				 "liste_rdv_type.label",
				 "liste_rdv_statut.label"

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

	

	public function getRdv($id_rdv)
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

			$this->where("id_rdv",$id_rdv);
			return $this->get()->getRow();

	}


	public function set_rdv($data,$id_demande,$id_rdv)
	{

		if($id_rdv>0)
		{
			$builder=$this->db->table("rdv");
			$builder->where("id_rdv",$id_rdv);
			$builder->update($data);
		}
		else
		{
			$builder=$this->db->table("rdv");
			$builder->insert($data);

			$id_rdv=$this->db->insertId();
		}

		if($id_demande>0)
		{
			//On vérifie si la relation existe
			$builder=$this->db->table("demande_rdv");
			$builder->where("id_demande",$id_demande);
			$builder->where("id_rdv",$id_rdv);
			$demande_rdv=$builder->get()->getRow();
			
			if(empty($demande_rdv))
			{
				$builder=$this->db->table("demande_rdv");
				$data_rdv_demande["id_demande"]=$id_demande;
				$data_rdv_demande["id_rdv"]=$id_rdv;

				$builder->insert($data_rdv_demande);

			}
			
		}

		return $id_rdv;

	}

	public function setType($id_rdv,$id_type_rdv)
	{
		$builder=$this->db->table("rdv");
		$builder->where("id_rdv",$id_rdv);
		$data["id_type_rdv"]=$id_type_rdv;

		$builder->update($data);
	}

	public function setStatut($id_rdv,$id_statut_rdv)
	{
		$builder=$this->db->table("rdv");
		$builder->where("id_rdv",$id_rdv);
		$data["id_statut_rdv"]=$id_statut_rdv;

		$builder->update($data);
	}
	

	public function setCommentaire($id_rdv,$note)
	{
		$builder=$this->db->table("rdv");
		$builder->where("id_rdv",$id_rdv);
		$data["note"]=$note;

		$builder->update($data);
	}

	public function delete_rdv($id_rdv)
	{
		$where=array('id_rdv'=>$id_rdv);

		$tables=["rdv","contact_rdv","demande_rdv"];

		foreach($tables as $table)
		{
			$builder=$this->db->table($table);
			$builder->where("id_rdv",$id_rdv);
			$builder->delete();
		}

		$rdv=$this->getRdv($id_rdv);

		if(!empty($rdv))
		{
			return false;
		}
		return true;
		
	}

}
