<?php

namespace Import\Controllers;
use App\Controllers\BaseController;


use Import\Models\Mimport;
use Import\Config\Import_config; 

use DataView\Models\DataViewConstructorModel;


class Import extends BaseController  {

	public function __construct()
	{
		if(session()->get("loggedUserRoleId")!=1)
        {
             header("Location:".base_url("identification/logout"));
        }

		$this->Mimport=new Mimport();

		$this->Mimport->init();

		$this->dataModel=new DataViewConstructorModel();

		$this->descriptor=$this->dataModel->getDescriptorIndexByFields();
		$this->descriptorEntities=$this->dataModel->getDescriptorEntity();

		$this->import_config=config("Import_config");

		$this->context="import";
		$this->titre="Importation";

		$this->path="Import\Views\/";
		$this->pathFile=APPPATH."/FileTemp/import_csv/";
	}

	public function index()
	{
		$tables_importer=$this->Mimport->getMetaDataTable();
		//debug($tables_importer);
		return view($this->path."/I_index",[
			"context"=>$this->context,
			"titleView"=>$this->titre,
			"tables_importer"=>$tables_importer
		]);
	}


	public function execute()
	{
		//on crée la table d'importation provisoire à partir du csv
		$input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,4096]|ext_in[file,csv],'
        ]);
	

        if (!$input) 
		{
            $data['validation'] = $this->validator->getErrors();
			//debug( $data['validation'],true);
			return redirect()->to(base_url()."/import/index")->with("danger",$data['validation']["file"] );
        }
		else
		{

			$file = $this->request->getFile("file");
			$basename_file=$file->getName();
			$name_temp=date("Ymdhis");
			$file->move($this->pathFile,$name_temp.".csv");

			if(is_null($name_temp))
				return redirect()->to(base_url()."/import/index")->with("danger","Le fichier n'est pas valide" );

			$file=$this->pathFile.$name_temp.".csv";

			$csv = new \ParseCsv\Csv();
			//$csv->limit = 5;
			$csv->auto($file);
			$csv->parseFile($file);
			$csvData=$csv->data;
			unlink($this->pathFile.$name_temp.".csv");
		
			if($this->Mimport->createTableBaseFromCsv($name_temp,$csv->data,$basename_file))
			{
				$table_name="ban_import_$name_temp";
				return redirect()->to(base_url()."/import/table_importation/$table_name");
			}
			else
			{
				return redirect()->to(base_url()."/import/index")->with("danger","Le fichier n'est pas valide" );
			}
			 
		}	

	}

	

	public function table_importation($name_temp)
	{
		
		
		if(is_null($name_temp))
			return redirect()->to(base_url()."/import/index")->with("danger","Le fichier n'est pas valide" );

		
		$csvData=$this->Mimport->getTableImportOnlyFieldCsv($name_temp);

		if(empty($csvData)||is_null($csvData))
			return redirect()->to(base_url()."/import/index")->with("danger","Je n'ai pas trouvé de données concernant ce fichier csv!" );

	
		//debug($csvData,true);
		//Créer array inverse
		foreach($csvData[0] as $label=>$value)
		{
			$indexes[]=$label;
		}
			
		$csView=NULL;


		foreach($indexes as $index)
		{
			$array_provisoire=[];
			foreach($csvData as $cdata)
			{
				foreach($cdata as $label=>$value)
				if($label==$index)
				{
					$array_provisoire[]=$value;
				}
			}
			//echo $index;
			//debug($array_provisoire);

			$csView[$index]=$array_provisoire;

		}
		
	

		$entities=$this->import_config->import_config["entity_import"];
		
		$indexes=[];
		foreach($entities as $entity)
		{
			
			$results=$this->dataModel->getIndexField($entity);

			foreach($results as $index_find)
			{
				$indexes[$index_find]=$index_find;
				//$indexes[$index_find]=$entity."-".$index_find;
			}
			
		}
		
		//debug($indexes);
		
		
		return view($this->path."/I_tableau_import",[
			"context"=>$this->context,
			"titleView"=>$this->titre,
			"csv"=>$csvData,
			'csView'=>$csView,
			"indexes"=>$indexes,
			"dataModel"=>$this->dataModel,
			"name_temp"=>$name_temp

		]);


		


	}


	public function retirer_importation($name_table,$id_ban_import)
	{
		$csvData=$this->Mimport->getMetaDataTableById($id_ban_import);

		if(empty($csvData)||is_null($csvData))
			return redirect()->to(base_url()."/import/index")->with("danger","Je n'ai pas trouvé de données concernant le fichier $csvData->name_file_origin!" );


		if($this->Mimport->deleteTableCsv($name_table,$id_ban_import))
		{
			
			return redirect()->to(base_url()."/import/index")->with("success","Le fichier $csvData->name_file_origin a été effacé!" );

		}
		else
		{
			return redirect()->to(base_url()."/import/index")->with("danger","Erreur inconnu! Je n'ai pas pu effacer le fichier $csvData->name_file_origin" );

		}
	}

	public function value_select()
	{
		if($this->request->getVar())
		{
			

			if($this->request->getVar("index_crm")=="0")
			{
				echo '<div class="text-danger"><small>Vous devez sélectionner un index!</small></div>';
				return;
			}
			else
			{
				$index_crm=$this->request->getVar("index_crm");
				$label_crm=[];
				$id_crm=[];


				$index_csv=$this->request->getVar("index_csv");
				$table_csv=$this->request->getVar("table_csv");
				$values_csv=[];


				$descriptor=$this->descriptor[$index_crm];
				switch ($descriptor["type_field"])
				{
					case "check":
					case "radio":
					case "select":
						//on récupérer les valuers du list select du crm
						$descriptor_index_crm=$this->dataModel->getListField((object) $descriptor);
						
						//on stocke les label et les id car on ne sait pas au départ si c'est encodé par label ou id
						foreach($descriptor_index_crm as $d )
						{
							$label_crm[]=$d->label;
							$id_crm[]=$d->id;
						}

					/*	debug($label_crm);
						debug($id_crm);
						echo $table_csv; echo $index_csv;*/

						//On recupere la liste des valeurs possibles du casv pour le champs en cours
						$values_csv=$this->Mimport->get_values_csv_array($table_csv,$index_csv);

						//ON sooustrait
						$diff=array_diff($values_csv,$label_crm);

						if(!empty($diff))
						{
							return view($this->path."/I_tableau_import_value",[
								"diff"=>$diff,
								"list_crm"=>$descriptor_index_crm,
							]);
						}
						
						

						return;

					default:
						echo NULL;
						return;
				}
			}
		}
		else
		{
			//echo "Erreur chargement";
		}

		//debug($this->request->getVar());
	}
	

	


		
}

	

/**
 * Voilà comment faire:
 * 
 * - On enregistre les données dans une table provisoire
 * - Cette table contient tout les champs du fichier
 * - Cette table contient en plus, 	un champ, 	ban_index_system qui va contenir l'index
 * 												ban_index_namee_file_origin qui va contenir le nom du fichier d'origine		
 * 												ban_index_date_importation, qui va contenir la date d'importation
 * 												ban_index_is_import, qui va contenir si la ligne a été importée
 * 												ban_index_date_is_import, qui va la date d'import de la ligne
 * 												ban_entity_import, qu'elle entité a été crée (contanct, ect)
 * 												ban_entity_id_import, la clé primaire qui correspond
 * 												ban_select_new_value, les valuers supplémentaires à ajouter, ok
 * - Une table ban_import contient la liste des fichiers en cours de tritement et leurs résultats de traitement
 * Cette table existe tant que tout n'a pas été importé ou qu'un utilisateur a marqué son accord pour effacer
 * On peut imaginer une liste de fichiers a traiter
 * Pour facilité, le tavleau d'importation (cf methode execute) va en ajax mettre à jour les données au fur et à mesure
 * 
 */
	
	
	
