<?php namespace DataView\Libraries;


use CodeIgniter\Model;
use DataView\Models\DataViewConstructorModel;
use DataView\Libraries\DataViewConstructor;



class DataViewDelivre extends Model
{
    protected $path="DataView\Views\/";
    protected $dataViewConstructorModel;
    protected $dataView;
    protected $fields;



    public function __construct()
    {

        $this->dataViewConstructorModel = new DataViewConstructorModel();
        $this->dataView=new DataViewConstructor();
        $this->fields=$this->dataViewConstructorModel->getFields();
    }

    public function save_delivre_multiple($indexesForm,$data_insert_table,$table,$name_key_primary,$value_key_primary=0,$table_entity=NULL,$name_entity_key=NULL,$value_entity_primary=NULL)
    {
        //debugd($data_insert_table);
        //je dois récuperer les tours
        $name_total="count_total_$table";
        
        $total_multiple=$data_insert_table[$name_total];

        if($total_multiple>0)
        {
           
            $indexes=array_unique($this->dataView->get_index_for_component($indexesForm,$table));
        
            for($i=0;$i<$total_multiple;$i++)
            {
                $only_multiple_data_insert_table=[];
                foreach($indexes as $index)
                {
                    if(isset($data_insert_table[$index][$i]))
                    {
                        $only_multiple_data_insert_table[$index]=$data_insert_table[$index][$i];
                    }
                }
                
                $value_key_primary_en_cours=$data_insert_table[$name_key_primary][$i];

               /*debug($table);
                debug($name_key_primary);
                debug($value_key_primary_en_cours);
                debug($table_entity);
                debug($name_entity_key);
                debugd($value_entity_primary);*/


               $this->save_delivre(
                    $indexesForm,
                    $only_multiple_data_insert_table,
                    $table,
                    $name_key_primary,
                    $value_key_primary_en_cours,
                    $table_entity,
                    $name_entity_key,
                    $value_entity_primary);
            }

            //die();
        }
        else
        {
            return;
        }

        //recupere pour la table en cours
       

    
        


    }

    public function save_delivre($indexesForm,$data_insert_table,$table,$name_key_primary,$value_key_primary=0,$table_entity=NULL,$name_entity_key=NULL,$value_entity_primary=NULL)
    {
        //$table = où l'on doit inserer
        //$name_key_primary = nom de la clé primaire, 
        //$value_key_primary = valeur de la clé primaire, si 0, alors on insert
        //$table_entity = Nom de la table entity
        //$name_entity_key = nom de la clé de l'entité, si les donneées à inserer est dans une table 1-n avec table de l'entité, si NULL, alors on ajoute directement dans une table entity
        //$value_entity_primary = valeur de la clé précédente

        $fields=$this->fields;
        //On détermine si on est dans une entity ou dans une table dépendante
        if($table_entity==NULL||$table==$table_entity)
        {
            $is_entity=TRUE;
            $table_entity=$table;
            $value_entity_primary=$value_key_primary;
            $name_entity_key=$name_key_primary;
        }
        else
        {
            $is_entity=FALSE;
        }

        $data_insert_table_with_index=$this->dataView->prepareData($indexesForm,$data_insert_table,$fields,$table,true,false);
        $data_insert_table=$this->dataView->prepareData($indexesForm,$data_insert_table,$fields,$table,true);
      
        $db=db_connect();
        $builder=$db->table($table);
     
        if($value_key_primary>0)
        {
			//on update
            
			$data_changes=$this->dataViewConstructorModel->set_log_fiche($table_entity,$data_insert_table_with_index,$name_key_primary,$value_key_primary,$table,$table_entity);

			$data_insert_table["updated_at"]=date("Y-m-d H:i:s");
            $data_insert_table["updated_by"]=session()->get("loggedUserId");
			
            $builder->where($name_key_primary,$value_key_primary);
			unset($data_insert_table[$name_key_primary]);
            $builder->update($data_insert_table);
			
			
	
			$this->dataViewConstructorModel->set_logs_fiche_insert_bd($table_entity,$data_changes,date("Y-m-d H:i:s"),$value_entity_primary,$value_key_primary);

        }
        else
        {
            
            //On insert
			$data_insert_table["created_at"]=date("Y-m-d H:i:s");
			$data_insert_table["created_by"]=session()->get("loggedUserId");
            //$data_insert_table["id_type_personne"]=1;
            if(!$is_entity)
            {
                $data_insert_table[$name_entity_key]=$value_entity_primary;
            }

            //debugd($data_insert_table);
            $builder->insert($data_insert_table);
            $value_key_primary=$db->insertId();
            if($is_entity)
            {
                $value_entity_primary=$value_key_primary;
            }
			$data_changes=$this->dataViewConstructorModel->set_log_fiche_insert($data_insert_table_with_index);
			$this->dataViewConstructorModel->set_logs_fiche_insert_bd($table_entity,$data_changes,date("Y-m-d H:i:s"),$value_entity_primary,$value_key_primary);

        }
 
 
		 return $value_key_primary;
    }


} 