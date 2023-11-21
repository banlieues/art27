<?php
namespace Dashboard\Models;
use CodeIgniter\Model;

class Mdashboard extends Model{
 
    protected $table="user_table";
	protected $primaryKey = 'id_user_table';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	public function read_data($table,$field='*',$where=NULL,$lefts=NULL,$order=NULL)
	{
		$builder=$this->db->table($table);
		$builder->select($field);

        if(!is_null($where))
		    $builder->where($where);

        if(!is_null($lefts))
        {
            foreach($lefts as $left)
            {
                foreach($left as $k=>$l)
                {
                    $builder->join($k,$l,"left");
                }
            }
        }    
            

        if(!is_null($order))
            $builder->orderBy($order);

		return $builder->get()->getResult();
	}

	public function insert_data($data,$table)
	{
		$builder=$this->db->table($table);
		$builder->insert($data);

		return $this->db->insertID();

	}

    public function delete_data($table,$data)
    {
        $builder=$this->db->table($table);
        $builder->where($data);
        $builder->delete();
    }

	public function update_data($table,$data,$where)
	{
		$builder=$this->db->table($table);
		$builder->where($where);
		$builder->update($data);
	}


    public function query($query)
    {
        $this->db->query($query);
    }

    public function query_result($query)
    {
        $r=$this->db->query($query);

        //debug($r->getResult("object"));

        return $r->getResult("object");
    }

   function get_table_ajax($id,$postData=null){
    
       include(APPPATH."config/fh_descriptor.php");
     $response = array();

     ##Recuper la query
     $table="user_table";
     $result=$this->ban_crud_model->read_data($table,"*","id_user_table=$id");
     if(isset($result[0]->query)):
	$query=$result[0]->query;
	$fields_a=$result[0]->field;
	$fields=explode(",",$fields_a);
     else:
	 $query=NULL;
	 $query=NULL;
     endif;
     
     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
     $searchValue = $postData['search']['value']; // Search value

     ## Search 
     $searchQuery = "";
     if($searchValue != ''){
	 $searchQuery=" AND (";
	 $i=0;
	 foreach($fields as $field):
		if($i==0):$searchQuery .=" OR "; endif;
		$field_sql=$descriptor[$field]["field_sql"];
		$searchQuery .= "$field_sql like '%".$searchValue."%' ";
        //$searchQuery = " (emp_name like '%".$searchValue."%' or email like '%".$searchValue."%' or city like'%".$searchValue."%' ) ";
	     endforeach;
	 $searchQuery=" AND )";
	     
     }

     ## Total number of records without filtering
     $records=$this->db->query($query);
     $totalRecords=count($records->result());
     
     
//     $this->db->select('count(*) as allcount');
//     $records = $this->db->get('employees')->result();
//     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
     $records=$this->db->query($query.$searchQuery);
     $totalRecordwithFilter=count($records->result());
     
     
//     $this->db->select('count(*) as allcount');
//     if($searchQuery != '')
//        $this->db->where($searchQuery);
//     $records = $this->db->get('employees')->result();
//     $totalRecordwithFilter = $records[0]->allcount;

     ## Fetch records
//     $this->db->select('*');
//     if($searchQuery != '')
//        $this->db->where($searchQuery);
//     $this->db->order_by($columnName, $columnSortOrder);
//     $this->db->limit($rowperpage, $start);
//     $records = $this->db->get('employees')->result();

    $query_data=$query;
    if($searchValue != ''): $query_data.=$searchQuery; endif;
   $query_data.= "limit($rowperpage,$start)";
   $k=$this->db->query($query_data);
   $records=$k->result();
     
     $data = array();

     foreach($records as $record ){
	 foreach($fields as $field):
	     $label=$d[$field]["label"];
	     $field=$d[$field]["field_sql"];
	     $value[$label]=$record->field;
	 endforeach;
	 array_push($data,$value);
//        $data[] = array( 
//           "emp_name"=>$record->emp_name,
//           "email"=>$record->email,
//           "gender"=>$record->gender,
//           "salary"=>$record->salary,
//           "city"=>$record->city
//        ); 
     }

     ## Response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response; 
   }

    
    
    
}
