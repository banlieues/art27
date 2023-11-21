<?php

namespace DataView\Models;

use CodeIgniter\Model;

use DataView\Libraries\DataViewConstructor;
use Historique\Models\HistoriqueModel;
//use DataView\Libraries\DataViewConstructor;

class dataViewConstructorModel extends Model
{
    protected $returnType     = 'object';

    protected $entities=["contact", "contact_profil"];
    protected $excludeField=["@#<hr>"];

    public $exclude_field_sql=["sqlsql"];

    public function __construct()
    {
        parent::__construct();
        $this->request = \Config\Services::request();
    }

    public function setQuerySearch($q, $fieldsSearch)
    {
        $itemSearch = $this->request->getVar("itemSearch");

        if($itemSearch && !empty(trim($itemSearch))) :
            $items = explode(" ", $itemSearch);

            $q->groupStart();
                foreach($items as $item):
                    $q->groupStart();
                    foreach($fieldsSearch as $fieldSearch):
                        $q->orLike($fieldSearch, $item);
                    endforeach;
                    $q->groupEnd();
                endforeach;
            $q->groupEnd();
        endif;

        return $q;
    }

    public function getDescriptor()
    {
        $builder=$this->db->table("ban_fields");
        $builder->orderBy("label");
        $fields=$builder->get()->getResult();
        
        foreach($fields as $field)
        {
            foreach($field as $k=>$v)
            {
                    $data[$field->type][$field->field_index][$k]=$v;
            }
        }
    
        return $data;
    }

    public function getDescriptorBrut()
    {
        $builder=$this->db->table("ban_fields");
        $builder->orderBy("label");
        $fields=$builder->get()->getResult();
        
        foreach($fields as $field)
        {
            foreach($field as $k=>$v)
            {
                    $data[$field->field_index][$k]=$v;
            }
        }
    
        return $data;
    }

    public function get_list_entity()
    {
        $list_entity=[];
        $builder=$this->db->table("ban_entities_params");
        $entities=$builder->get()->getResult();

        foreach($entities as $entity)
        {
            $list_entity[]=$entity->type;
        }

        return $list_entity;
    }
   

    public function getDescriptorIndexByFields()
    {
        $builder=$this->db->table("ban_fields");
        $builder->orderBy("label");
        $fields=$builder->get()->getResult();
        
        foreach($fields as $field)
        {
            foreach($field as $k=>$v)
            {
                    $data[$field->field_index][$k]=$v;
            }
        }
        
        return $data;
    }

    public function liste_key_primary()
    {
        $builder=$this->db->table("ban_entities_params");
       
        $entities=$builder->get()->getResult();
        
        foreach($entities as $entity)
        {
            $data[]=$entity->key_primary;
        }
        
        return $data;
    }


    public function inputs_liste_key_primary($vtcc,$type=NULL)
    {
        $inputs=NULL;

        if(!is_null($type))
        {
                $metadata=$this->getOneEntities($type);
           
            if(isset($metadata->key_possible))
            {
                $key_possible=$metadata->key_possible;
                $liste_key_primary=explode(",",$key_possible);

                

                foreach($liste_key_primary as $key_p)
                {
                    if(isset($vtcc->$key_p))
                    {
                        $val=$vtcc->$key_p;
                        $inputs.="<input class='one_key_primary' type='hidden' value='".$val."' name='$key_p'>";
                        
                    }
                    else
                    {
                        $inputs.="<input class='one_key_primary' type='hidden' value='0' name='$key_p'>";

                    }

                
                }
            }
                
              
        }
            
        return $inputs;
    }


    public function getDescriptorEntity()
    {
        $builder=$this->db->table("ban_entities_params");
        $builder->orderBy("type");
        $entities=$builder->get()->getResult();

        foreach($entities as $entity)
        {
            foreach($entity as $k=>$v)
            {
                $data[$entity->type][$k]=$v;
            }
        }

        return $data;
    }


    public function getValueIndex($index,$id_entity)
    {
        //On récupere le descriptor
        $metadata=$this->getOneField($index);
        
        $type=$metadata->type;

        $table=$metadata->table;
        $field_sql=$metadata->field_sql;
        $key_primary=$this->getKeyPrimary($type);
        
        $builder=$this->db->table($table);

        $builder->select($field_sql);

        $builder->where($key_primary,$id_entity);

        $result=$builder->get()->getResult();

        if(!empty($result))
        {
            if(count($result)==1)
            {
                return $result[0]->$field_sql;
            }
            else
            {
                return $result;
            }
        }
        else
        {
            return NULL;
        }

        

        //$builder->db->table($metadata);

    }

    public function getDescriptorIndexByType($type)
    {
        $builder=$this->db->table("ban_fields");
        $builder->orderBy("field_index");
        $builder->where("type",$type);
        $fields=$builder->get()->getResult();
        
        foreach($fields as $field)
        {
            foreach($field as $k=>$v)
            {
                    $data[$field->field_index][$k]=$v;
            }
        }
        
        return $data;
    }


    public function getIndexField($type)
    {
        $data=[];
        $builder=$this->db->table("ban_fields");
        $builder->where("type",$type);

        $indexes=$builder->get()->getResult();
        
        foreach($indexes as $index)
        {
            
                   array_push($data,$index->field_index);
            
        }
        
        return $data;

    }

    public function getFieldsWithIndex($type,$is_exclude_field_sql=false)
    {
        $data=[];
        $builder=$this->db->table("ban_fields");
        $builder->where("type",$type);

        $indexes=$builder->get()->getResult();
        
        foreach($indexes as $index)
        {
                if($is_exclude_field_sql)
                {
                    if(!in_array($index->field_sql,$this->exclude_field_sql))
                    {
                        $data[$index->field_index]=$index->field_sql;
                    }
                }
                else
                {
                    $data[$index->field_index]=$index->field_sql;
                }
                   
            
        }
        
        return $data;
    }


    public function getIndexComponents($type,$only_field_sql=true)
    {
        $indexes=[];
        $table="ban_components_".$type."_fields_index";
        $builder=$this->db->table($table);
        $builder->orderBy("id_component_field_index");

        $components=$builder->get()->getResult();
       
        if($only_field_sql)
        {
            foreach($components as $component)
            {
                if(strpos($component->field_index, "@#") !== false)
                {
                    
                }
                else{
                    array_push($indexes,$component->field_index);
                }
            }
        }
        else
        {
            foreach($components as $component)
            {
                    array_push($indexes,$component);
            }
        }

        return $indexes;
       
    }


    public function getFieldsIndexOrdered($type,$mise_en_evidence=NULL)
    {
        $fields=$this->getDescriptorIndexByType($type);
        $indexes_all=[];

		foreach($fields as $index=>$descriptor)
		{
			array_push($indexes_all,$index);
		}


        $indexComponents=$this->getIndexComponents($type);



        $indexDiff=array_diff($indexes_all,$indexComponents);

        if(!empty($indexDiff))
            $indexComponents= array_merge($indexComponents,$indexDiff);

        if(!is_null($mise_en_evidence)) 
        {
            $indexComponents=array_diff($indexComponents,$mise_en_evidence);

            $indexComponents=array_merge($mise_en_evidence,$indexComponents);
        }   

        return $indexComponents;    
    }




    public function getSubstractField($indexes,$type_substract)
    {
        $indexes_substract=$this->getIndexField($type_substract);

        return array_diff($indexes,$indexes_substract);

    }


    public function getSubstractFieldWithValues($indexes,$type_substract,$values)
    {
        //ici on retire si et seulement si les values sont vides

        $indexes_substract=$this->getIndexField($type_substract);
        $indexes_substract_with_field=$this->getFieldsWithIndex($type_substract);
        $indexes_empty=array();
    

       //debug($values);
        foreach($indexes_substract_with_field as $index=>$field)
        {
            if(isset($values->$field)&&empty($values->$field))
            {
                array_push($indexes_empty,$index);
            }
        }
        
        //debug($indexes_empty,true);
        $indexes_substract_final=array_diff($indexes_substract,$indexes_empty);

        return array_diff($indexes,$indexes_substract_final);

    }


    public function search_index($item)
    {
        $builder=$this->db->table("ban_fields");
        $builder->where("field_index",trim($item));
        $builder->orWhere("field_sql",trim($item));
        $builder->orWhere("label",trim($item));

        $result=$builder->get()->getRow();
        
        if(!empty($result))
            return $result->field_index;

        $builder=$this->db->table("ban_fields");
        $builder->like("field_index",trim($item));
        $builder->orLike("field_sql",trim($item));
        $builder->orLike("label",trim($item));

        $result=$builder->get()->getRow();
        
        if(!empty($result))
            return $result->field_index;


        $items=explode(" ",$item); 
        
        foreach($items as $item)
        {
            $builder->where("field_index",trim($item));
            $builder->orWhere("field_sql",trim($item));
            $builder->orWhere("label",trim($item));
    
            $result=$builder->get()->getRow();
            
            if(!empty($result))
                return $result->field_index;
    
            $builder=$this->db->table("ban_fields");
            $builder->like("field_index",trim($item));
            $builder->orLike("field_sql",trim($item));
            $builder->orLike("label",trim($item));
    
            $result=$builder->get()->getRow();
            
            if(!empty($result))
                return $result->field_index;
        }

        return null;    
    }

