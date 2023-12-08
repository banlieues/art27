<?php
namespace Import\Models;

use CodeIgniter\Model;
use DataView\Models\DataViewConstructorModel;

class MImport extends Model{

	protected $returnType     = 'object';
    
	public function init()
	{
		$forge = \Config\Database::forge();

		if(!$this->db->tableExists("ban_import"))
		{
			$fields = [
				'id_ban_import' => [
					'type'           => 'INT',
					'unsigned'       => true,
					'auto_increment' => true,
				],
				'name_file_origin' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
				],

				'name_table' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
				],

				'name_fields_origin'=> [
					'type'       => 'TEXT',
				],

				'name_fields_index'=> [
					'type'       => 'TEXT',
				],

				'number_line'=> [
					'type'       => 'INT',
				],

				'number_total_import'=> [
					'type'       => 'INT',
				],

				'number_total_insert'=> [
					'type'       => 'INT',
				],

				'number_total_update'=> [
					'type'       => 'INT',
				],

				'number_total_reste'=> [
					'type'       => 'INT',
				],


				'created_at' => [
					'type' => 'DATETIME',
				],

				'updated_at' => [
					'type' => 'DATETIME',
				
	
				],

				'created_by' => [
					'type' => 'INT',
				],

				'updated_by' => [
					'type' => 'INT',
	
				],
			
			];
	
