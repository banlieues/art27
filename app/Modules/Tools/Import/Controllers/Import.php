<?php

namespace Import\Controllers;
use Base\Controllers\BaseController;


use Import\Models\Mimport;

use Config\Config_import; 


use DataView\Models\DataViewConstructorModel;

use Layout\Libraries\LayoutLibrary;




class Import extends BaseController  {

	public function __construct()
	{
		/*if(session()->get("loggedUserRoleId")!=1)
        {
             header("Location:".base_url("identification/logout"));
        }

		$this->autorisationManager = \Config\Services::autorisationModel();*/

	/*	if(!$this->autorisationManager->is_autorise("importation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

		parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef("import");
        $this->datas->context = "import";

		$this->Mimport=new Mimport();

		$this->Mimport->init();

		$this->dataModel=new DataViewConstructorModel();

		$this->descriptor=$this->dataModel->getDescriptorIndexByFields();
		$this->descriptorEntities=$this->dataModel->getDescriptorEntity();

		$this->import_config=config("Config_import");

		$this->context="import";
		$this->titre="Importation";

		$this->path="Import\Views\/";
		$this->pathFile=APPPATH."/FileTemp/import_csv/";
	
	}

	public function index()
	{
		
		$tables_importer=$this->Mimport->getMetaDataTable();

		
			$this->datas->context=$this->context;
			$this->datas->titleView=$this->titre;
			$this->datas->tables_importer=$tables_importer;
	
		//debug($tables_importer);
		return view($this->path."/I_index",(array) $this->datas);
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

			//debugd($name_temp);
		
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

	

	public function table_importation($name_temp,$etape=1)
	{
		$primary_name_temp="id_".$name_temp;


		
		
		if(is_null($name_temp))
			return redirect()->to(base_url()."/import/index")->with("danger","Le fichier n'est pas valide" );

		
		$csvData=$this->Mimport->getTableImportOnlyFieldCsv($name_temp);
		//debugd($csvData);
		
		

		if(empty($csvData)||is_null($csvData))
			return redirect()->to(base_url()."/import/index")->with("danger","Je n'ai pas trouvé de données concernant ce fichier csv!" );

			if($etape==2)
			{
				
				for($i=0;$i<count($csvData);$i++)
				{
					

					if(isset($csvData[$i]->prenom_utilisateur)&&isset($csvData[$i]->nom_utilisateur))
					{	
						$search=$csvData[$i]->nom_utilisateur.' '.$csvData[$i]->prenom_utilisateur;
						$doublons=$this->Mimport->search_utilisateur($search);
						if(!empty($doublons))
						{
							$csvData[$i]->nom_utilisateur=$csvData[$i]->nom_utilisateur;
							foreach($doublons as $doublon)
							{
								$j=0;

								if($j==0): $checked='checked'; else: $checked=null; endif;
								$csvData[$i]->nom_utilisateur.="<div><input $checked value='$doublon->id'  name='data[nom_utilisateur][".$csvData[$i]->$primary_name_temp."]' type='checkbox'> <a target='_blank' href='".base_url("utilisator/index/$doublon->id")."'>Trouver $doublon->prenom $doublon->nom</a></div>";
							
								$j=$j++;
							
							}

						}
					}
					elseif(isset($csvData[$i]->nom_utilisateur))
					{
						//$csvData[$i]->nom_utilisateur="Jecherche";

						$search=$csvData[$i]->nom_utilisateur;
						$doublons=$this->Mimport->search_utilisateur($search);

						if(!empty($doublons))
						{
							$csvData[$i]->nom_utilisateur=$csvData[$i]->nom_utilisateur;
							foreach($doublons as $doublon)
							{
								$j=0;

								if($j==0): $checked='checked'; else: $checked=null; endif;
								$csvData[$i]->nom_utilisateur.="<div><input $checked name='data[nom_utilisateur][".$csvData[$i]->$primary_name_temp."]' value='$doublon->id' type='checkbox'> <a target='_blank' href='".base_url("utilisator/index/$doublon->id")."'>Trouver $doublon->prenom $doublon->nom</a></div>";
							
								$j=$j++;
							
							}

						}
					}

					if(isset($csvData[$i]->prenom_contact)&&isset($csvData[$i]->nom_contact))
					{
						$search=$csvData[$i]->nom_contact.' '.$csvData[$i]->prenom_contact;
						$doublons=$this->Mimport->search_contact($search);
						if(!empty($doublons))
						{
							$csvData[$i]->nom_contact=$csvData[$i]->nom_contact;
							foreach($doublons as $doublon)
							{
								$j=0;

								if($j==0): $checked='checked'; else: $checked=null; endif;
								$csvData[$i]->nom_contact.="<div><input $checked name='data[nom_contact][".$csvData[$i]->$primary_name_temp."]' value='$doublon->id_contact' type='checkbox'> <a target='_blank' href='".base_url("contacts/viewContact/$doublon->id_contact")."'>Trouver $doublon->prenom $doublon->nom</a></div>";
							
								$j=$j++;
							
							}

						}


					}
					elseif(isset($csvData[$i]->nom_contact))
					{
						$search=$csvData[$i]->nom_contact;
						$doublons=$this->Mimport->search_contact($search);
						if(!empty($doublons))
						{
							$csvData[$i]->nom_contact=$csvData[$i]->nom_contact;
							foreach($doublons as $doublon)
							{
								$j=0;

								if($j==0): $checked='checked'; else: $checked=null; endif;
								$csvData[$i]->nom_contact.="<div><input $checked name='data[nom_contact][".$csvData[$i]->$primary_name_temp."]' value='$doublon->id_contact' type='checkbox'> <a target='_blank' href='".base_url("contacts/viewContact/$doublon->id_contact")."'>Trouver $doublon->prenom $doublon->nom</a></div>";
							
								$j=$j++;
							
							}

						}

					}
				}

				
			}

		//debugd($csvData);
	
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
		
		

		$entities=$this->import_config->config["entity_import"];
		
	

		$indexes=$this->Mimport->getIndexActif($entities);
		
		natcasesort($indexes);

		$indexes["nom_utilisateur"]="utilisateur - nom";
		$indexes["prenom_utilisateur"]="utilisateur - prenom";
		//$indexes=sort($indexes);
		//debugd($indexes);
		
		$id_activite=0;
		return view($this->path."/I_tableau_import",[
			"context"=>$this->context,
			"titleView"=>$this->titre,
			"csv"=>$csvData,
			'csView'=>$csView,
			"indexes"=>$indexes,
			"dataModel"=>$this->dataModel,
			"name_temp"=>$name_temp,
			"primary_name_temp"=>$primary_name_temp,
			"etape"=>$etape,
			"activitePossible"=>$this->Mimport->getActivitesPossible($id_activite=0,false),
			"value_id_activite"=>$id_activite


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

	public function value_select($index_crm=0,$index_csv=0,$table_csv=0,$is_ajax=true)
	{

			$request = \Config\Services::request();

		


			if($index_crm=="0")
			{
				
					$message='<div class="text-danger bloque_etape2"><small>Vous devez sélectionner un index!</small></div>';
				


				if (!$request->isAJAX())
				{
					echo $message;
					return;
				}

				echo json_encode(
					["message_error"=>$message,"index_csv"=>$index_csv]
				);
				return;
			}
			else
			{
				
				//ici on modifie

				if($index_crm=="ban666luci")
				{
					$index_crm="untitled_".date("YmdHis").rand(2,2);
				}
				

				//ici on modifie
				if($index_crm!=$index_csv)
				{
					//on regarde si l'index existe déjà, sil existe, j'envoie un message d'erreur
					$db=db_connect();
		
					if ($db->fieldExists($index_crm, $table_csv)) 
					{
						$message= "<div class='text-danger bloque_etape2'><small>Impossible de changer l'index $index_csv en $index_crm car $index_crm est déjà lié à une autre colonne</small></div>";
						
						if (!$request->isAJAX())
						{
							echo $message;
							return;
						}

						echo json_encode(
							["message_error"=>$message,"index_csv"=>$index_csv]
						);
						return;
						
						
					}
					else
					{

						$this->Mimport->change_index($index_crm,$index_csv,$table_csv);

						$index_csv=$index_crm;


						
					}


					
				}

				$label_crm=[];
				$id_crm=[];


				$values_csv=[];

				if(!isset($this->descriptor[$index_crm]))
				{
				
					if (!$request->isAJAX())
								{
									return false;
								}

								echo json_encode(
									["message_error"=>"","index_csv"=>$index_csv]
								);
								return;
				}
					

				else
				{

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
								$message= view($this->path."/I_tableau_import_value",[
									"diff"=>$diff,
									"list_crm"=>$descriptor_index_crm,
									"table_csv"=>$table_csv,
									"index_csv"=>$index_csv
								]);

								if (!$request->isAJAX())
								{
									echo $message;
									return;
								}

								echo json_encode(
									["message_error"=>$message,"index_csv"=>$index_csv]
								);
								return;


							}
							
							

							return;

						default:
								if (!$request->isAJAX())
								{
									echo null;
									return;
								}

								echo json_encode(
									["message_error"=>"","index_csv"=>$index_csv]
								);
								return;
						
					}
					}
				
			}
		
	

		
	}

	
	
	

	public function insert()
	{
		if($this->request->getVar("name_temp"))
		{
			//variable
			$getVar=$this->request->getVar();
			//debugd($getVar);
			//savoir la table
			$id_primary_csv="id_".$this->request->getVar("name_temp");
			$table_primary_csv=$this->request->getVar("name_temp");

			$id_activity=$this->request->getVar("id_activite");

			//on récupére les données
			$csv=$this->Mimport->getTableImportOnlyFieldCsv($table_primary_csv,$this->request->getVar("id_primary"));
		
			//On récupere les descriptor
			$DataModel=new DataViewConstructorModel();
			$descriptor=$DataModel->getDescriptorIndexByFields();
			//$descriptorEntities=$DataModel->getDescriptorEntity();
			
			//debugd($descriptor);

			$db=db_connect();

			//Etape 1: Savoir les indexes
			$fields = $db->getFieldNames($table_primary_csv);
			//debugd($fields);


			//Etape 2 : On récupére les index présents dans le descriptor pour les contacts
			foreach($fields as $index_csv )
			{
				if(isset($descriptor[$index_csv])&&$descriptor[$index_csv]["table"]=="contacts")
				{
					$descriptor_contacts[$index_csv][]=$descriptor[$index_csv];
				}
			}

			//debug($descriptor_contacts);

			//Etape 3 : On récupére les index présents dans le descriptor pour les registrations

			if($id_activity>0)
			{
				foreach($fields as $index_csv )
				{
					if(isset($descriptor[$index_csv])&&$descriptor[$index_csv]["table"]=="inscriptions")
					{
						$descriptor_inscriptions[$index_csv][]=$descriptor[$index_csv];
					}
				}
			}

			//debugd($descriptor_inscriptions);


			//Etape 4 : On récupére les index présents dans le descriptor pour les users
			foreach($fields as $index_csv )
			{
				if(isset($descriptor[$index_csv])&&$descriptor[$index_csv]["table"]=="user_accounts")
				{
					$descriptor_utilisateurs[$index_csv][]=$descriptor[$index_csv];
				}
			}

			//debugd($descriptor_utilisateurs);

			// Etape 5: on va créer les contacts ou upadter les contactas

			foreach($csv as $index=>$value)
			{

				$data_contacts=[];
				$data_inscriptions=[];
				$data_utilisateurs=[];

		
				$type_insert_contacts="insert";
				$type_insert_utilisateurs="insert";

				if(isset($descriptor_contacts))
				{
					foreach($descriptor_contacts as $index=>$metadata)
					{
						$field_sql=$descriptor[$index]["field_sql"];
						$data_contacts[$field_sql]=$this->getValue($value->$index,$descriptor[$index]);
						$id_primary_csv_value=$value->$id_primary_csv;
						if(isset($getVar["data"]["nom_contact"][$id_primary_csv_value]))
						{
							$type_insert_contacts="update";
							$data_contacts["id_contact"]=$getVar["data"]["nom_contact"][$id_primary_csv_value];
						}
					}
				}

				if($id_activity>0)
				{
					$data_inscriptions["id_activity"]=$this->request->getVar("id_activite");
					if(isset($descriptor_inscriptions))
					{
						foreach($descriptor_inscriptions as $index=>$metadata)
						{
							$field_sql=$descriptor[$index]["field_sql"];
							$data_inscriptions[$field_sql]=$this->getValue($value->$index,$descriptor[$index]);
						}
					}
				}
				

				if(isset($descriptor_utilisateurs))
				{
					foreach($descriptor_utilisateurs as $index=>$metadata)
					{
						$field_sql=$descriptor[$index]["field_sql"];
						$data_utilisateurs[$field_sql]=$this->getValue($value->$index,$descriptor[$index]);
						$id_primary_csv_value=$value->$id_primary_csv;
						if(isset($getVar["data"]["nom_utilisateur"][$id_primary_csv_value]))
						{
							$type_insert_utilisateurs="update";
							$data_utilisateurs["id"]=$getVar["data"]["nom_utilisateur"][$id_primary_csv_value];
						}
					}
				}
				
			
				//Etape 6:
				if(!empty($data_contacts))
				{
					
					
					
					$builder=$db->table("contacts");

					if($type_insert_contacts=="insert")
					{
						$data_contacts["created_at"]=date("Y-m-d H:i:s");
						$builder->insert($data_contacts);
						$id_contact=$db->insertID();
					}
					else
					{
						unset($data_contacts["nom"]);
						unset($data_contacts["prenom"]);
						
						foreach($data_contacts as $index_contact=>$value_contact)
						{
							if(empty(trim($value_contact)))
							{
								unset($data_contacts[$index_contact]);
							}
						}

						$builder->where("id_contact",$data_contacts["id_contact"]);
						$builder->update($data_contacts);
						$id_contact=$data_contacts["id_contact"];
					}

					
				}
				
				if(!empty($data_inscriptions))
				{
					$data_inscriptions["created_at"]=date("Y-m-d H:i:s");
					$data_inscriptions["date_suivi"]=date("Y-m-d H:i:s");
					
					$builder=$db->table("inscriptions");
					$data_inscriptions["id_contact"]=$id_contact;
					
					$builder->insert($data_inscriptions);
				}

				if(!empty($data_utilisateurs))
				{
					
					$builder=$db->table("user_accounts");
					if($type_insert_utilisateurs=="insert")
					{	

						//username
						$username="";
						$nom="";
						$prenom="";
						

						//pasword
						$bytes = openssl_random_pseudo_bytes(4);
						$pass = bin2hex($bytes);


						if(isset($data_utilisateurs["nom"]))
						{
							$nom=$data_utilisateurs["nom"];
						}
						
				

						if(isset($data_utilisateurs["prenom"]))
						{
							$prenom=$data_utilisateurs["prenom"];
						}


						$email=null;

						if(isset($data_utilisateurs["email"]))
						{
							$email=$data_utilisateurs["email"];
						}
						elseif(isset($data_contacts["email"])&&!empty($data_contacts["email"]))
						{
							$email=$data_contacts["email"];
						}
						elseif(isset($data_contacts["email2"])&&!empty($data_contacts["email2"]))
						{
							$email=$data_contacts["email2"];
						}


						if(!empty($email))
						{
							$username=$email;
						}
						elseif(!empty($nom))
						{
							$username=filtre_filename($nom);
						}
						else
						{
							$bytes = openssl_random_pseudo_bytes(4);
							$username = bin2hex($bytes);
						}
						
						
						$password = $pass;
						$passhash = Hash::make($password);
						$token = md5(uniqid(mt_rand()));
						$created_at = date('Y-m-d H:i:s');
						$valided = 1;
						$actived = 1;
						$role_id = 2;

						$values = [
							'username' => $username,
							'email' => $email,
							'password' => $passhash,
							'token' => $token,
							'created_at' => date("Y-m-d H:i:s"),
							'valided' => 1,
							'actived' => $actived,
							'role_id' => $role_id,
							'nom'=>$nom,
							'prenom'=>$prenom,
							'created_at'=>date("Y-m-d H:i:s"),
							'created_by'=>session()->get("loggedUserId")
						];

            		$id_user = $this->Mimport->insert_user($values);
					
						$data_profiles=[
							'user_id'=>$id_user,
							'role_id'=>2,
							'avatar'=>"default.png",
							'actived'=>1,
							'created_at'=>date("Y-m-d H:i:s"),
							'created_by'=>session()->get("loggedUserId")
						];

						$builder=$db->table("user_profiles");
						$builder->insert($data_profiles);
						/*$data_utilisateurs["created_at"]=date("Y-m-d H:i:s");
						$data_utilisateurs["created_by"]=session()->get("loggedUserId");
	
						$builder->insert($data_utilisateurs);*/
						//$id_user=$db->insertID();

					}
					else
					{
						unset($data_utilisateurs["nom"]);
						unset($data_utilisateurs["prenom"]);
						$builder->where("id",$data_utilisateurs["id"]);
						$builder->update($data_utilisateurs);
						$id_user=$data_utilisateurs["id"];
					}