    public function getEntities()
    {
        $builder=$this->db->table("ban_entities_params");
        $builder->orderBy('rank');
        return $builder->get()->getResult();
    }

    public function getOneEntities($type)
    {
        $builder=$this->db->table("ban_entities_params");
        $builder->where('type',$type);
        return $builder->get()->getRow();
    }

    public function getKeyEntities($type)
    {
        $key=$this->getOneEntities($type);
        return $key->key_primary;
    }

    public function getTableEntities($type)
    {
        $table=$this->getOneEntities($type);
        return $table->table_primary;
    }

    public function getKeyPrimary($type)
    {
        $table=$this->getOneEntities($type);
        return $table->key_primary;
    }

    public function getOneField($index)
    {
        $builder=$this->db->table("ban_fields");
        $builder->where("field_index",$index);
        return $builder->get()->getRow();
    }

    public function getFieldsArray($type,$is_only_actif=FALSE)
    {
        $builder=$this->db->table("ban_fields");
        $builder->where("type",$type);
        if($is_only_actif)
        {
            $builder->where("is_actif",1);
        }
        $builder->orderBy("label");
        return $builder->get()->getResult();
    }

    public function getListField($field)
    {
        $builder=$this->db->table($field->table_list);

        $builder->select("*,$field->key_list as id, $field->label_list as label ");

        if(!is_null($field->order_list))
        {
            $builder->orderBy($field->order_list);
        }
        return $builder->get()->getResult();

    }

    public function getListTypeFields()
    {
        $builder=$this->db->table("ban_list_type_field");
        $builder->where("is_actif",1);
        $builder->orderBy("rank");

        return $builder->get()->getResult();
    }

    public function getLabelTypeField($ref)
    {
        $builder=$this->db->table("ban_list_type_field");
        $builder->where("ref",$ref);
        $result=$builder->get()->getRow();

        if(!empty($result))
            return $result->label;

            return "Label non défini";
    }

    public function getFields()
    {
        $builder=$this->db->table("ban_fields");
        $fields=$builder->get()->getResult();
        
        foreach($fields as $field)
        {
            foreach($field as $k=>$v)
            {
                if($k!="field_index")
                {
                    $data[$field->field_index][$k]=$v;
                } 
            }
        }
        
        $data["key_entity"]["inscriptions"]="id_inscription";

        if(isset($data))
        {
            $builder=$this->db->table("ban_entities_params");
            $params=$builder->get()->getResult();

            $data["table_foreign"]			=  [
                "contact"=>["keyEntity"=>"id_contact","keyForeign"=>"id_contact"],
                "activities"=>["keyEntity"=>"id_activity","keyForeign"=>"id_activity"],
            ];

           // $keys_entity=[];
            foreach($params as $param)
            {
                $data["key_entity"][$param->type]=$param->key_primary;
                	//array_push($keys_entity,array($param->type => $param->key_primary));


                   
            }
            //$data["key_entity"]=$keys_entity;

           /* $data["key_entity"] 		=[
                "contact"=>"id_contact",
                "activities"=>"id_activity",
                "inscriptions"=>"id_inscription",
                
            ];	*/
           

            return $data;
        }
        return NULL;
    }

    public function getComponents($name)
    {
        $builder=$this->db->table("ban_components_$name");
        $builder->orderBy("column,rank");

        return $builder->get()->getResult();
    }

    public function getOneComponent($name,$type)
    {
        $builder=$this->db->table("ban_components_$name");

        $builder->where("type",$type);

        return $builder->get()->getRow();
    }


    public function setComponents($name,$posts)
    {
        $builder=$this->db->table("ban_components_$name");
        $ncol1=1;
        $ncol2=1;

        foreach($posts as $k=>$v)
        {
            if(stristr($k,"@order@")===false)
            {
                continue;
            }
            else
            {
                $descriptor=explode("@order@",$k);
                $where["id_components"]=$descriptor[1];
                $data["fields"]=$v;
                $colIndex=str_replace("colIndex",NULL,$descriptor[0]);
                $data["column"]=$colIndex;

                switch($colIndex)
                {
                    case 1:
                        $data["rank"]=$ncol1;
                        $ncol1=$ncol1+1;
                        break;
                        
                    case 2: 
                        $data["rank"]=$ncol2;
                        $ncol2=$ncol2+1;
                        break;

                }

                // if($colIndex==1)
                // {
                //     $data["rank"]=$ncol1;
                //     $ncol1=$ncol1+1;
                // }

                // if($colIndex==2)
                // {
                //     $data["rank"]=$ncol2;
                //     $ncol2=$ncol2+1;
                // }

                
                $builder->update($data,$where);

                $this->treatComponentsFieldIndex("ban_components_$name",$descriptor[1]);
            }
        }
        
    }

    public function getFieldsGestion($type)
    {
        $builder=$this->db->table("ban_fields");
        $builder->where("type",$type);
        $builder->orderBy("label");

        return $builder->get()->getResult();

    }

    public function getfieldsSelected($type,$entity)
    {
        $fieldsSelected=[];
        $builder=$this->db->table("ban_components_$entity");
        $builder->select("fields");
        $builder->where("type",$type);
        $fields=$builder->get()->getResult();
        if(!empty($fields))
        {
            foreach($fields as $field)
            {
                $fieldsArray=explode(",",$field->fields);
                foreach($fieldsArray as $fieldArray)
                {
                    array_push($fieldsSelected,$fieldArray);
                }

            }
        }

        return $fieldsSelected;
    }

    
 

    public function getSelect($field,$value)
    {
        //debug($field);
      
        $label=contruct_label_sql($field["label_list"]);
        
        $builder=$this->db->table($field["table_list"]);


       
        

        $builder->select($field["key_list"]." as key, ".$label." as label ");
        //if(isset($field["actif"]))

        if ($this->db->fieldExists('is_actif', $field["table_list"])) {
    // some code...

            $builder->orWhere("is_actif",1);
        }
        //if(!empty($value))
            $builder->orWhere($field["key_list"],$value);
        if(isset($field["order_list"]))
            $builder->orderBy($field["order_list"],1);    

        //debug($builder->get()->getResult(),true);
        return $builder->get()->getResult();
    }

    public function getSelectValue($field,$value)
    {
        $builder=$this->db->table($field["table_list"]);
        $builder->select($field["key_list"]." as key, ".$field["label_list"]." as label ");
        $builder->where($field["key_list"],$value);
        $result=$builder->get()->getRow();
        if(!empty($result))
        {
            return $result->label;
        }

        return NULL;
    }

    public function getCheck($field,$value)
    {
        $builder=$this->db->table($field["table_list"]);
        $builder->select($field["key_list"]." as key, ".$field["label_list"]." as label ");

        //if(isset($field["actif"]))
            $builder->orWhere("is_actif",1);

        if(!empty($value))
        {
            //$builder->groupStart();
            //debug($value);
            if(!is_array($value))
            {
                foreach(explode(",",$value) as $v)
                {
                    $builder->orWhere($field["key_list"],$v);
                } 
            }
            else
            {
                foreach($value as $v)
                {
                    $builder->orWhere($field["key_list"],$v);
                } 
            }
            //$builder->groupEnd();   
        }
        if(isset($field["order_list"]))
            $builder->orderBy($field["order_list"],1);    

        return $builder->get()->getResult();
    }



    public function getCheckValue($field,$value)
    {
        
        $builder=$this->db->table($field["table_list"]);
        $builder->select($field["key_list"]." as key, ".$field["label_list"]." as label ");
        if(isset($field["actif_list"]))
            
        if(isset($field["order_list"]))
            $builder->orderBy($field["order_list"]);    

        $results=$builder->get()->getResult();
       
        if(!empty($results))
        {
            $traduction=[];
            $valuesPossible=explode(",",$value);
            foreach($results as $result)
            {
                if(in_array($result->key,$valuesPossible))
                {
                    $traduction[]=$result->label;
                }
            }

            return implode("<br>",$traduction);
        }

        return NULL;
    }

    public function verifExistFields($index,$field,$table)
	{
        $forge = \Config\Database::forge();
		if (!$this->db->fieldExists($index, $table))
        {
            $fields = [
                $index => ['type' => 'TEXT','null'=>TRUE]
        ];

        //$forge->addColumn($table, $fields);
        }
	}

    public function nullable($index,$table)
    {
        $is_nullable=FALSE;
        $fields = $this->db->getFieldData($table);

        foreach ($fields as $field)
        {
            if($field->name==$index&&$field->nullable==1)
            {
                $is_nullable=TRUE;
            }
        }

        return $is_nullable;
    }

  