			$forge->addField($fields);
			$forge->addPrimaryKey('id_ban_import');
			$forge->createTable('ban_import');
		}
	}
  
    public function createTableBaseFromCsv($name_temp,$csv,$basename_file)
	{
		$forge = \Config\Database::forge();

		//On vérifie si la table existe, si elle existe, on incrmente et on répéte jusqu'au moment où on peut la créer
		$name_temp="ban_import_$name_temp";
		/*$i=1;
		do{
			$name_temp=$name_temp."_".$i;

		} while($this->db->tableExists($name_temp));*/

		//debug($csv,true);
		$fields["id_$name_temp"]=[
			'type'           => 'INT',
			'unsigned'       => true,
			'auto_increment' => true,
		];

		$i=1;

		foreach($csv[0] as $label=>$value)
		{
		
			$name_field=slugify_name_file($label,"_");
			
			if(empty(trim($name_field))||is_numeric(trim($name_field)))
			{
				$name_field="untitled_$i";
				$i=$i+1;
			}
			
			$fields[$name_field]=['type' => 'TEXT','null'=> true];


			$label_origine[]=$name_field;

		}

		//debugd($fields);

	/*	$fields["ban_index_is_import"]=[
			'type'       => 'INT',
			'constraint'=> 1,
		];

		$fields["ban_index_date_is_import"]=[
			'type'       => 'DATETIME',
			'null'		=> true
		];

		$fields["ban_entity_import"]=[
			'type'       => 'VARCHAR',
			'constraint'=>'255',
			'null'		=> true
		];

		$fields["ban_entity_id_import"]=[
			'type'       => 'INT',
			'null'		=> true
		];

		

		$fields["ban_entity_id_import"]=[
			'type'       => 'INT',
			'null'		=> true
		];

		$fields["ban_select_new_value"]=[
			'type'       => 'text',
			'null'		=> true
		];*/


		$fields["is_imported"]=[
			'type'       => 'int',
			'null'		=> false
		];
		//debugd($fields);
	
		$forge->addField($fields);
		$forge->addPrimaryKey("id_$name_temp");
		
		if($forge->createTable($name_temp))
		{
			$builder=$this->db->table("ban_import");
			$data_insert=[
					"name_file_origin"=>$basename_file,
					"name_table"=>$name_temp,
					"created_at"=>date("Y-m-d H:i:s"),
					"created_by"=>session()->get("loggedUserId"),
					//"name_fields_origin"=>implode(",",$label_origine)

			];


			$builder->insert($data_insert);



			//debug($csv);
			//je copie les données
			$data_insert=[];
		
			foreach($csv as $cs)
			{
				$i=1;
				
				
				foreach($cs as $label=>$value)
				{
					$is_empty=false;
					$name_field=slugify_name_file($label,"_");
			
					if(empty(trim($name_field))||is_numeric(trim($name_field)))
					{
						$name_field="untitled_$i";
						$is_empty=true;
					}
					
					$label=$name_field;


					$data_ligne[$label]=$value;
					if($is_empty)
					{
						$i=$i+1;
					}
				}
				
				//debug($data_ligne);
				array_push($data_insert,$data_ligne);
				$data_ligne=[];
			}
			
			//debug($data_insert,true);

			$builder=$this->db->table($name_temp);
			$builder->insertBatch($data_insert);

			$builder=$this->db->table("ban_import");
			$data_update_import["number_line"]=count($data_insert);
			$builder->where("name_table",$name_temp);
			$builder->update($data_update_import);
			
			return TRUE;

		}
		else
		{
			return FALSE;
		}


	}

	

	public function getMetaDataTable($name_temp=NULL)
	{
		$builder=$this->db->table("ban_import");
	
		if(!is_null($name_temp))
		{
			$builder->where("name_table",$name_temp);
			return $builder->get()->getRow();
		}
		else
		{
			return $builder->get()->getResult();
		}
		

	}


	public function getMetaDataTableById($id_ban_import=NULL)
	{
		$builder=$this->db->table("ban_import");
		$builder->where("id_ban_import",$id_ban_import);
		return $builder->get()->getRow();
		
	
		

	}


	public function getTableImportOnlyFieldCsv($name_temp,$ids_primary=null)
	{
		$metadata=$this->getMetaDataTable($name_temp);

		if(!empty($metadata))
		{
			$name_fiels_origin=$metadata->name_fields_origin;

			$builder=$this->db->table($name_temp);
			$builder->select($name_fiels_origin);
			if(!is_null($ids_primary))
			{
				$builder->whereIn("id_$name_temp",$ids_primary);
			}
	
			$builder->where("is_imported",0);
			$builder->OrderBy("id_$name_temp");
			return $builder->get()->getResult();

		}
		else
		{
			return NULL;
		}

	}

	public function deleteTableCsv($name_table,$id_ban_import)
	{
		$builder=$this->db->table("ban_import");
		$builder->where("id_ban_import",$id_ban_import);

		if($builder->delete())
		{
			$forge = \Config\Database::forge();
			$forge->dropTable($name_table,TRUE);
			

			return true;
		}
		
		return false;
	}

	public function get_values_csv($table_csv,$index_csv)
	{
		$builder=$this->db->table($table_csv);

		$builder->select($index_csv);

		$builder->groupBy($index_csv);
		$builder->orderBy($index_csv);

		return $builder->get()->getresult();
	}


	public function get_values_csv_array($table_csv,$index_csv)
	{
		$values=$this->get_values_csv($table_csv,$index_csv);
		$values_csv=[];
		//debug($values);

		if(empty($values))
			return NULL;

		foreach($values as $value)
		{
			//En cas où, plusieurs valeurs
			$val_explode=explode(";",$value->$index_csv);

			foreach($val_explode as $val)
			{
				if(!empty(trim($val)))
				{
					$values_csv[]=trim($val);
				}
			}
			
		}		

		return $values_csv;
	}

	/*public function set_date_created()
	{
		$query="
			UPDATE activities SET created_at=date WHERE date!='0000-00-00'
		";

		$this->db->query($query);

		$query="
			UPDATE activities SET updated_at=maj WHERE maj!='0000-00-00'
		";

		$this->db->query($query);


		$query="
			UPDATE contacts SET created_at=date_creation WHERE date_creation!='0000-00-00'
		";

		$this->db->query($query);

		$query="
			UPDATE contacts SET updated_at=maj WHERE maj!='0000-00-00'
		";


		$query="
			UPDATE inscriptions SET created_at=(SELECT date_creation FROM contacts WHERE date_creation!='0000-00-00' AND contacts.id_contact=inscriptions.id_contact )
		";

		$this->db->query($query);

		$query="
			UPDATE inscriptions SET updated_at=(SELECT maj FROM contacts WHERE maj!='0000-00-00' AND contacts.id_contact=inscriptions.id_contact )
		";

		$this->db->query($query);


		$query="
			UPDATE lieu SET created_at=date_created WHERE date_created!='0000-00-00'
		";

		$this->db->query($query);

		$query="
			UPDATE lieu SET updated_at=date_updated WHERE date_updated!='0000-00-00'
		";


		$this->db->query($query);

		$query="
			UPDATE activities SET statut_action=3 WHERE cloturer='oui'
		";

		$this->db->query($query);


	}*/


	function getIndexActif($entities)
	{
		$indexes=[];
		foreach($entities as $entity)
		{
			$builder=$this->db->table("ban_components_$entity");
			$builder->select("fields");
			$builder->where("type",$entity);

			$result=$builder->get()->getResult();

			if(!empty($result))
			{
				foreach($result as $r)
				{
					if(!empty($r->fields))
					{
						$explode=explode(",",$r->fields);
						//$explode=ksort($explode);
						foreach($explode as $index)
						{
							
							if($index!="@#<hr>")
							{
								if($entity=="registrations")
								{
									$entity="inscriptions";
								}
								$indexes[$index]=$entity." - $index";
								
							}
						}
					}
					
				}
			}
		
		}
		return $indexes;
	}
    

	public function change_index($index_crm,$index_csv,$table_csv)
	{
		$forge = \Config\Database::forge();

		$fields=[
			$index_csv=>[
				'name'=>$index_crm,
				'type'=>"TEXT",
				'null'=>true
			]
		];

		if (!$this->db->fieldExists($index_crm, $table_csv)) {
			$forge->modifyColumn($table_csv,$fields);
		}


	}

	public function search_utilisateur($search)
	{
		if(!empty(trim($search)))
		{
			$builder=$this->db->table("user_accounts");

			$builder->select("
				user_accounts.id, 
				user_accounts.nom,
				user_accounts.prenom
					");
				
					$items=explode(" ",$search);
						
					$fieldSearchs=array(
						"user_accounts.nom",
						"user_accounts.prenom",
					
					);
					
					$builder->groupStart();
						foreach($items as $item):
							$builder->groupStart();
							foreach($fieldSearchs as $fieldSearch):
								$builder->orLike($fieldSearch,$item);
							endforeach;
							$builder->groupEnd();
						endforeach;
					$builder->groupEnd();

				return $builder->get()->getResult();
		}
		else
		{
			return null;
		}
	}

	public function search_contact($search)
	{
		if(!empty(trim($search)))
		{
			$builder=$this->db->table("contacts");

			$builder->select("
					contacts.id_contact, 
					contacts.nom,
					contacts.prenom,
					contacts.nom_court_institution
					");
				
					$items=explode(" ",$search);
						
					$fieldSearchs=array(
						"contacts.nom",
						"contacts.prenom",
					
					);
					
					$builder->groupStart();
						foreach($items as $item):
							$builder->groupStart();
							foreach($fieldSearchs as $fieldSearch):
								$builder->orLike($fieldSearch,$item);
							endforeach;
							$builder->groupEnd();
						endforeach;
					$builder->groupEnd();

				return $builder->get()->getResult();
		}
		else
		{
			return null;
		}
	}


	public function search_doublon($search,$index)
	{
		//Je dois récuperer le descriptor
		$dataViewModel=new DataViewConstructorModel();
		$descriptor=$this->dataViewModel->getOneField($index);
		debugd($descriptor);

		if(!empty(trim($search)))
		{
			$builder=$this->db->table("contacts");

			$builder->select("
					contacts.id_contact, 
					contacts.nom,
					contacts.prenom,
					contacts.nom_court_institution
					");
				
					$items=explode(" ",$search);
						
					$fieldSearchs=array(
						"contacts.nom",
						"contacts.prenom",
					
					);
					
					$builder->groupStart();
						foreach($items as $item):
							$builder->groupStart();
							foreach($fieldSearchs as $fieldSearch):
								$builder->orLike($fieldSearch,$item);
							endforeach;
							$builder->groupEnd();
						endforeach;
					$builder->groupEnd();

				return $builder->get()->getResult();
		}
		else
		{
			return null;
		}
	}


	public function insert_user($values)
	{
		$builder=$this->db->table("user_accounts");
		$builder->insert($values);

		return $this->db->insertId();
	}

}
