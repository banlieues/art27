<?php

namespace Doublon\Controllers;

use App\Controllers\BaseController;


use Doublon\Models\Mdoublon;
use DataView\Models\DataViewConstructorModel;
use Doublon\Config\Doublon_config; 

use App\Models\DeleteModel;

use DataQuery\Models\DataQueryModel;

class Doublon extends BaseController  {

	protected $Fdoublon;

	public function __construct()
	{
		if(session()->get("loggedUserRoleId")!=1)
        {
             header("Location:".base_url("identification/logout"));
        }

		$this->Mdoublon=new Mdoublon();

		$this->DataModel=new DataViewConstructorModel();
		$this->descriptor=$this->DataModel->getDescriptorIndexByFields();
		$this->descriptorEntities=$this->DataModel->getDescriptorEntity();

		$this->dataQueryModel = new DataQueryModel();

		$this->deleteModel=new DeleteModel();

		$this->doublon_config = config("Doublon_config");
		$this->context="doublon";
		$this->titre="Gestion des doublons";
		$this->path="Doublon\Views\/";

		
	}

	public function index()
	{
		
		return view($this->path."/d_index",[
			"context"=>$this->context,
			"titleView"=>$this->titre,
			"champSelection"=>$this->get_champ_selection()
		]);
	}

	private function get_champ_selection(){
		$view=NULL;
		$entity_doublon=$this->doublon_config->entity_doublon;
		$descriptor=$this->descriptor;
		
			foreach ($entity_doublon as $name=>$entity)
			{
				$se=array();
				$fields=$entity["fields"];
			
				foreach($fields as $field)
				{
					$se[$field]= $descriptor[$field]["label"];
				}
			
				$data["entity"]=$this->descriptorEntities[$name]["label"];
				$data["type"]=$this->descriptorEntities[$name]["type"];
				$data["fields"]=$se;
				$data["path"]=$this->path;
				$view.=view($this->path."/d_champ_affiche",$data);
				//$entities[$name]=array('label'=>$entity["label"],'fields'=>$se);
			};
		
		return $view;
		//$data["entities"]=$entities;
		//return view("r_champ_selection",$data,TRUE);
		
		}

	public function search_by_field($entity="Entité inconnue")
	{
		
		if(empty($this->request->getVar()))
			return redirect()->to(base_url()."/doublon")->with("danger","Vous n'avez pas sélectionné de champs pour $entity!");
		


		foreach($this->request->getVar() as $index=>$value)
		{
			
			if(isset($this->descriptor[$index]))
			{
				$label[]=$this->descriptor[$index]["label"];

			}
		}
		
		$label_string=implode("+",$label);

		$listes=$this->Mdoublon->liste_doublon_by_critere($this->request->getVar(),$entity,$this->descriptor,$this->descriptorEntities);

		if(empty($listes))
			return redirect()->to(base_url()."/doublon")->with("success","Aucun doublon trouvé pour $entity!");

		return view($this->path."/d_liste_doublon",[
			"context"=>$this->context,
			"titleView"=>"Liste des doublons par critères pour l'entité $entity",
			"champSelection"=>$this->get_champ_selection(),
			"descriptor"=>$this->descriptor,
			"entity_dynamique"=>$entity,
			"listes"=>$listes,
			"input"=>$this->request->getVar(),
			"label_string"=>$label_string
		]);	
		
	}

	public function search_by_id($entity)
	{
		
		$entity_doublon=$this->doublon_config->entity_doublon;
		
		$modelSearch=$entity_doublon[$entity]["modelSearch"];
		$methodSearch=$entity_doublon[$entity]["methodSearch"];
		

		$results=$this->$modelSearch->$methodSearch($this->request);
		$pager=$this->$modelSearch->pager;

		//debug($results);

		return view($this->path."/d_form_search",[
			"itemSearch"=>$this->request->getVar("itemSearch"),
			"entity"=>$entity,
			"fiches"=>$results,
			"idSearch"=>$entity_doublon[$entity]["idSearch"],
			"fieldsSearch"=>$entity_doublon[$entity]["fieldSearch"],
			"pager"=>$pager,
            "nbResult"=> $pager->getTotal(),
		]);
		
	}