    public function saveData($indexes,$table,$datasForSave,$fields,$id_entity,$id_encours)
    {

      //   debug($indexes);
    //// debug($table);
       // debug($datasForSave);
        // debug($fields);
        //debug($id_entity);
        //die();

        $dataView = new DataViewConstructor(); 

        $descriptor_indexes=$this->getDescriptorIndexByFields();
        $descriptor_entities=$this->getDescriptorEntity(); //obtenir la liste des entity présente

        $liste_key_primary=$this->liste_key_primary();
        
        $is_update=FALSE;

        
        
        $save_indexes=[];
        $save_entities=[];
        $save_value=[];
        $save_key_primary=[];
        
        $indexes_form=$datasForSave["indexesForm"];

       
      
        

    /*

        1. Au départ, on collecte les données que l'on a
        On boucle sur l'ensemble des entity pour connaitre leur caractéristique, les values, les données
        
    */
    
       
  

        foreach($datasForSave as $index=>$value)
        {
            
            if($index!="indexesForm")
            {
            
                if(isset($descriptor_indexes[$index])&&!in_array($index,$liste_key_primary))
                {
                    //On retire les key

                    $save_indexes[$index]=$descriptor_indexes[$index];

                    $save_value[$index]=$value;

                    // tamo modif
                    $entity=$save_indexes[$index]["type"];
                    // $entity=$save_indexes[$index]["table"];

                    $save_entities[$entity]=$descriptor_entities[$entity];

                    if(!in_array($index,$indexes))
                        array_push($indexes,$index);
                   
                }
                else
                {
                    $save_value[$index]=$value;

                    if(in_array($index,$liste_key_primary))
                    {
                        $entity=str_replace("id_",NULL,$index);
                        $save_entities[$entity]=$descriptor_entities[$entity];
                    }
                  
                }
            }
        }
        
       /* debug($indexes);
        debug($save_entities);
        debug($save_indexes);
        debug($save_value);
        debug($save_key_primary);
        die();*/
   
        $profil_relation=[];
        //debug($save_entitie);
        $ent_treat=[];
      
        //1 on fait les insert afin de produire les id manquants
        foreach($save_entities as $ent=>$save_entitie)
        {
            $data_insert=[];
            $is_insert=FALSE;

            
       // debug($datasForSave);
            $prepare_datas = $dataView->prepareData($indexes,$datasForSave,$fields,$save_entitie["table_primary"]);
         //debug($prepare_datas);

        
             //je regarde les joitnrures
            if(!empty($save_entitie["jointure"]))
            {
                    $explode=explode(",",$save_entitie["jointure"]);
                    if(!empty($explode))
                    {
                        foreach($explode as $ex)
                        {
                            $explode_for_table_detection=explode(".",$ex);

                            if(!empty($explode_for_table_detection[0]&&$explode_for_table_detection[1]))
                            {
                                // $profil_relation[]=[$explode_for_table_detection[0]=>$explode_for_table_detection[1]];
                                $profil_relation[$explode_for_table_detection[0]][]=$explode_for_table_detection[1];
                                //On met à jour

                            }

                        }
                    }
            }
           //debug( $prepare_datas);
          // die();
           // 

            if(empty($save_value[$save_entitie["key_primary"]]))
            {
      
               
              if(isset($profil_relation[$ent]))
                {
                    
                    foreach($profil_relation[$ent] as $index_relation)
                    {
                       // echo $index_relation;
                        //echo '<br>';
                            if(isset($save_value[$index_relation]))
                            {
                                $data_insert[$index_relation]=$save_value[$index_relation];
                                $is_insert=TRUE;

                                
                            }
                            
                       
                      
                    }
                }
                
          
                //On compare les deux
                if(isset($prepare_datas[$ent]))
                {
                    foreach($prepare_datas[$ent] as $prepare_data)
                    {
                        foreach($prepare_data as $field_sql=>$val )
                    //echo $old_value->$field_sql; echo "="; echo $val;
                    if(isset($val))
                    {
                        $is_insert=TRUE;
                        $data_insert[$field_sql]=$val;
                        //debug($data_update);
                    }
                           
                    }
                }

               // debug($data_insert);

               // debug($prepare_datas);
              
                //debug($old_value);

                if($is_insert)
                {
                    $builder=$this->db->table($save_entitie["table_primary"]);
                    //$builder->where($save_entitie["key_primary"],$save_value[$save_entitie["key_primary"]]);
                    $builder->insert($data_insert);
                    $save_value[$save_entitie["key_primary"]]=$this->db->insertId();
                    array_push($ent_treat,$ent);

                    if($save_entitie["key_primary"]==$id_encours)
                    {
                        $id_entity=$save_value[$save_entitie["key_primary"]];
                    }

                }
                else{
                    //echo "je n'insert pas";
                }
              // debug($data_update);
            }
        }
      

        


        //2ensuie, on fait les updates
        foreach($save_entities as $ent=>$save_entitie)
        {
            $data_update=[];
            $is_update=FALSE;

            $prepare_datas = $dataView->prepareData($indexes,$datasForSave,$fields,$save_entitie["table_primary"]);

             //je regarde les joitnrures
             if(!empty($save_entitie["jointure"]))
            {
                    $explode=explode(",",$save_entitie["jointure"]);
                    if(!empty($explode))
                    {
                        foreach($explode as $ex)
                        {
                            $explode_for_table_detection=explode(".",$ex);

                            if(!empty($explode_for_table_detection[0]&&$explode_for_table_detection[1]))
                            {
                                // $profil_relation[]=[$explode_for_table_detection[0]=>$explode_for_table_detection[1]];
                                $profil_relation[$explode_for_table_detection[0]][]=$explode_for_table_detection[1];
                                //On met à jour

                            }

                        }
                    }
            }
       
        
           // 1. On s'occupe de mettre à jour les valeurs ou de les inserer
            if(!empty($save_value[$save_entitie["key_primary"]]))
            {
               
                //On récupérer la table concernée
                $builder=$this->db->table($save_entitie["table_primary"]);

                //On crée le where

                $builder->where($save_entitie["key_primary"],$save_value[$save_entitie["key_primary"]]);

                //On récupère les données de la ligne

                $old_value=$builder->get()->getRow();

               //debug($old_value);
                //On va vérifier si les id sont toujours les mêmes
               
                if(isset($profil_relation[$ent]))
                {
                    
                    foreach($profil_relation[$ent] as $index_relation)
                    {
                       // echo $index_relation;
                        //echo '<br>';
                      
                        if(
                            isset($save_value[$index_relation])
                            &&isset($old_value->$index_relation)
                            &&$old_value->$index_relation!=$save_value[$index_relation])
                        {
                            $data_update[$index_relation]=$save_value[$index_relation];
                            $is_update=TRUE;
                        }
                      
                    }
                }
                
             
                //On compare les deux
                if(isset($prepare_datas[$ent]))
                {

               
                    foreach($prepare_datas[$ent] as $prepare_data)
                    {
                        foreach($prepare_data as $field_sql=>$val )
                    //echo $old_value->$field_sql; echo "="; echo $val;
                    
                        
                        if($val!==$old_value->$field_sql)
                        {
                            $is_update=TRUE;

                        

                            $data_update[$field_sql]=$val;

                            //debug($data_update);
                        }
                    }
                }

              /*  debug($data_update);

                debug($prepare_datas);*/
              
                //debug($old_value);

                if($is_update)
                {
                    $builder=$this->db->table($save_entitie["table_primary"]);
                    $builder->where($save_entitie["key_primary"],$save_value[$save_entitie["key_primary"]]);
                    $builder->update($data_update);
                }
                else{
                   // echo "je n'update pas";
                }

              //  echo "<h4>Je mets à jour pour ".$save_entitie["table_primary"]."</h4>";
             //debug($data_update);
            }
        }
      
     //die();
     



        return $id_entity;
    }

