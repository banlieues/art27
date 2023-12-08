<?php

namespace DataQuery\Models;

use CodeIgniter\Model;

class dataQueryListModel extends Model
{
    protected $table=      "user_requete";
    protected $primaryKey = 'id_requete';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';

   
    
    public function list_queries($request,$orderBy=NULL,$orderDirection=NULL)
    {
       
        $this->select("id_requete,nom,uri,date_create,query,string_where,field,is_dasboard");
        if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=[
				"id_requete","nom"
			 ]
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
        return $this->paginate(50);
    }

	public function info_requete($id_requete)
	{
			//$this->select("user_requete.nom as nom_requete");
			//$this->join("user_accounts","user_accounts.id=user_requete.id_user","left");
			return $this->find($id_requete);
	}

	public function list_queries_all()
    {
       
		$builder=$this->db->table("user_table");
       
        return $builder->get()->getResult();
    }

	public function list_queries_all_import()
    {
       
		$builder=$this->db->table("user_requete");
       
        return $builder->get()->getResult();
    }

	public function set_is_dasboard($id_requete,$is_dasboard)
	{
		$builder=$this->db->table("user_requete");
		$builder->where("id_requete",$id_requete);
		$data["is_dasboard"]=$is_dasboard;

		$builder->update($data);
	}
   

	public function is_dasboard($id_requete)
	{
		$this->where("id_requete",$id_requete);
		
		$result=$this->get()->getRow();

		if(isset($result->is_dasboard))
		{
			return $result->is_dasboard;
		}
		else
		{
			return NULL;
		}
	}

  


   
    
}
