<?php

namespace Partenaire_culturel\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;


class Partenaire_culturelModel extends Model
{
	protected $table="partenaire_culturel";
	protected $primaryKey = 'id_partenaire_culturel';
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

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListPartenaire_culturel($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select
			(
				"
					partenaire_culturel.id_partenaire_culturel,
					partenaire_culturel.numero_partenaire_culturel,
					partenaire_culturel.nom_partenaire_culturel,
					partenaire_culturel.adresse_partenaire_culturel,
					partenaire_culturel.code_postal_partenaire_culturel,
					crm_list_code_postal.label as code_postal,
					partenaire_culturel.telephone_partenaire_culturel,
					partenaire_culturel.gsm_partenaire_culturel,
					partenaire_culturel.mail_partenaire_culturel,
					partenaire_culturel.web_partenaire_culturel,
					partenaire_culturel.instagram_partenaire_culturel,
					partenaire_culturel.facebook_partenaire_culturel,
					partenaire_culturel.remarque_by_partenaire_culturel,
					partenaire_culturel.commentaire_by_art27_partenaire_culturel 
				"
				
			);

			
	
		$this->join("crm_list_code_postal","crm_list_code_postal.id=partenaire_culturel.code_postal_partenaire_culturel","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array
			 (
				
				"partenaire_culturel.numero_partenaire_culturel",
				"partenaire_culturel.nom_partenaire_culturel",
				"partenaire_culturel.adresse_partenaire_culturel",
				"partenaire_culturel.code_postal_partenaire_culturel",	
				"partenaire_culturel.gsm_partenaire_culturel",
				"partenaire_culturel.mail_partenaire_culturel",
				"partenaire_culturel.web_partenaire_culturel",
				"crm_list_code_postal.label",
				"partenaire_culturel.telephone_partenaire_culturel",
				"partenaire_culturel.gsm_partenaire_culturel",
				"partenaire_culturel.mail_partenaire_culturel",
				"partenaire_culturel.web_partenaire_culturel",
				"partenaire_culturel.instagram_partenaire_culturel",
				"partenaire_culturel.facebook_partenaire_culturel",
				"partenaire_culturel.remarque_by_partenaire_culturel",
				"partenaire_culturel.commentaire_by_art27_partenaire_culturel"
				
			
			);
			


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

	public function partenaire_culturel($id_partenaire_culturel)
	{

		
		$builder=$this->db->table("partenaire_culturel");
		$builder->select("
		partenaire_culturel.*, 
		concat(creator.prenom,' ',creator.nom) as createur, concat(updater.prenom,' ',updater.nom) as updateur


			");

	

		$builder->where("id_partenaire_culturel",$id_partenaire_culturel);

		$builder->join("user_accounts as creator","creator.id=partenaire_culturel.created_by","left");
		$builder->join("user_accounts as updater","updater.id=partenaire_culturel.updated_by","left");


		

		
		return $builder->get()->getRow();
	}


	public function saveDataDelivrePartenaire_culturel($indexesForm,$posts_brut,$id_partenaire_culturel=NULL)
    {
		//debugd($id_partenaire_culturel);
		//debugd($posts_brut);
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();


		$id_partenaire_culturel=$posts_brut["id_partenaire_culturel"];
        $posts_partenaire_culturel=$posts_brut;
        $is_insert=FALSE;
        $partenaire_culturel_exclude=["pk_value","id_partenaire_culturel"];

        foreach($posts_partenaire_culturel as $index=>$value)
        {
            if(!in_array($index,$partenaire_culturel_exclude))
            {
                $data_insert_partenaire_culturel[$index]=$value;
                
            }
        }
       
        
		$data_insert_partenaire_culturel_with_index=$dataView->prepareData($indexesForm,$data_insert_partenaire_culturel,$fields,"partenaire_culturel",true,false);

        $data_insert_partenaire_culturel=$dataView->prepareData($indexesForm,$data_insert_partenaire_culturel,$fields,"partenaire_culturel",true);



        $builder=$this->db->table("partenaire_culturel");

        if($id_partenaire_culturel>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("partenaire_culturel",$data_insert_partenaire_culturel_with_index,"id_partenaire_culturel",$id_partenaire_culturel,"partenaire_culturel","partenaire_culturel");

			$data_insert_partenaire_culturel["updated_at"]=date("Y-m-d H:i:s");
            $data_insert_partenaire_culturel["updated_by"]=session()->get("loggedUserRoleId");
			//debug($id_partenaire_culturel);
			//debugd($data_insert_partenaire_culturel);
            $builder->where("id_partenaire_culturel",$id_partenaire_culturel);
			unset($data_insert_partenaire_culturel["id_partenaire_culturel"]);
            $builder->update($data_insert_partenaire_culturel);

			$this->DataViewModel->set_logs_fiche_insert_bd("partenaire_culturel",$data_changes,date("Y-m-d H:i:s"),$id_partenaire_culturel,$id_partenaire_culturel);


        }
        else
        {

			$data_insert_partenaire_culturel["created_at"]=date("Y-m-d H:i:s");
			$data_insert_partenaire_culturel["created_by"]=session()->get("loggedUserRoleId");
	
            //$data_insert_partenaire_culturel["id_type_personne"]=1;
            
            $builder->insert($data_insert_partenaire_culturel);
            $id_partenaire_culturel=$this->db->insertId();


			$data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_partenaire_culturel_with_index);
			$this->DataViewModel->set_logs_fiche_insert_bd("partenaire_culturel",$data_changes,date("Y-m-d H:i:s"),$id_partenaire_culturel,$id_partenaire_culturel);

        }
 
 
		 return $id_partenaire_culturel;
	}


	public function saveDataDelivreNewPartenaire_culturel($indexesForm,$posts_brut,$id_partenaire_culturel)
	{
		$id_partenaire_culturel=$this->saveDataDelivrePartenaire_culturel($indexesForm,$posts_brut,$id_partenaire_culturel);
		$posts_brut["id_partenaire_culturel"]=$id_partenaire_culturel;

		return $id_partenaire_culturel;
	}


	public function get_remarque_by_partenaire_culturel($id_partenaire_culturel)
	{
		$this->where("id_partenaire_culturel",$id_partenaire_culturel);
		$this->select("remarque_by_partenaire_culturel");

		return $this->get()->getRow();
	}

	public function get_commentaire_by_art27_partenaire_culturel($id_partenaire_culturel)
	{
		$this->where("id_partenaire_culturel",$id_partenaire_culturel);
		$this->select("commentaire_by_art27_partenaire_culturel");

		return $this->get()->getRow();
	}

	
}