    public function saveData_old($table,$datasForSave,$fields,$id_entity)
    {
        //Treat data for table principal
        //debug($datasForSave,true);
        $historiqueModel=new HistoriqueModel();

        
    
       if($id_entity>0)
       {
            
            $is_change_date_suivi=FALSE;
            

            $historique=[];//on va stocker pour hisoricisation les nouvelles valeurs dans cette table
           
            $builder=$this->db->table($table);
            $where[$fields["key_entity"][$table]]=$id_entity;

            //Je dois connaitre les valeurs avant soumission
            $old_value=$builder->where($where)->get()->getRow();

            //debug($old_values,true);

            foreach($datasForSave[$table] as $indexes)
            {
                foreach($indexes as $index=>$value)
                {
                    if($index=="id_activite_registration"&&$value>0)
                    {
                        $data["id_activity"]=$value;
                        $id_activite_registration=$value;
                    }

                    elseif($index=="id_contact_registration"&&$value>0)
                    {
                        $data["id_contact"]=$value;
                        $id_contact_registration=$value;
                    }
                 
                    else
                    {
                        
                        $data[$index]=$value;

                         //ici on historicise, mais comment?

                        //POur cemea, il faut connaîtte l'id_inscription, car on historise que statut et statut payement
                        //On doit traquer les deux index et savoir si on est en contexte d'inscription, faut verifier et dans la table de dpart
                        // et ci-dessous, dans une table en-dessous
                        
                        if($old_value->$index!=$value&&$index!="remarques_gestion")
                        {
                           if($table=="inscriptions")
                            {

                                if($table=="inscriptions"&&$index=="statutsuivi")
                                {
                                    $is_change_date_suivi=TRUE;
                                }


                              

                                //debug($table); debug($index); 
                                //on injecte dans l'historicisation, les valeurs de l'index en cours pour réaliser l'historicisation
                            // $old_value=NULL;
                                //if($value!=$old_value)
                                //{
                                    $historique_index["id_entity"]=$id_entity;
                                    $historique_index["type"]="index_db";
                                    $historique_index["index"]=$index;
                                    $historique_index["new_value"]=$value;
                                    if(!empty($old_value))
                                    {
                                        $historique_index["old_value"]=$old_value->$index;
                                    }
                                    else
                                    {
                                        $historique_index["old_value"]=NULL;
                                    }
                                   

                                    array_push($historique,$historique_index);
                            // }

                                
                            
                            }
                        }
                    }

                    

                    
                }
            }
            //debug($historique,true);
            if($is_change_date_suivi&&$table=="inscriptions")
            {
                $data["date_suivi"]=date("Y-m-d");
                $data["heure_suivi"]=date("H-i-s");
            }

            //on regle le payement terminé
            if($table=="inscriptions"&&isset($data["statut_payement"])&&$data["statut_payement"]==3)
            {
                $old_statut_suivi=$this-> getValueIndex('statutsuivi',$id_entity);
                if($old_statut_suivi!="I")
                {
                    $data["statutsuivi"]="I";
                    $data["date_suivi"]=date("Y-m-d");
                    $data["heure_suivi"]=date("H-i-s");

                    $historique_index["id_entity"]=$id_entity;
                    $historique_index["type"]="index_db";
                    $historique_index["index"]='statutsuivi';
                    $historique_index["new_value"]="I";
                    $historique_index["old_value"]=$old_statut_suivi;

                    array_push($historique,$historique_index);
                }

            }

            $builder->update($data,$where);



           
            if(!empty($historique))
            {
                foreach($historique as $historique_index)
                {
                  
                    $historiqueModel->set_remarque_gestion_inscription
                    (
                        $historique_index["id_entity"],
                        $historique_index["type"],
                        $historique_index["index"],
                        $historique_index["new_value"],
                        $historique_index["old_value"]
                    );
                }
            }


            $builder->where($where);

            //recup data from table entity
            $valueEntity=$builder->get()->getResult();

           
            
       }
       else
       {    
            $builder=$this->db->table($table);

            foreach($datasForSave[$table] as $indexes)
            {
                foreach($indexes as $index=>$value)
                {
                    if($index=="id_activite_registration"&&$value>0)
                    {
                        $data["id_activity"]=$value;
                        $id_activite_registration=$value;
                    }

                    elseif($index=="id_contact_registration"&&$value>0)
                    {
                        $data["id_contact"]=$value;
                        $id_contact_registration=$value;
                    }
                    
                    else
                    {
                        $data[$index]=$value;
                    }
                }
            }
            $builder->insert($data);
            $id_entity=$this->db->insertID();
            
       }


       //Case of registration| suite
       //Table
       switch($table)
       {
            case "inscriptions":
                    //contexte d'update
                    $builder=$this->db->table("contact"); 
                    if(isset($id_contact_registration))
                    {
                        if(isset($datasForSave["contact"])&&!empty($datasForSave["contact"]))
                        {
                            $where_update_contact["id_contact"]=$id_contact_registration;
                            foreach($datasForSave["contact"] as $indexes)
                            {
                                foreach($indexes as $index=>$value)
                                {
                                    $data_update_contact[$index]=$value;
                                    
                                }
                            }
                            $builder->update($data_update_contact,$where_update_contact);
                        }
                        
                       
                    }
                    else
                    {
                        if(isset($datasForSave["contact"])&&!empty($datasForSave["contact"]))
                        {
                            foreach($datasForSave["contact"] as $indexes)
                            {
                                foreach($indexes as $index=>$value)
                                {
                                    $data_insert_contact[$index]=$value;
                                    
                                }
                            }
                        
                            $builder->insert($data_insert_contact);
                            $id_new_contact=$this->db->insertId();
                            //On ajoute id_contact
                            
                            $builder=$this->db->table("inscriptions"); 

                            $where_update_new_registration["id_inscription"]=$id_entity;
                            $data_update_new_registration["id_contact"]=$id_new_contact;

                            $builder->update($data_update_new_registration,$where_update_new_registration);

                        
                            $id_contact_registration=$id_new_contact;
                        }
                    }
               
            break;

          
       }

       //case of user doit être ajouté
       //user n'existe pas alors on crée un compte
       //et on récupérer l'id_user
       if(isset($datasForSave["user_contacts"]))
       {
           //debug($datasForSave["user_contacts"]);
            foreach($datasForSave["user_contacts"] as $indexes)
            {
                foreach($indexes as $index=>$value)
                {
                    if($index=="id_user_registration")
                    {
                       
                        $id_user_registration=$value;
                    }
                    
                }
            }

            //debug( $id_user_registration);
            //debug($id_contact_registration);


            

            if(isset($id_user_registration)&&$id_user_registration>0&&isset($id_contact_registration))
            {
               /* debug($id_user_registration);
                debug($id_contact_registration);
                die();*/
                //on vérifie si la personne gere ou pas le compte

                    $builder=$this->db->table("user_contacts"); 
                    $builder->where("id_user",$id_user_registration);
                    $builder->where("id_contact",$id_contact_registration);
                    $result=$builder->get()->getRow();
                    //debug($result,true);
                    if(empty($result))
                    {
                        $data_insert_user_contact["id_user"]=$id_user_registration;
                        $data_insert_user_contact["id_contact"]=$id_contact_registration;

                        $builder=$this->db->table("user_contacts"); 
                        $builder->insert($data_insert_user_contact);
                    }
            }


        }    





       //treat data for table foreign
       /*foreach($datasForSave as $tableForeign=>$datasForForeign)
       {
            $dataForeign=[];
            $whereForeign=[];
            if($tableForeign!=$table)
            {
                //if update or insert
                $builder=$this->db->table($table);
                $where[$fields["key_entity"][$table]]=$id_entity;

                //Data form table_foreign
                $descriptorForeign=$fields["table_foreign"][$tableForeign];

                //Key primary of table foreign
                $keyForeign=$descriptorForeign["keyForeign"];

                //Key entity for relation with table foreign
                $keyEntity=$descriptorForeign["keyEntity"];

            
                foreach($datasForSave[$tableForeign] as $indexes)
                {
                    foreach($indexes as $index=>$value)
                    {
                        $dataForeign[$index]=$value;
                    }
                }

                //If exist keyForeign and value>0 
                // or if table entity has value for keyForeign of table foreign
                //then update

                if(isset($dataForeign[$keyForeign])&&($dataForeign[$keyForeign]>0)) 
                {
                    $builder=$this->db->table($tableForeign);
                    $whereForeign[$keyForeign]=$dataForeign[$keyForeign];
                    $valuesForeignEntity=$builder->where($whereForeign);
                    if(!empty($valuesForeignEntity))
                    {
                        $buider->update($dataForeign,$whereForeign);
                        $idKeyForeignValue=$dataForeign[$keyForeign];
                    }
                    else
                    {
                        $builder->insert($dataForeign);
                        $idKeyForeignValue=$this->db->insertID();
                    }
                }

                elseif(isset($valueEntity[0]->$keyEntity)&&$valueEntity[0]->$keyEntity>0)
                {
                    $builder=$this->db->table($tableForeign);
                    $whereForeign[$keyForeign]=$valueEntity[0]->$keyEntity;
                    $valuesForeignEntity=$builder->where($whereForeign);
                    if(!empty($valuesForeignEntity))
                    {
                        $builder->update($dataForeign,$whereForeign);
                        $idKeyForeignValue=$valueEntity[0]->$keyEntity;
                    }
                    else
                    {
                        $builder->insert($dataForeign);
                        $idKeyForeignValue=$this->db->insertID();
                    }
                }

                else
                {
                    $builder=$this->db->table($tableForeign);
                    $builder->insert($dataForeign);
                    $idKeyForeignValue=$this->db->insertID();
                }

                $builder=$this->db->table($table);
                $whereI[$fields["key_entity"][$table]]=$id_entity;
                $dataI[$keyForeign]=$idKeyForeignValue;
                $builder->update($dataI,$whereI);


            }
       }*/

       return $id_entity;
        
    }