					$data_user_contacts["id_contact"]=$id_contact;
					$data_user_contacts["id_user"]=$id_user;
					$builder=$db->table("user_contacts");
					$builder->insert($data_user_contacts);
				}
				
				$id_primary_csv="id_".$this->request->getVar("name_temp");
				$table_primary_csv=$this->request->getVar("name_temp");
	
				//Je dois indiquer que c'est traiter
				$builder=$db->table($table_primary_csv);
				$data_log["is_imported"]=1;
				$builder->where($id_primary_csv,$value->$id_primary_csv);
				$builder->update($data_log);

				//je met à jpur les statistiques
				$builder=$db->table($table_primary_csv);
				$builder->where("is_imported",1);
				$total=$builder->countAllResults();

				$builder=$db->table("ban_import");
				$builder->where("name_table",$table_primary_csv);
				$result=$builder->get()->getRow();
				$total_ligne=$result->number_line;

				$builder=$db->table("ban_import");
				$builder->where("name_table",$table_primary_csv);
				$data_log_gen["number_total_import"]=$total;
				$data_log_gen["number_total_reste"]=$total_ligne-$total;
				$data_log_gen["updated_at"]=date("Y-m-d H:i:s");
				$data_log_gen["updated_by"]=session()->get("loggedUserId");
				$builder->update($data_log_gen);




				/*debug($data_contacts);
				echo "<div>mode: $type_insert_contacts</div>";
				debug($data_inscriptions);
				debug($data_utilisateurs);
				echo "<div>mode: $type_insert_utilisateurs</div>";
				echo '<hr>';*/

			}

			return redirect()->to(base_url()."/import/index")->with("success","Les données ont été importées dans le CRM");


		}
		
		


		
	}

	public function getValue($value,$descriptor)
	{
		$type=$descriptor["type_field"];
		
		switch($type)
		{
			case "select":
			case "radio":
			case "checkbox":

				$db=db_connect();

				$table_list=$descriptor["table_list"];
				$key_list=$descriptor["key_list"];
				$label_list=$descriptor["label_list"];

				$builder=$db->table($table_list);
				$builder->where($label_list,$value);
				$result=$builder->get()->getRow();

				if(isset($result->$key_list))
				{
					return $result->$key_list;
				}

				return 0;

			

			default: return $value;
		}

		
		
	}

	public function ne_pas_importer($label)
	{
	
		if(strpos($label, "untitled_") !== false)
		{
			return "ban666luci";
		}
		else
		{
			return $label;
		}
	}

	public function traducteur_list()
	{
		$db= \Config\Database::connect();

		$post=$this->request->getVar();

		//debugd($post);
		$data["modif"]=[];

		$list=$post["list"];
		$index_csv=$post["index_csv"];
		$table_csv=$post["table_csv"];

	
		for($i=0;$i<count($list["csv"]);$i++)
		{
			$data_update=[];
		
			if($list["crm"][$i]!="0")
			{
				$builder=$db->table($table_csv);
				if($list["crm"][$i]=="notraduction")
				{
					$data_update[$index_csv]=null;
					$builder->where($index_csv,$list["csv"][$i]);

					$builder->update($data_update);

				}
				else
				{
					$data_update[$index_csv]=$list["crm"][$i];
					//debug($list["crm"][$i]);
					//debug($list["csv"][$i]);
					$builder->where($index_csv,$list["csv"][$i]);

					$builder->update($data_update);

				}
			}
		}
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
	
	
	
