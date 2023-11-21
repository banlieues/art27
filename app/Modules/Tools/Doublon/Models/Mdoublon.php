<?php
namespace Doublon\Models;

use CodeIgniter\Model;


class Mdoublon extends Model{

	protected $returnType     = 'object';
    
    function liste_doublon_by_critere($input,$entity,$descriptor,$descriptorEntities)
    {
		$fields=array();
		$groupby=array();

		//table
		
		$table_primary=$descriptorEntities[$entity]["table_primary"];
		$key_primary=$descriptorEntities[$entity]["key_primary"];
		

		$builder=$this->db->table($table_primary);
		
		array_push($fields,"COUNT(*) AS nbr_doublon");
		foreach($input as $index=>$value):
			if($value==1):
				$critere=$descriptor[$index]["field_sql"];
				array_push($fields,$critere);
				array_push($groupby,$critere);
				
				$builder->orGroupStart();
					$builder->where("$critere!=","");
					$builder->where("$critere!=","-");
				$builder->groupEnd();
			

			endif;
		endforeach;
		array_push($fields,"GROUP_CONCAT($table_primary.$key_primary) as id_entity");
		$select_string=implode(",",$fields);
		$group_by_string=implode(",",$groupby);


		
		$builder->select($select_string);
		$builder->groupBy($group_by_string);
		$builder->having("COUNT(*)>1");
		
		
		return $builder->get()->getResult();
  }

  	public function get_value_id_doublon($id_doublon, $indexes, $table_primary,$key_primary)
	{
		$builder=$this->db->table($table_primary);
		$list_fields=[];
		//debug($indexes); die();
		foreach($indexes as $descriptor)
		{
			foreach($descriptor as $index=>$des)
			{
				$list_fields[]=$des["field_sql"];
			}
			
			
		}
		$builder->select(implode(",",$list_fields));
		$builder->where($key_primary,$id_doublon);

		$results=$builder->get()->getRow();

		$results=json_decode(json_encode($results),true);

		return $results;
	}

	public function get_update_doublon($data_update,$key_primary,$table_primary)
	{
		$builder=$this->db->table($table_primary);
		$builder->where($key_primary,$data_update[$key_primary]);

		$builder->update($data_update);
	}

	public function update_tables_doublon($tables_doublon,$key_primary,$id_doublon,$id_doublon_garder)
	{
		foreach($tables_doublon as $table)
		{
			$builder=$this->db->table($table);
			$builder->where($key_primary,$id_doublon);
			$data_update[$key_primary]=$id_doublon_garder;
			$builder->update($data_update);
		}
	}
    
  
    
    
}
