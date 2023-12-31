<?php

namespace Partenaire_social\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Libraries\DataViewDelivre;
use DataView\Models\DataViewConstructorModel;


class Partenaire_socialModel extends Model
{
	protected $table="partenaire_social";
	protected $primaryKey = 'id_partenaire_social';
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

		$this->DataViewModel=new DataViewConstructorModel();
		$this->dataViewDelivre=new DataViewDelivre();
		

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListPartenaire_social($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select(
			"
				partenaire_social.id_partenaire_social,
				partenaire_social.numero_partenaire_social,
				partenaire_social.nom_partenaire_social,
				partenaire_social.adresse_partenaire_social,
				partenaire_social.code_postal_partenaire_social,
				crm_list_code_postal.label as code_postal,
				partenaire_social.telephone_partenaire_social,
				partenaire_social.gsm_partenaire_social,
				partenaire_social.mail_partenaire_social,
				partenaire_social.web_partenaire_social,
				partenaire_social.instagram_partenaire_social,
				partenaire_social.facebook_partenaire_social,
				partenaire_social.remarque_by_partenaire_social,
				partenaire_social.commentaire_by_art27_partenaire_social,
				crm_list_statut_partenaire_social.label as statut_partenaire_social
			"
			
			);

			
		$this->join("crm_list_statut_partenaire_social","crm_list_statut_partenaire_social.id=partenaire_social.statut_partenaire_social","left");
		$this->join("crm_list_code_postal","crm_list_code_postal.id=partenaire_social.code_postal_partenaire_social","left");


		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				"crm_list_statut_partenaire_social.label",
				"partenaire_social.numero_partenaire_social",
				"partenaire_social.nom_partenaire_social",
				"partenaire_social.adresse_partenaire_social",
				"partenaire_social.code_postal_partenaire_social",	
				"partenaire_social.gsm_partenaire_social",
				"partenaire_social.mail_partenaire_social",
				"partenaire_social.web_partenaire_social",
				"crm_list_code_postal.label",
				"partenaire_social.telephone_partenaire_social",
				"partenaire_social.gsm_partenaire_social",
				"partenaire_social.mail_partenaire_social",
				"partenaire_social.web_partenaire_social",
				"partenaire_social.instagram_partenaire_social",
				"partenaire_social.facebook_partenaire_social",
				"partenaire_social.remarque_by_partenaire_social",
				"partenaire_social.commentaire_by_art27_partenaire_social"

				
				
			
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

	public function partenaire_social($id_partenaire_social)
	{
		$builder=$this->db->table("partenaire_social");
		$builder->select("
		partenaire_social.*, 
		concat(creator.prenom,' ',creator.nom) as createur, concat(updater.prenom,' ',updater.nom) as updateur
			");

		$builder->where("id_partenaire_social",$id_partenaire_social);
		$builder->join("user_accounts as creator","creator.id=partenaire_social.created_by","left");
		$builder->join("user_accounts as updater","updater.id=partenaire_social.updated_by","left");


		return $builder->get()->getRow();
	}

	public function partenaire_social_convention($id_partenaire_social)
	{
		$builder=$this->db->table("partenaire_social_convention");
		$builder->where("id_partenaire_social",$id_partenaire_social);
		$builder->orderBy("annee_convention_partenaire_social","DESC");
		$conventions= $builder->get()->getResult();



		if(empty($conventions))
		{
			/*$data["annee_convention_partenaire_social"]=null;
			$data["created_by"]=date("Y-m-d H:i:s");
			$data["created_at"]=session()->get("loggedUserId");
			$data["id_partenaire_social"]=$id_partenaire_social;
			$builder=$this->db->table("partenaire_social_convention");
			$builder->insert($data);
			$this->partenaire_social_convention($id_partenaire_social);*/

			return null;
		}
		else
		{
			return $conventions;
		}

	}


	public function saveDataDelivrePartenaire_social($indexesForm,$posts_brut,$id_partenaire_social=NULL)
    {
		//debugd($id_partenaire_social);
		//debugd($posts_brut);
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();


		$id_partenaire_social=$posts_brut["id_partenaire_social"];
        $posts_partenaire_social=$posts_brut;
        $is_insert=FALSE;
        $partenaire_social_exclude=["pk_value","id_partenaire_social"];

        foreach($posts_partenaire_social as $index=>$value)
        {
            if(!in_array($index,$partenaire_social_exclude))
            {
                $data_insert_partenaire_social[$index]=$value;
                
            }
        }
     
		//debugd($data_insert_partenaire_social);
        
		$id_partenaire_social=$this->dataViewDelivre->save_delivre(
				$indexesForm,
				$data_insert_partenaire_social,
				$table="partenaire_social",
				$name_key_primary="id_partenaire_social",
				$value_key_primary=$id_partenaire_social,
				$table_entity="partenaire_social",
				$name_entity_key="id_partenaire_social",
				$value_entity_primary=$id_partenaire_social
				);

		$tables_possibles_multiples=["partenaire_social_convention"];

		foreach($tables_possibles_multiples as $table_possible )
		{
			$this->dataViewDelivre->save_delivre_multiple(
				$indexesForm,
				$data_insert_partenaire_social,
				$table=$table_possible,
				$name_key_primary="id_$table_possible",
				$value_key_primary=NULL,
				$table_entity="partenaire_social",
				$name_entity_key="id_partenaire_social",
				$value_entity_primary=$id_partenaire_social,
				);
		}

		return $id_partenaire_social;
	}


	public function saveDataDelivreNewPartenaire_social($indexesForm,$posts_brut,$id_partenaire_social)
	{
		$id_partenaire_social=$this->saveDataDelivrePartenaire_social($indexesForm,$posts_brut,$id_partenaire_social);
		$posts_brut["id_partenaire_social"]=$id_partenaire_social;

		return $id_partenaire_social;
	}


	public function get_remarque_by_partenaire_social($id_partenaire_social)
	{
		$this->where("id_partenaire_social",$id_partenaire_social);
		$this->select("remarque_by_partenaire_social");

		return $this->get()->getRow();
	}

	public function get_commentaire_by_art27_partenaire_social($id_partenaire_social)
	{
		$this->where("id_partenaire_social",$id_partenaire_social);
		$this->select("commentaire_by_art27_partenaire_social");

		return $this->get()->getRow();
	}

	public function save_convention($post)
	{
		
		$convention=$this->convention($post["id_partenaire_social"],$post["annee"]);

	

		if(empty($convention))
		{
			$builder=$this->db->table("convention");
			$post["created_at"]=date("Y-m-d H:i:s");
			$post["created_by"]=session()->get("loggedUserId");
			unset($post["ci_session"]);
			unset($post["csrf_cookie_name"]);
			$builder->insert($post);
		}
		else
		{
			$post["updated_at"]=date("Y-m-d H:i:s");
			$post["updated_by"]=session()->get("loggedUserId");
			$builder=$this->db->table("convention");
			$builder->where("id_convention",$convention->id_convention);
			unset($post["ci_session"]);
			unset($post["csrf_cookie_name"]);

			$builder->update($post);
		}
		

	}

	public function has_convention($id_partenaire_social,$annee)
	{
		$builder=$this->db->table("convention");
		$builder->where("id_partenaire_social",$id_partenaire_social);
		$builder->where("annee",$annee);
		$result=$builder->get()->getRow();

		if(!empty($result))
			return true;

		return false;

	}

	public function convention($id_partenaire_social,$annee)
	{
		$builder=$this->db->table("convention");
		$builder->select("convention.*,
		concat(creator.prenom,' ',creator.nom) as createur, concat(updater.prenom,' ',updater.nom) as updateur
		");
		
		$builder->where("id_partenaire_social",$id_partenaire_social);
		$builder->where("annee",$annee);

		$builder->join("user_accounts as creator","creator.id=convention.created_by","left");
		$builder->join("user_accounts as updater","updater.id=convention.updated_by","left");

		$result=$builder->get()->getRow();
		return $result;

	}

	

	
}