	public function get_tableau_fusion($entity)
	{
		$entity_doublon=$this->doublon_config->entity_doublon;
		
		if(is_array($this->request->getVar("id_doublons")))
		{
			$id_doublons=$this->request->getVar("id_doublons");
		}
		else
		{
			$id_doublons=explode(",",$this->request->getVar("id_doublons"));
		}
			

		if($this->request->getVar("tableau_fusion_direct")&&$this->request->getVar("tableau_fusion_direct")==1)
		{
			$tableau_fusion_direct=TRUE;
		}
		else
		{
			$tableau_fusion_direct=FALSE;
		}

		if(empty($id_doublons))
				return redirect()->to(base_url()."/doublon")->with("danger","Pas de fiches sélectionnés pour $entity!");

		if(count($id_doublons)==1)
				return redirect()->to(base_url()."/doublon")->with("danger","Vous devez sélectionner au moins deux fiches pour $entity!");
		
		for($i=1;$i<=count($id_doublons);$i++)
		{
			$var["ou_et_##$i"]="OR";
			$var["par_ouvert_##$i"] = 0;
			$var["entity_##$i"] = $entity;
			$var["champ_##$i"] = $entity_doublon[$entity]["index_key_entity"];
			$var["operateur_##$i"] = "egal";
			$var["##$i##_value"] = $id_doublons[$i-1];
			$var["par_ferme_##$i"] = 0;
		}
		
		$var["number"]=count($id_doublons);
		

		//$fields=$this->DataModel->getDescriptorIndexByType($entity);
		$entity_doublon=$this->doublon_config->entity_doublon;
		
		if(isset($entity_doublon[$entity]["mise_en_evidence"]))
		{
			$mise_en_evidence=$entity_doublon[$entity]["mise_en_evidence"];
		}
		else
		{
			$mise_en_evidence=NULL;
		}

		$var["fields_select"]=$this->DataModel->getFieldsIndexOrdered($entity,$mise_en_evidence);


		$execute=$this->dataQueryModel->executeQuery($var);
		$results=$execute["results"];

		
		$values=[];
		$indexes=[];

		foreach($results as $index=>$value)
		{
			
			foreach($value as $k=>$v)
			{
				$values_prov[]=$v;
				$indexes_prov[]=$k;
			}
			
			array_push($values,$values_prov);
			array_push($indexes,$indexes_prov);

			$values_prov=[];
			$indexes_prov=[];
		}
		

		$labels=$execute["labels"];

		

		return view($this->path."/d_table_fusion",[
			"labels"=>$labels,
			"values"=>$values,
			"indexes"=>$indexes,
			"tableau_fusion_direct"=>$tableau_fusion_direct,
			"context"=>$this->context,
			"titleView"=>"Doublon: Tableau de fusion",
			"entity"=>$entity
		]

		);

	
	}

	public function fusion($entity)
	{
		$input=$this->request->getVar();
		$entity_doublon=$this->doublon_config->entity_doublon;

		$exclude=["id_doublons"];

		$data_update=[];

		$key_primary=$this->descriptorEntities[$entity]["key_primary"];
		$table_primary=$this->descriptorEntities[$entity]["table_primary"];

		foreach($input["id_doublons"] as $id_doublon)
		{
			$indexes=[];
			foreach($input as $index=>$value)
			{
				if(!in_array($index,$exclude)&&$value==$id_doublon)
				{

					array_push($indexes,[$index=>$this->descriptor[$index]]);
				}
			}

			if(!empty($indexes))
			{
				$values_doublon=$this->Mdoublon->get_value_id_doublon($id_doublon, $indexes, $table_primary,$key_primary);

				$data_update=array_merge($data_update,$values_doublon);

			}
			
			
		}

		if(isset($data_update[$key_primary])&&!empty($key_primary)&&!is_null($key_primary))
		{
			$this->Mdoublon->get_update_doublon($data_update,$key_primary,$table_primary);

			$id_doublon_garder=$data_update[$key_primary];

			foreach($input["id_doublons"] as $id_doublon)
			{
				if($id_doublon!=$id_doublon_garder)
				{
					//echo "j'efface et update inscription pour $id_doublon <br>";

					$tables_doublons=$entity_doublon[$entity]["table_update"];

					$this->Mdoublon->update_tables_doublon($tables_doublons,$key_primary,$id_doublon,$id_doublon_garder);

					//pour update, parametrer dans fichier config les champs à parametrer, creer un champ de jointure


					//pour delete, utiliser id delete existant
					$this->deleteModel->deleteContact($id_doublon);
				}
			}
		}
		else
		{
			echo '<div class="py-5 text-center text-red"><b>ERROR! Impossible de fusionner</b></div>';
		}

		echo '<div class="py-5 text-center"><b>Fusion réussie!</b></div>';
	}
	
	
		
}