    public function saveMetaData($getVar,$total_item=NULL)
    {
       
        if($getVar["mode"]=="update")
        {
            $builder=$this->db->table("ban_fields");
            $data_update["label"]=trim($getVar["label"]);
            $data_update["rule"]=$this->saveRules($getVar);
            $where_update["field_index"]=$getVar["field_index"];
            $builder->update($data_update,$where_update);

            //2. On crée le champ dans les tables
            //2.1. Déterminer la table liée à l'entité en cours
            
        }
        else
        {
            $builder=$this->db->table("ban_fields");
            $data_insert["field_index"]=trim(str_replace("-","_",$getVar["field_index"]));
            $data_insert["label"]=trim($getVar["label"]);
            $data_insert["rule"]=$this->saveRules($getVar);
            $data_insert["type_field"]=$getVar["type_field"];
            $data_insert["field_sql"]=str_replace("-","_",$data_insert["field_index"]);
            $data_insert["type"]=$getVar["entity"];
            $data_insert["table"]=$this->getTableEntities($data_insert["type"]);
            
            switch($data_insert["type_field"])
            {
                case "select":
                case "radio":
                case "check":
                    $data_insert["table_list"]="crm_list_".$data_insert["field_sql"];
                    $data_insert["key_list"]="id";
                    $data_insert["label_list"]="label";
                    $data_insert["order_list"]="rank,label";
                    break;
            }
            
            $builder->insert($data_insert);

            //ON modifie le model data
            
           $this->createFields($data_insert);
               
        }

        switch($getVar["type_field"])
        {
            case "check":
            case "radio":
            case "select":
//debug($getVar,TRUE);
                if($getVar["mode"]=="insert"){ $getVar["table_list"]=$data_insert["table_list"];}

                if($this->db->tableExists($getVar["table_list"]))
                {
                    for($num_item_list=1;$num_item_list<=$total_item;$num_item_list++)
                    {
                        $builder=$this->db->table($getVar["table_list"]);
                        $data_updateItem["label"]=$getVar["label_item_##$num_item_list"];

                        if(isset($getVar["ref_item_##$num_item_list"]))
                        {
                            $data_updateItem["ref"]=$getVar["ref_item_##$num_item_list"];
                        }

                        $data_updateItem["rank"]=$num_item_list;

                        if(isset($getVar["is_actif_item_##$num_item_list"]))
                        {
                            $data_updateItem["is_actif"]=0;
                        }
                        else
                        {
                            $data_updateItem["is_actif"]=1;
                        }

                        if($getVar["id_item_##$num_item_list"]=='0')
                        {
                           
                            $builder->insert($data_updateItem);
                        }
                        else
                        {
                            
                            if(isset($getVar["ref_item_##$num_item_list"]))
                            {
                                $where_updateItem["ref"]=$getVar["id_item_##$num_item_list"];
                            }
                            else
                            {
                                $where_updateItem["id"]=$getVar["id_item_##$num_item_list"];
                            }
                            $builder->update($data_updateItem,$where_updateItem);
                        }
                    }
                }

                break;
        }


    }

    protected function saveRules($getVar)
    {
        $rule=NULL;
        $is_required=FALSE;
        if(isset($getVar["rule_required"])&&$getVar["rule_required"]==1)
        {
            $rule.="trim|required";
            $is_required=TRUE;
        }

        switch($getVar["type_field"])
        {
            case "email":
                if($is_required)
                {
                    $rule="trim|valid_email";
                    
                }
                else
                {
                    $rule="trim|permit_empty|valid_email";
                }

                return $rule;

            case "int":
                if($is_required)
                {
                    $rule="trim|numeric";
                    
                }
                else
                {
                    $rule="trim|permit_empty|numeric";
                }

                return $rule;

            case "decimal":
            case "price":
                    if($is_required)
                    {
                        $rule="trim|numeric";
                        
                    }
                    else
                    {
                        $rule="trim|permit_empty|numeric";
                    }
    
                    return $rule;    

            default:
                return $rule;
        }

        return $rule;

    }

    //Models for injected form
    public function getListInjectedForm()
    {
        $conditions=$this->getInjectedForm_conditions();

        $builder=$this->db->table("ban_injected_form");



        $builder->select(
            "
                id_injected_form,
                fields,
                title,
                header_text,
                is_actif,
             
            "

        );

        if(!empty($conditions))
        {
            $builder->select(
                $conditions
            );
        }
       

        if($this->getInjectedFormParams_spip())
        {
            //$builder->select("(SELECT group_concat(spip2019_mots.titre) FROM spip2019_mots WHERE FIND_IN_SET(spip2019_mots.id_mot,ban_injected_form.filtre_spip) ORDER BY spip2019_mots.id_groupe ) as filtre_spip_labels");

        }
       
        // $builder->join("spip2019_mots","FIND_IN_SET(ban_injected_form.filtre_spip,spip2019_mots.id_mot)","left");
        $builder->orderBy("id_injected_form DESC");
        return $builder->get()->getResult();
    }

    public function getInjectedForm($id_injected_form)
    {
        $builder=$this->db->table("ban_injected_form");
        $builder->where("id_injected_form",$id_injected_form);
        return $builder->get()->getRow();
    }

    public function getInjectedFormDefault()
    {
        $builder=$this->db->table("ban_injected_form_params");
        return $builder->get()->getRow();
      
    }

    public function getInjectedFormConditions()
    {
        $builder=$this->db->table("ban_injected_form_params");
        return $builder->get()->getRow();
      
    }

    public function getInjectedFormPrefixe_spip()
    {
        $builder=$this->db->table("ban_injected_form_params");
        $params=$builder->get()->getRow();

        if(isset($params->prefixe_spip)&&!empty($params->prefixe_spip))
            return $params->prefixe_spip;

        return NULL;    
      
    }

    public function getInjectedForm_conditions()
    {
        $builder=$this->db->table("ban_injected_form_params");
        $params=$builder->get()->getRow();

        if(isset($params->conditions)&&!empty($params->conditions))
            return $params->conditions;

        return NULL;    
      
    }

    public function getInjectedFormParams_spip()
    {
        $builder=$this->db->table("ban_injected_form_params");
        $params=$builder->get()->getRow();

        if(isset($params->has_filtre_spip)&&$params->has_filtre_spip==1)
            return TRUE;

        return FALSE;    
      
    }

    
    public function getFieldsInjedtedFormDefault()
    {
        $builder=$this->db->table("ban_injected_form_params");
        $fields=$builder->get()->getRow();
        
        $builder=$this->db->table("ban_fields");
        $builder=$builder->whereIn("field_index",explode(",",$fields->fields));
        return $builder->get()->getResult();
    }

    public function getFieldsInjedtedFormSelected($id_injected_form)
    {
        if($id_injected_form>0)
        {
            $builder=$this->db->table("ban_injected_form");
            $builder->where("id_injected_form",$id_injected_form);
            $fields=$builder->get()->getRow();
            return explode(",",$fields->fields);

        }
        else
        {
            $builder=$this->db->table("ban_injected_form_params");
            $fields=$builder->get()->getRow();
            return explode(",",$fields->fields);

        }
    }

