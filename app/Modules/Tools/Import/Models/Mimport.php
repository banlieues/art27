<?php
namespace Import\Models;

use CodeIgniter\Model;


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

		foreach($csv[0] as $label=>$value)
		{
			$fields[slugify_name_file($label,"_")]=['type' => 'TEXT'];
			$label_origine[]=slugify_name_file($label,"_");

		}

		

		$fields["ban_index_is_import"]=[
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
		];


		
		
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
					"name_fields_origin"=>implode(",",$label_origine)

			];


			$builder->insert($data_insert);



			//debug($csv);
			//je copie les données
			$data_insert=[];
			foreach($csv as $cs)
			{
				foreach($cs as $label=>$value)
				{
					$label=slugify_name_file($label,"_");
					$data_ligne[$label]=$value;
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


	public function getTableImportOnlyFieldCsv($name_temp)
	{
		$metadata=$this->getMetaDataTable($name_temp);

		if(!empty($metadata))
		{
			$name_fiels_origin=$metadata->name_fields_origin;

			$builder=$this->db->table($name_temp);
			$builder->select($name_fiels_origin);
			$builder->limit(5);
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

    
}
