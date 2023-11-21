<?php

namespace Components\Models;
use CodeIgniter\Model;

class BanCrudModel extends Model
{
	protected $table="contact";
	protected $primaryKey = 'id_contact';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object'; 
    
   public function query_direct($sql)
   {
       return $this->db->query($sql);
   }
   
   public function  read_data($table,$fields=NULL,$where=NULL,$left=NULL,$order=NULL,$group_by=NULL,$limit=NULL,$count=FALSE,$where_in=NULL,$where_not_in=NULL,$having=NULL)
   {
        $builder=$this->db->table($table);

        if(!is_null($fields)):
            $builder->select($fields,FALSE);
        else:
            $builder->select("*");
        endif;
        
    
        if(!is_null($left)):
              
            if(!is_array($left[0])):
            $builder->join($left[0],$left[1],"left");
            else:
              
                foreach($left as $k=>$v):
                    foreach($v as $ks=>$vs):
                        $builder->join($ks,$vs,"left");
                    endforeach;
                endforeach;
            endif;
            
        endif;
        
        if(!is_null($where)):
           if(!is_array($where)):
               $builder->where($where,NULL, FALSE);
           else:
                if(!is_array($where[0])):
                    $builder->where($where[0],$where[1]);
                else:
                    foreach($where as $k=>$v):
                        foreach($v as $ks=>$vs):
                            $builder->where($ks,$vs);
                        endforeach;
                    endforeach;
                endif;
          endif;  
        endif;
        
        if(!is_null($where_in)):
            $builder->whereIn($where_in[0],$where_in[1]);
        endif;
        
        if(!is_null($where_not_in)):
            $builder->whereNotIn($where_not_in[0],$where_not_in[1]);
        endif;
        
        
         if(!is_null($having)):
            $builder->having($having);
        endif;
        
        if(!is_null($order)):
            $builder->orderBy($order);
        endif;
        
        if(!is_null($group_by)):
            $builder->groupBy($group_by);
        endif;
        
        if(!is_null($limit)):
            if(is_array($limit)):
                $builder->limit($limit[0],$limit[1]);
            else:
                $builder->limit($limit);
            endif;

            
        endif;
        
        if($count):
            return $builder->countAllresults();
        else:
            
            return $builder->get()
                   ->getResult();
        endif;
   }

    public function insert_data($data,$table)
    {
        $builder=$this->db->table($table);
        $builder->insert($data);
        $id_insert=$this->db->insertID();
        return $id_insert;
    }

    public function  update_data($table,$data,$where)
    {
        $builder=$this->db->table($table);
        return $builder->update($data,$where);
    }


    public function  delete_data($table,$where)
    {
        $builder=$this->db->table($table);
        $builder->where($where);
       return  $builder->delete($where);;
    }

    public function is_exist($table,$champ,$value){
        $this->db->select(array($champ));
        $this->db->from($table);
        $this->db->where($champ,$value);
        return $this->db->get()
                ->result();
    }
   
   

}