     //attention, n'est pas encore généralisé mais reste lié au contexte cemea
     public function getInjectedFormIframe($id_activities)
     {
       
        //debug($id_activities);
         //1. On regarde si un id article correspond
         $builder=$this->db->table("ban_injected_form");
         $builder->where("id_activity",$id_activities);
        $builder->where("is_actif",1);
         $builder->orderBy("id_injected_form DESC");
         $injectedForm=$builder->get()->getRow();

         $residentiel=$this->getResidentielActivity($id_activities);

         if(!empty($injectedForm))
         {
             return $injectedForm;
         }

         //22222222222222222. Sinon on recherche par système de mots clés, avec mots clés qui correspondent
         ////3333333333. Sinon, on cherche AU MOINS une correspondance

         //On récupére pour l'id_article, les mots clés classés par groupe de mots clés
         

         $builder=$this->db->table("spip2019_mots_liens");
         $builder->select("
                GROUP_CONCAT(spip2019_mots_liens.id_mot) as id_mot,
                GROUP_CONCAT(spip2019_mots.titre) as mot,
                spip2019_mots.id_groupe,
                spip2019_groupes_mots.titre as groupe
                ");
         $builder->join("spip2019_mots","spip2019_mots.id_mot=spip2019_mots_liens.id_mot");
         $builder->join("spip2019_groupes_mots","spip2019_groupes_mots.id_groupe=spip2019_mots.id_groupe");
         $builder->where("id_objet",$id_activities);
         $builder->where("objet","article");
         $builder->groupBy("spip2019_mots.id_groupe");
         $mots=$builder->get()->getResult();

        // debug($mots);

         if(!empty($mots)) //on continue car a peut être un filtre correspondant aux filtres formulaires
         {
            $groupe_mots_action=[];
            //On crée la liste des groupes de mot trouvés pour l'action/article

            foreach($mots as $mot)
            {
                array_push($groupe_mots_action,$mot->id_groupe);
            }
            //debug($groupe_mots_action);

            //Sur base du résultats de la requête, je vais chercher les groupes de mot non choisis par le système et je remplis un array avec les id_mts en question
            //Je m'appuie sur le système qui retourne les id_des filtres et je fais l'intersection
            $filtres_spip=$this->getFiltreSpip("id_groupe"); 

           //debug($filtres_spip);
   
   
           /*Onr recherche un formulaire 
               regle suivante: Au sein d'un mot clé on regarde si on a un mot clé au sein d'un groupe de mots lié à l'article (OR au sein d'un groupe et AND entre groupe)
               Il faut voir aussi pour l'ensemble des groupes de mots non présent si on a des id comme filtre, si c'est le cas
               on ne prend pas le formulaire
   
            */
            $possibles=["strict","nonstrict"];

            foreach($possibles as $possible)
            {
                    $builder=$this->db->table("ban_injected_form");
                    $builder->where("is_actif",1);

                        if(!empty($residentiel)&&!is_null($residentiel))
                        {
                            $builder->where("residentiel",$residentiel);
                        }
                        else
                        {
                            $builder->where("residentiel is null");

                        }

                    $builder->orderBy("id_injected_form DESC");

                    foreach($mots as $mot)
                    {
                        if($possible=="strict")
                        {
                            $builder->groupStart();

                        }
                        else
                        {
                            $builder->orGroupStart();

                        }

                        //On explose et on cherche
                        if(!empty($mot))
                        {   
                            $mot_explode=explode(",",$mot->id_mot);
                            foreach($mot_explode as $me)
                            {
                                $builder->orWhere("FIND_IN_SET('$me',ban_injected_form.filtre_spip)>",0);
                            }
                            
                        }
                        $builder->groupEnd();

                        if(!empty($filtres_spip))
                        {
                            foreach($filtres_spip as $gr=>$filtre)
                            {
                                if(!in_array($gr,$groupe_mots_action))
                                {

                                    foreach($filtre as $km=>$vm)
                                    {
                                        $builder->where("FIND_IN_SET('$km',ban_injected_form.filtre_spip)=",0);
                                    }
                                
                                }
                            }
                        }

                        
                    }

                    $injectedForm=$builder->get()->getRow();
                    // echo "<h3>Resltat</h3>";

                    if(!empty($injectedForm))
                    {
                        return $injectedForm;
                    }
                    //debug($injectedForm, true);
            }
         }

        

       
        


         
         //444444444444444444444444444. Sinon on recherche le dernier formulaire qui n'a pas de conditions
         $builder=$this->db->table("ban_injected_form");
         $builder->where("id_activity IS NULL AND (filtre_spip IS NULL OR filtre_spip ='' OR filtre_spip =' ' )");
         if(!empty($residentiel)&&!is_null($residentiel))
         {
             $builder->where("residentiel",$residentiel);
         }
         else
         {
            $builder->where("residentiel is null");
         }

         
         $builder->where("is_actif",1);
        $builder->orderBy("id_injected_form DESC");
         $injectedForms=$builder->get()->getRow();
         
         
         
         if(!empty($injectedForms))
         {
            return $injectedForms;

         }

         $builder=$this->db->table("ban_injected_form");
         $builder->where("id_activity IS NULL AND (filtre_spip IS NULL OR filtre_spip ='' OR filtre_spip =' ' )");
         $builder->where("residentiel is null");
         $builder->where("is_actif",1);
         $builder->orderBy("id_injected_form DESC");
        $injectedForms=$builder->get()->getRow();

        if(!empty($injectedForms))
         {
            return $injectedForms;

         }



         return NULL;
     }
 
    public function getResidentielActivity($id_activity)
    {
        $builder=$this->db->table("activities");

        $builder->where("id_activity",$id_activity);

        $activity=$builder->get()->getRow();

        return $activity->residentiel;
    }

    public function getLastHeaderText()
    {
        $builder=$this->db->table("ban_injected_form");
        $builder->where("header_text is NOT NULL");
        $builder->orderBy("id_injected_form DESC");
        $lastHeader=$builder->get()->getRow();

        if(!empty($lastHeader))
        {
            return $lastHeader->header_text;
        }
        else
        {
            return NULL;
        }
    }

    public function saveInjectedForm($posts)
    {

        //Je récupere les params
        $builder=$this->db->table("ban_injected_form_params");
        $params_injected_form=$builder->get()->getRow();

        



        $builder=$this->db->table("ban_injected_form");
        //debug($posts,true);
        
        if(!empty(trim($posts["title"])))
        {
            $data["title"]=$posts["title"];;
        }
        else
        {
            $data["title"]="Formulaire sans titre";
        }

        if(!empty(trim($posts["header_text"])))
        {
            $data["header_text"]=$posts["header_text"];
        }
        else
        {
            $data["header_text"]=NULL;
        }

        $data["fields"]=$posts["fields"];



        //Je règle les filtres

        if(isset($params_injected_form->conditions))
        {
            $filtres=explode(",",$params_injected_form->conditions);

            foreach($filtres as $filtre)
            {

                if(!$this->db->fieldExists($filtre,"ban_injected_form"))
                {
                    $this->db->query("ALTER TABLE `ban_injected_form` ADD `$filtre` text NULL");
                }


                if(!empty(trim($posts[$filtre])))
                {
                    
                    if(is_array($posts[$filtre]))
                    {
                        $data[$filtre]=implode(",",trim($posts[$filtre]));
                    }
                    else
                    {
                        $data[$filtre]=trim($posts[$filtre]);

                    }
                }
                else
                {
                    $data[$filtre]=NULL;
                }
            }

        }


        if(isset($params_injected_form->has_filtre_spip))
        {
            if(!$this->db->fieldExists("filtre_spip","ban_injected_form"))
            {
                $this->db->query("ALTER TABLE `ban_injected_form` ADD `filtre_spip` text NULL");
            }

            if(!empty($posts["filtre_spip"]))
            {
    
                $data["filtre_spip"]=implode(",",$posts["filtre_spip"]);
            }
            else
            {
                $data["filtre_spip"]=NULL;
            }
    
        }
      

        
      /*  if(!empty($posts["id_activity"]))
        {

            $data["id_activity"]=$posts["id_activity"];
        }
        else
        {
            $data["id_activity"]=NULL;
        }


        if(!empty($posts["residentiel"]))
        {

            $data["residentiel"]=$posts["residentiel"];
        }
        else
        {
            $data["residentiel"]=NULL;
        }*/


       


        if($posts["id_injected_form"]>0)
        {
            $where["id_injected_form"]=$posts["id_injected_form"];
            $builder->update($data,$where);
            $this->treatInjectedFieldIndex($posts["id_injected_form"]);

            return true;
        }
        else
        {
            $data["id_injected_form"]=$posts["id_injected_form"];
            $builder->insert($data);
            $this->treatInjectedFieldIndex($posts["id_injected_form"]);
            
        }
    }


    public function treatComponentsFieldIndex($table_component,$id_component=0)
    {
        $table_insert=$table_component."_fields_index";
        $builder=$this->db->table($table_component);

        $components=$builder->get()->getResult();


        $builder=$this->db->table($table_insert);

        if($id_component>0)
        {
            $builder->delete(["id_component"=>$id_component]);
        }
        else
        {
            $builder->truncate(); 
        }


        if(!empty($components))
        {
            foreach($components as $component)
            {
                $fields=explode(",",$component->fields);

                if(!empty($fields))
                {
                    foreach($fields as $field)
                    {
                        if(!$this->isExistComponentsFieldIndex($component->id_components,$field,$table_component))
                        {
                            $data["id_component"]=$component->id_components;
                            $data["field_index"]=$field;
                            $builder->insert($data);
                        }
                    }
                }
            }
        }

    }


    public function isExistComponentsFieldIndex($id_component,$field_index,$components)
    {
        $builder=$this->db->table($components."_fields_index");
        $builder->where("id_component",$id_component);
        $builder->where("field_index",$field_index);

        $result=$builder->get()->getRow();

        if(!empty($result))
            return TRUE;

        return false;


    }

    public function setComponentsFieldIndex($id_components,$entity)
    {
        $table="ban_components_$entity";
        $table_index=$table."_fields_index";
        $builder=$this->db->table($table_index);
        $builder->where("id_component",$id_components);
        $fields=$builder->get()->getRow();

        if(!empty($fields))
        {
            $this->db->query("UPDATE $table 
        SET fields=(SELECT GROUP_CONCAT(field_index SEPARATOR ',') FROM $table_index WHERE $table_index.id_component=$id_components ORDER BY id_component_field_index) 
         WHERE $table.id_components=$id_components");
        }
        else
        {
            $this->db->query("UPDATE $table SET fields='' WHERE $table.id_components=$id_components");
        }
       
    }


    public function treatInjectedFieldIndex($id_injected_form=0)
    {
        $table_insert="ban_injected_form_fields_index";
        $builder=$this->db->table("ban_injected_form");

       

        $injecteds=$builder->get()->getResult();


        $builder=$this->db->table($table_insert);

        if($id_injected_form>0)
        {
            $builder->delete(["id_injected_form"=>$id_injected_form]);
        }
        else
        {
            $builder->truncate(); 
        }


        if(!empty($injecteds))
        {
            foreach($injecteds as $injected)
            {
                $fields=explode(",",$injected->fields);

                if(!empty($fields))
                {
                    foreach($fields as $field)
                    {
                        if(!$this->isExistInjectedFieldIndex($injected->id_injected_form,$field))
                        {
                            $data["id_injected_form"]=$injected->id_injected_form;
                            $data["field_index"]=$field;
                            $builder->insert($data);
                        }
                    }
                }
            }
        }

    }


    public function isExistInjectedFieldIndex($id_injected_form,$field_index)
    {
        $builder=$this->db->table("ban_injected_form_fields_index");
        $builder->where("id_injected_form",$id_injected_form);
        $builder->where("field_index",$field_index);

        $result=$builder->get()->getRow();

        if(!empty($result))
            return TRUE;

        return false;


    }

    public function setInjectedFieldIndex($id_injected_form)
    {
        $builder=$this->db->table("ban_injected_form_fields_index");
        $builder->where("id_injected_form",$id_injected_form);
        $fields=$builder->get()->getRow();

        if(!empty($fields))
        {
            $this->db->query("UPDATE ban_injected_form 
        SET fields=(SELECT GROUP_CONCAT(field_index SEPARATOR ',') FROM ban_injected_form_fields_index WHERE ban_injected_form_fields_index.id_injected_form=$id_injected_form ORDER BY id_injected_form_field) 
         WHERE ban_injected_form.id_injected_form=$id_injected_form");
        }
        else
        {
            $this->db->query("UPDATE ban_injected_form SET fields='' WHERE ban_injected_form.id_injected_form=$id_injected_form");
        }
       
    }


    public function verifFieldIndex()
    {
       $builder=$this->db->table("ban_fields");
       $fields=$builder->get()->getResult();

       foreach($fields as $field)
       {
           if(!$this->db->fieldExists($field->field_sql,$field->table))
           {
                $this->createFields((array) $field);

           }

       }

       return;

    }


    public function verifFieldIndexFormComponents()
    {
        $entities=$this->get_list_entity();

        foreach($entities as $entity)
        {
           $this->oneVerifFieldIndexFormComponents($entity);
        }

    }

    public function oneVerifFieldIndexFormComponents($entity)
    {
        if ($this->db->tableExists('ban_components_'.$entity.'_fields_index')) {
            
                
                
                $builder=$this->db->table('ban_components_'.$entity.'_fields_index');
                $builder->where("field_index NOT IN (SELECT field_index FROM ban_fields)");

                $fields=$builder->get()->getResult();

                foreach($fields as $field)
                {
                    if(!in_array($field->field_index,$this->excludeField))
                    {
                        $builder->delete(array("id_component_field_index"=>$field->id_component_field_index));
                        $this->setComponentsFieldIndex($field->id_component,$entity);
                    }
                
                }

        }
       
    }


    public function verifFieldIndexFormInjected()
    {
    
        $builder=$this->db->table('ban_injected_form_fields_index');
        $builder->where("field_index NOT IN (SELECT field_index FROM ban_fields)");

        $fields=$builder->get()->getResult();

        foreach($fields as $field)
        {
            if(!in_array($field->field_index,$this->excludeField))
            {
                //print_r($field);
                $builder->delete(array("id_injected_form_field"=>$field->id_injected_form_field));
                $this->setInjectedFieldIndex($field->id_injected_form);
            }
           
        }
       
    }


    public function deleteField($fieldIndex)
    {
        //On efface le field
        $builder=$this->db->table("ban_fields");
        $builder->where(array("field_index"=>$fieldIndex));
        $field=$builder->get()->getRow();
        if(!empty($field))
        {
            $builder->delete(array("field_index"=>$fieldIndex));

            $forge = \Config\Database::forge();

            $fields=[
                $field->field_sql=>
                [
                    'name'=>"zz_old_".date('ymdhis')."_".$field->field_sql,
                    'type'=>'TEXT'
                ]
            ];

            $forge->modifyColumn($field->table,$fields);


            if(!empty($field->table_list))
            {
                if($this->db->tableExists($field->table_list))
                {
                    //echo "je suis là";
                    switch($field->type_field)
                    {
    
                        case "select":
                        case "radio":
                        case "check":
                            
                            $forge->renameTable($field->table_list,'zz_old_'.date('ymdhis').'_'.$field->table_list);

                            break;
                    }
                }
            }

            $this->verifFieldIndexFormComponents();
            $this->verifFieldIndexFormInjected();

            //on verifie s'il a bien été effacé
            $builder=$this->db->table("ban_fields");
            $builder->where(array("field_index"=>$fieldIndex));
            $field=$builder->get()->getRow();
            if(empty($field))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

    }

    public function createFields($data_insert)
    {
        $forge = \Config\Database::forge();

        //1. On ajoute le nouveau champ

        switch($data_insert["type_field"])
        {
            case "key":
                $dataForge= [
                                'type'       => 'INT',
                            ];
                break;
            
            case "input":
                $dataForge= [
                                'type'       => 'VARCHAR',
                                'constraint' => 255,
                                'null'       => true,
                            ];
                break;

            case "textarea":
                $dataForge= [
                                'type'       => 'TEXT',
                                'null'       => true,
                            ];
                break;

            case "email":
                $dataForge= [
                                'type'       => 'VARCHAR',
                                'constraint' => 255,
                                'null'       => true,
                            ];
            
                break;


            case "int":
                $dataForge= [
                                'type'       => 'INT',
                                'null'       => true,
                            ];
                break;


            case "price":
                $dataForge= [
                                'type'       => 'INT',
                              
                                'null'       => true,
                            ];
                
                break;

            case "date":
                $dataForge= [
                    'type'       => 'DATE',
                    'null'       => true,
                ];
                
                break;

            case "birthday":
                $dataForge= [
                    'type'       => 'DATE',
                    'null'       => true,
                ];
                
                break;

            case "hour":
                $dataForge= [
                    'type'       => 'TIMES',
                    'null'       => true,
                ];
                
                break;  

            case "select":
                $dataForge= [
                    'type'       => 'INT',
                    'null'       => true,
                ];

                break;

            case "radio":
                $dataForge= [
                    'type'       => 'INT',
                    'null'       => true,
                ];

                break;

            case "check":
                $dataForge= [
                    'type'       => 'TEXT',
                    'null'       => true,
                ];

                default:
                    $dataForge= [
                                    'type'       => 'TEXT',
                                    'null'       => true,
                                ];
                    break;    
               
                break;
        }

        $fields[$data_insert["field_sql"]]=$dataForge;
        $forge->addColumn($data_insert["table"], $fields);

        //2. On Crée si besoin la table liée, on vérifie si la table existe ou non
        if(!empty($data_insert["table_list"]))
        {
            if(!$this->db->tableExists($data_insert["table_list"]))
            {
                //echo "je suis là";
                switch($data_insert["type_field"])
                {

                    case "select":
                    case "radio":
                    case "check":
                        
                        $fieldsList = 
                        [  
                            'label'             =>  [
                                                        'type'           => 'VARCHAR',
                                                        'constraint'     => '255',
                                                    ],

                            'rank'              =>  [
                                                        'type'           =>'INT',
                                                    ],

                            'is_actif'          =>  [
                                                        'type'           => 'INT',
                                                        'default'        =>1
                                                    ],

                            'date_modification'  => [
                                                        'type'           => 'TIMESTAMP',
                                                        'null'       => true,
                                                
                                                    ],

                            'id_user'           =>  [     
                                                        'type'           => 'INT',
                                                        'null'       => true,
                                                    ],
                        ];
                        $forge->addField($fieldsList);
                        $forge->addField('id');
                        $forge->createTable($data_insert["table_list"]);
                        break;
                }
            }
        }
    }
    
    //Parmas $index_groupe, permet de retourner array avec comme index soit titre des groupes ou id_groupe, 
    // Cette méthode retourne una rray avec comme index nom du filtre (titre du groupe) ou bine id_filtre (id_groupe),et pour chaque groupe, les id correspondants

    public function getFiltreSpip($index_groupe="titre")
	{
		$filtres=[];

		$rubriques=["rubrique|125","rubrique|27"];

		foreach($rubriques as $rubrique)
		{
			$builder=$this->db->table("spip2019_groupes_mots");
			$builder->where("rubriques_on",$rubrique);
			$builder->orderBy("titre");
			$groupes=$builder->get()->getResult();
           
			if(!empty($groupes))
			{
                
				foreach($groupes as $groupe)
				{
					$builder=$this->db->table("spip2019_mots");
					$builder->where("id_groupe",$groupe->id_groupe);
					$builder->orderBy("titre");
					$mots=$builder->get()->getResult();

                    $mots_find=[];
					if(!empty($mots))
					{
						foreach($mots as $mot)
						{
							$filtres[$groupe->$index_groupe][$mot->id_mot]=$mot->titre;
						}
						
					}

                    //$filtres[$groupe->$index_groupe]=$mots_find;
				}
				

				
			}

            

			
		}

		return $filtres;

	}

    public function get_label_categorie_contact($categorie_profil_contact)
    {
        $builder=$this->db->table("crm_list_categorie_profil_contact");
        $builder->where("id",$categorie_profil_contact);

        $cat=$builder->get()->getRow();

        if(empty($cat->label))
            return "Titre Inconnu";

        return $cat->label;
    }

    public function get_label_of_value($value,$descriptor,$index)
    {
        $builder=$this->db->table($descriptor[$index]["table_list"]);
        $builder->where([$descriptor[$index]["key_list"]=>$value]);
        $new_values_label=$builder->get()->getRow();

        if(!empty($new_values_label))
        {
            $field_label=$descriptor[$index]["label_list"];

            if (strpos($field_label, ",") !== FALSE) {
                
                $explode_field_labels=explode(",",$field_label);

                $new_value_label="";
                foreach($explode_field_labels as $label_field)
                {
                    if(isset($new_values_label->$label_field))
                    {
                        $new_value_label.=$new_values_label->$label_field." ";
                    }
                }
            }
            else
            {
                $new_value_label=$new_values_label->$field_label;

            }

        }
        return $new_value_label;
    }

    public function set_log_fiche($entity,$newValues,$id_primary_name,$id_primary_value,$table,$type)
    {
        $data_changes=[];
        //1. je récupere les fields possibles pour le type de component et uniquement la table concernée, car dans fields on a plusisuers tables possibles
        $builder=$this->db->table("ban_components_$entity");
        $builder->where(["type"=>$type]);
        $indexes_possible=$builder->get()->getRow();

        $descriptor=$this->getFields();

        //on garde les fields qui correspondent à la table où on insère les données
        $indexes_possible=explode(",",$indexes_possible->fields);
        $indexes_entity=[];
       
        foreach($indexes_possible as $index_possible)
        {
            $index_possible=trim($index_possible);
            if(isset($descriptor[$index_possible])&&$descriptor[$index_possible]["table"]==$table)
            {
                $indexes_entity[$index_possible]=$descriptor[$index_possible];
            }
        }
      
       // debug($indexes_entity);
        //2. Je récupère les valeurs actuels
      
        $builder=$this->db->table($table);

        foreach($indexes_entity as $key_entity=>$index_entity)
        {
            if($index_entity["type_field"]!="key")
            {
                $builder->select($index_entity["field_sql"]." as $key_entity");

            }

        }
        
        $builder->where([$id_primary_name=>$id_primary_value]);
        $builder->orderBy("$id_primary_name DESC");
        $old_values=$builder->get()->getRow();

        $is_debug=false;
        if($is_debug)
        {
            debug($old_values);
            debugd($newValues);
        }
     

        //3. On compare
        if(!empty($old_values))
        {
            foreach($old_values as $index=>$old_value)
            {
                $type_field=$descriptor[$index]["type_field"];

                if(isset($newValues[$index]))
                {
                    $new_values_index=$newValues[$index];

                }
                else
                {
                    $new_values_index="";
                }


                switch($type_field)
                {
                    case "check":

                        if(is_null($old_value)||$old_value==0||empty($old_value))
                        {
                            $old_value="";
                        }

                        if(is_null($new_values_index)||$new_values_index==0||empty($new_values_index))
                        {
                            $new_values_index="";
                        }


                        break;
                            

                    case "radio":
                    case "select":
                        if(is_null($old_value)||$old_value==0||empty($old_value))
                        {
                            $old_value="";
                        }

                        if(is_null($new_values_index)||$new_values_index==0||empty($new_values_index))
                        {
                            $new_values_index="";
                        }
                        break;

                    case "birthday":      
                    case "date":
                        if(is_null($old_value)||$old_value=="0000-00-00 00:00:00"||$old_value=="0000-00-00"||empty($old_value))
                        {
                            $old_value="";
                        }

                        if(is_null($new_values_index)||$new_values_index=="0000-00-00 00:00:00"||$new_values_index=="0000-00-00"||empty($new_values_index))
                        {
                            $new_values_index="";
                        }
                        break;

                    default:

                            if(is_null($old_value)||empty($old_value))
                            {
                                $old_value="";
                            }

                            if(is_null($new_values_index)||empty($new_values_index))
                            {
                                $new_values_index="";
                            }



                }

                if((isset($new_values_index)&&$new_values_index!=$old_value) || !isset($new_values_index))
                {
                    //On récupère les valeurs
                

                    switch($type_field)
                    {
                        case "check":
                            $old_value_label='';
                            
                            if(!empty($old_value))
                            {
                                $old_value_array=explode(",",$old_value);
                                $old_value_labels=[];

                                foreach($old_value_array as $ov)
                                {
                                    if($ov>0)
                                    {
                                        array_push($old_value_labels,$this->get_label_of_value($ov,$descriptor,$index));

                                    }
                                }

                                if(!empty($old_value_labels))
                                {
                                    $old_value_label=implode(", ",$old_value_labels);
                                }
                            }

                            $new_value_label='';
                            
                            if(!empty($new_values_index))
                            {
                                $new_value_array=explode(",",$new_values_index);
                                $new_value_labels=[];

                                foreach($new_value_array as $nv)
                                {
                                    if($nv>0)
                                    {
                                        array_push($new_value_labels,$this->get_label_of_value($nv,$descriptor,$index));

                                    }
                                }

                                if(!empty($new_value_labels))
                                {
                                    $new_value_label=implode(", ",$new_value_labels);
                                }
                            }
                            break;


                        case "radio":
                        case "select":
                            //On récupère le label pour old_value
                            if(!empty($old_value))
                            {
                            
                                $old_value_label=$this->get_label_of_value($old_value,$descriptor,$index);
                            }
                            else
                            {
                                $old_value_label='';
                            }
                        
                            //On récupère le label pour new_value
                            if(!isset($new_values_index))
                            {
                                $new_value_label='';
                            }
                            else
                            {
                                if(!empty($new_values_index))
                                {
                                
                                    $new_value_label=$this->get_label_of_value($new_values_index,$descriptor,$index);
                                }
                                else
                                {
                                    $new_value_label='';
                                }
                            }


                            break;

                        default:

                            $old_value_label=$old_value;
                            $new_value_label=$new_values_index;
                        
                            
                    }
                    $data_changes[$index]=["value_old"=>$old_value_label,"value_new"=>$new_value_label];
                // debugd($data_changes);
                }
            }
        
        }
        return $data_changes;
       

    }

    public function set_log_fiche_insert($newValue)
    {
        $data_changes=[];
        $descriptor=$this->getFields();

           

        foreach($newValue as $index=>$new_values_index)
        {
            $type_field=$descriptor[$index]["type_field"];
            $new_value_label='';
        
            switch($type_field)
            {
                case "check":
                    $new_value_label='';
                        
                    if(!empty($new_values_index))
                    {
                        $new_value_array=explode(",",$new_values_index);
                        $new_value_labels=[];

                        foreach($new_value_array as $nv)
                        {
                            if($nv>0)
                            {
                                array_push($new_value_labels,$this->get_label_of_value($nv,$descriptor,$index));

                            }
                        }

                        if(!empty($new_value_labels))
                        {
                            $new_value_label=implode(", ",$new_value_labels);
                        }
                    }

                    break;

                case "radio":
                case "select":


                    if(is_null($new_values_index)||$new_values_index==0||empty($new_values_index))
                    {
                        $new_values_index="";
                    }

                    else
                    {
                        if(!empty($new_values_index))
                        {
                            $new_value_label=$this->get_label_of_value($new_values_index,$descriptor,$index);
                       
                        }
                        else
                        {
                            $new_value_label='';
                        }
                    }


                    break;
          
    
                    case "birthday":      
                    case "date":
                    
        
                        if(is_null($new_values_index)||$new_values_index=="0000-00-00 00:00:00"||$new_values_index=="0000-00-00"||empty($new_values_index))
                        {
                            $new_value_label="";
                        }
                        break;

                    default:
                      
                        $new_value_label=$new_values_index;
    
            }


            if(!empty($new_value_label))
            {
                $data_changes[$index]=["value_old"=>"","value_new"=>$new_value_label];
            }
        }

        return $data_changes;
    }
    
    public function set_logs_relation_bien($id_personne_bien,$rel_personne_bien)
    {
        $descriptor=$this->getFields();
        $old_value_label="";
        $data_changes=[];
        $rel_personne_bien_old=0;
        $builder=$this->db->table("personne_bien");
        $builder->where("id_personne_bien",$id_personne_bien);
        
        $personnes_bien_old=$builder->get()->getResult();

        if(!empty($personnes_bien_old))
        {
            foreach($personnes_bien_old as $personne_bien_old)
            {
                if(!empty($personne_bien_old->rel_personne_bien)&&$personne_bien_old->rel_personne_bien>0)
                {
                    $rel_personne_bien_old=$personne_bien_old->rel_personne_bien;
                }
            }
        }
        else
        {
            $rel_personne_bien_old=0;
        }

        if(is_null($rel_personne_bien)||empty($rel_personne_bien))
        {
            $rel_personne_bien=0;
        }

        if($rel_personne_bien!=$rel_personne_bien_old)
        {
            $index="rel_personne_bien";

            $builder=$this->db->table($descriptor[$index]["table_list"]);
            $builder->where([$descriptor[$index]["key_list"]=>$rel_personne_bien_old]);
            $old_values_label=$builder->get()->getRow();
            if(!empty($old_values_label))
            {
                $field_label=$descriptor[$index]["label_list"];
                $old_value_label=$old_values_label->$field_label;
            }

            $builder=$this->db->table($descriptor[$index]["table_list"]);
            $builder->where([$descriptor[$index]["key_list"]=>$rel_personne_bien]);
            $new_values_label=$builder->get()->getRow();
            if(!empty($new_values_label))
            {
                $field_label=$descriptor[$index]["label_list"];
                $new_value_label=$new_values_label->$field_label;
            }


            $data_changes[$index]=["value_old"=>$old_value_label,"value_new"=>$new_value_label];
        }
        
        return $data_changes;
    }

    public function set_logs_fiche_insert_bd($entity,$data_changes,$date_modification,$id_entity,$key_primary)
    {
        if(!empty($data_changes))
        {
            $id_user=session()->loggedUserId;

            foreach($data_changes as $index=>$value)
            {
                $data["index"]=$index;
                $data["value_old"]=$value["value_old"];
                $data["value_new"]=$value["value_new"];
                $data["date_modification"]=$date_modification;
                $data["id_user"]=$id_user;
                $data["key_primary"]=$key_primary;
                $data["id_entity"]=$id_entity;
                $builder=$this->db->table($entity."_h");
                $builder->insert($data);
            }

            return true;
        }
        return null;

    }

    
 
}