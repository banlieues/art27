<?php

namespace DataQuery\Models;

use CodeIgniter\Model;

class dataQueryModel extends Model
{
    protected $returnType     = 'object';
    protected $table=      "ban_entities_params";

    protected $exclude_entity=["lieu"]; //pour exclure une entity du syst!me de requête

    public function getDescriptor()
    {
        $builder=$this->db->table("ban_fields");
        $builder->where("is_requete",1);
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

    

  

    public function getDescriptorIndexByFields()
    {
        $builder=$this->db->table("ban_fields");

        if(!empty($this->exclude_entity))
        {
            foreach($this->exclude_entity as $exclude)
            {
                $builder->where("type<>",$exclude);
            }
        }

    

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

    public function getEntities()
    {
        $builder=$this->db->table("ban_entities_params");
        $builder->where("is_requete",1);
        $builder->orderBy('rank_query');
        return $builder->get()->getResult();
    }

    public function getOneEntities($type)
    {
        $builder=$this->db->table("ban_entities_params");
        $builder->where('type',$type);
        return $builder->get()->getRow();
    }


    public function getFields($type)
    {
        $builder=$this->db->table("ban_fields");
        if(!empty($this->exclude_entity))
        {
            foreach($this->exclude_entity as $exclude)
            {
                $builder->where("type<>",$exclude);
            }
        }
        $builder->where("type",$type);
        $builder->orderBy("label");
        return $builder->get()->getResult();
    }

    public function getFieldsFromModelisation($type)
    {
        $builder=$this->db->table("ban_fields");
        if(!empty($this->exclude_entity))
        {
            foreach($this->exclude_entity as $exclude)
            {
                $builder->where("type<>",$exclude);
            }
        }
        $builder->where("type",$type);
        $builder->where("is_requete",1);
        $builder->orderBy("label");
        return $builder->get()->getResult();
    }

   
    public function getList($select_table,$fields=NULL,$select_where=NULL,$order_select=NULL)
    {
        $builder=$this->db->table($select_table);
        if(!is_null($fields))
            $builder->select($fields);

        if(!is_null($select_where))
            $builder->where($select_where);

        if(!is_null($order_select))    
            $builder->orderBy($order_select);

        return $builder->get()->getResult();
    }

    public function create_requete_get_operateur($type_input,$operateur)
	{
	     switch ($type_input):
		
			
			default:
			     switch ($operateur):
			    
				case "vide":
				    $operateur=" IS NULL";
				    break;
				
				case "vide_pas":
				    $operateur=" IS NOT NULL";
				    break;
			    
				case "superieur":
				    $operateur=">";
				    break;
				
				case "inferieur":
				    $operateur="<";
				    break;
				
				case 'contient':
				    
				    $operateur=" LIKE ";
				 break;
			     
			     case 'contient_pas':
				    
				    $operateur=" NOT LIKE ";
				 break;
			     
			     case 'different':
				    $operateur=" != ";
				 break;
				
				default:
				    $operateur="=";
				   break;
			     endswitch;
			    
		    endswitch;
		    
		    return $operateur;
	}

    public function getAllIndexes()
    {
        $descriptor=$this->getDescriptorIndexByFields();
        return array_keys($descriptor);
    }

    public function executeQuery($getVar,$offset=NULL)
    {
       // debug($getVar,true);

        $descriptor=$this->getDescriptorIndexByFields();
        $query=NULL;
        $results=NULL;
        $fieldSQLArray=[];
        $labelsArray=[];
        $entitiesUsed=[];


        $group_byse=NULL;
        $orderse=NULL;

        if(isset($getVar["fields_select"])&&!empty($getVar["fields_select"]))
        {
            $fields=$getVar["fields_select"];
        }
        else //on prend tous les index
        {
            $fields=array_keys($descriptor);
            /*foreach($descriptor as $index=>$value)
            {
                $fields[]=$index;
            }*/
        }

//debug($fields,true);
        
        if(isset($getVar["group_by"])){ $is_group_by=TRUE; $group_by=$getVar["group_by"];} else {$is_group_by=FALSE;}
        // if(isset($getVar["order_by"])){     $orderse=$getVar["order_by"];    }

        //1. Trouver le from de la requête, From est trouvé par la première ligne des conditions
        $entityFirstData=$this->getOneEntities($getVar["entity_##1"]);
       // debug($entityFirstData);
       
        $tableFrom=$entityFirstData->table_primary;
        $keyPrimaryFrom=$entityFirstData->key_primary;
        $this->table=$tableFrom;
        $entityFrom=$entityFirstData->type;
        array_push($entitiesUsed,$entityFirstData->type);//on stocke l'entité dans la liste ds entities ajoutée à la requête
        $fieldKeyPrimary=$entityFirstData->key_primary;

        $fieldsKey[$entityFirstData->type]=$this->table.'.'.$entityFirstData->key_primary;

        $jointureList=array();
        //2. Trouver les champs
        //Attention, si le champs est lié à une autre entité alors il faut ajouter la jointure vers l'autre entité
        foreach($fields as $index)
        {
                    

                    $labelsArray[]=$descriptor[$index]["label"];

                    //ici on va traiter les fields (notamment les fields)
                 
                    switch ($descriptor[$index]["type_field"])
                    {
                       // case 'radio':
                        //case 'select':
                            case "sql_inject":
                                $fieldSQLArray[]=$descriptor[$index]["condition_sql"];

                            break;
                            case "radio":
                                case "select":
                        case "check":
                            $field="(";
                                    $condition_concat=$descriptor[$index]["table"].".".$descriptor[$index]["field_sql"];
                                    //On regarde si on a un array ou pas
                                    $test_is_array=explode(",",$descriptor[$index]["label_list"]);
                                    if(count($test_is_array)>1)
                                    {
                                        $descriptor[$index]["label_list"]=$test_is_array;
                                    }

                                    $field.=" SELECT ";
                                    $field.="GROUP_CONCAT( ";
                                        //cas où il y a plusieurs champs
                                        if(is_array($descriptor[$index]["label_list"]))
                                        {
                                            $field.="CONCAT ( ";
                                                $init_2=0;
                                                foreach($descriptor[$index]["label_list"] as $f)
                                                {
                                                    if($init_2>0)
                                                    {
                                                        $field.=",' ',";
                                                    };

                                                    $field.=$descriptor[$index]["table_list"]."."."$f";
                                                    $init_2=$init_2+1;
                                                }
                                            $field.=")";
                                            $field.=" SEPARATOR ',' ";
                                            $field.=") ";
                                            $field.= " as label_affiche ";
                                        }
                                        else
                                        {
                                            $field.=$descriptor[$index]["table_list"].".".$descriptor[$index]["label_list"];
                                        }

                                        if(!is_array($descriptor[$index]["label_list"]))
                                        {
                                            $field.=" SEPARATOR ',' ";
                                            $field.=") ";
                                        }

                                        $field.=" FROM ".$descriptor[$index]["table_list"];
                                        $field.=" WHERE ";

                                        $cequerecherche=$descriptor[$index]["table_list"].".".$descriptor[$index]["key_list"];
				                        $field.=" FIND_IN_SET ($cequerecherche,$condition_concat)";
				                        //$field.= " GROUP BY ".$des["select_table"].".".$des["select_field_sql"];
                                       
			                            $field.=")";
		   
			                            $field.=" as $index";


                                    
                                    


                               

                            $fieldSQLArray[]=$field;
                            break;

                         /*   case "radioss":
                            case "selectss":
                                $listAlias=$index.'_'.$descriptor[$index]['table_list'];
                                $test_is_array=explode(",",$descriptor[$index]["label_list"]);
                                if(count($test_is_array)>1)
                                {
                                    $field="GROUP CONCAT(";
                                    $it=0;
                                        foreach($test_is_array as $ch)
                                        {
                                            if($it>0)
                                            {
                                                $field.=",' ',";
                                            }
                                            
                                            $field.=$listAlias.'.'.$ch;

                                           $it=$it+1;
                                        }

                                    $field.=") as $index";
                                }

                                $field=$listAlias.'.'.$descriptor[$index]["label_list"].' as '.$index;
                                       

    
                                $fieldSQLArray[]=$field;

                                $tl=$descriptor[$index]['table_list'];
                                $keylist_condition=$descriptor[$index]['key_list'];
                                $table_condition=$descriptor[$index]['table'];
                                $table_field_sql=$descriptor[$index]['field_sql'];
                                //ON ajoute la jointure
                                $jointureList[]=
                                [
                                    "table_join" =>"$tl as $listAlias",
                                   "condition" => $listAlias.'.'.$keylist_condition.'='.$table_condition.'.'.$table_field_sql,

                                ];
                                
                             
                                $this->join(
                                    "$tl 
                                    as $listAlias",
                                        $listAlias.'.'.$descriptor[$index]['key_list'].'='.$descriptor[$index]['table'].'.'.$descriptor[$index]['field_sql']
                                        ,"left");

                                break;      */         

                        default:
                        $fieldSQLArray[]='trim('.$descriptor[$index]["table"].".".$descriptor[$index]["field_sql"].") as ".$index;
                    }


                    //on verfie si l'entité est déjà appelée
                    if(!in_array($descriptor[$index]["type"],$entitiesUsed))
                    {
                        array_push($entitiesUsed,$descriptor[$index]["type"]);         
                    }
        }




        //3. Créer le where et si besoin les jointures nécessaires pour les conditions
        $numberWhere=$getVar["number"];
        $where=NULL;
    
        for($i=1;$i<=$numberWhere;$i++)
        {
            $indexTreat=$getVar["champ_##$i"]; //index du champs lié à la condition
           
           // debug($indexTreat);
            if(!in_array($descriptor[$indexTreat]["type"],$entitiesUsed))
            {
              
                array_push($entitiesUsed,$descriptor[$indexTreat]["type"]);
                
            }


            $tableTreat=$descriptor[$indexTreat]["table"];
            $fieldSqlTreat=$descriptor[$indexTreat]["field_sql"];
            $typeFieldTreat=$descriptor[$indexTreat]["field_sql"];
            $where_condition_sql=$descriptor[$indexTreat]["where_condition_sql"];
            $operateur=$getVar["operateur_##$i"];
            $operateurTreat=$this->create_requete_get_operateur($typeFieldTreat,$getVar["operateur_##$i"]);

            if(isset($getVar["##$i##_value"]))
            {
                $valueTreat=$getVar["##$i##_value"];
            }
            else
            {
                $valueTreat=null;
            }
           

            if($i!=1)//Pour la première condition jamais d'opérateur et/ou, opérateur existe toujours pour les autres conditions
            {
                $where.=' '.$getVar["ou_et_##$i"].' ';
            }

            if($getVar["par_ouvert_##$i"]==1)//On ouvre paranthèse
            {
                $where.=' ( ';
            }

           

            switch($typeFieldTreat)
            {
                case "sqlsql":
                    $field_requete=$where_condition_sql;
                    break;

                default: $field_requete=$tableTreat.'.'.$fieldSqlTreat;
            }
            

            //On ecrit la condition
            switch($typeFieldTreat)
            {
                default:
                     //la table et le champ sur lequel on fait la condition

                    switch($operateur)
                    {


                            case "contient":
                            case "contient_pas":
                                if(is_array($valueTreat)) $valueTreat = implode(',', $valueTreat);

                                $where.= $field_requete;
                                    $where.=$operateurTreat;
                                $where.='"%'.$this->escapeString($valueTreat).'%"';
                             break;

                        case "egal":    
                        case "different":
                            if(is_array($valueTreat)) $valueTreat = implode(',', $valueTreat);

                            $where.= $field_requete;
                            $where.=$operateurTreat;
                            $where.='"'.$this->escapeString($valueTreat).'"';
                            break;

                        case "vide":
                        case "vide_pas":
                            $where.=" ( ";
                                $where.= $field_requete;
                                $where.= ' '.$operateurTreat.' ';
                                if($operateur=="vide")
                                {
                                    $where.=" OR $tableTreat.$fieldSqlTreat=''";
                                }

                                if($operateur=="vide_pas")
                                {
                                    $where.=" AND $tableTreat.$fieldSqlTreat!=''";
                                }


                            $where.=" ) ";

                            break;

                        default:
                           if(!is_array($valueTreat))
                           {     
                            $where.= $field_requete;
                            $where.=$operateurTreat;


                            if(
                                $descriptor[$indexTreat]["type_field"]=="birthday"||
                                $descriptor[$indexTreat]["type_field"]=="date"
                            )
                            {
                                $valueTreat=convert_date_fr_to_en($valueTreat,false);
                            }
                            //$valueTreat=$typeFieldTreat;

                            $where.='"'.$this->escapeString($valueTreat).'"';
                           }
                           else
                           {
                               $j=0;
                               $where.=" ( "; 
                               foreach($valueTreat as $vt)
                               {
                                    if($j>0)
                                    {
                                    $where.=" OR ";
                                    }
                                   
                                    if($operateurTreat=" != "){$operateurTreat=" NOT LIKE ";}
                                    if($operateurTreat="="){$operateurTreat=" LIKE ";}
                                    if($operateurTreat=" = ")$operateurTreat=" LIKE ";{}
                                    if($operateurTreat="!="){$operateurTreat=" NOT LIKE ";}

                                    $op = ($operateurTreat==" NOT LIKE ") ? ' AND ' : ' OR ';

                                   $where.=" ( ";
                                   $where.= "$tableTreat.$fieldSqlTreat $operateurTreat '".$this->escapeString($vt)."'";
                                   $where.= $op; 
                                   $where.= "$tableTreat.$fieldSqlTreat $operateurTreat '".$this->escapeString($vt).',%'."'";
                                   $where.= $op;
                                   $where.= "$tableTreat.$fieldSqlTreat $operateurTreat '%,".$this->escapeString($vt)."'";
                                   $where.= $op;
                                   $where.= "$tableTreat.$fieldSqlTreat $operateurTreat '%,".$this->escapeString($vt).',%'."'";
                                   $where.=" ) ";
                                   
                                   $j=$j=1;
                               }
                               $where.=" ) ";
                           }


                        break;
                                

                    }                    
            }


            if($getVar["par_ferme_##$i"]==1)//on ferme paranthèse
            {
                $where.=' ) ';
            }

        }

        //4. order by
        if(isset($getVar["order_by"]))
        {
            foreach($getVar["order_by"] as $orderBy)
            {
                $this->orderBy($orderBy);
                //$this->orderBy($descriptor[$orderBy]["table"].'.'.$descriptor[$orderBy]["field_sql"],"ASC");
            }
        }

        //5. groupBy

        if(isset($getVar["group_by"]))
        {
            foreach($getVar["group_by"] as $groupBy)
            {
                $this->groupBy($groupBy);
                //$this->orderBy($descriptor[$orderBy]["table"].'.'.$descriptor[$orderBy]["field_sql"],"ASC");
            }
        }

        //5. comptage=Group by mode actif

      /*  if($is_group_by)
        {
            foreach($group_by as $entityGroupBy)
            {
                //liste des selects pour le group by
                $entityGroup=$this->getOneEntities($entityGroupBy);
                $tableGroup=$entityGroup->table_primary;
                $keyGroup=$entityGroup->key_primary;
                $labelGroup=$entityGroup->label;

                $fieldSQLArray[]=" COUNT(DISTINCT $tableGroup.$keyGroup) as nombre_$entityGroupBy";
                $labelsArray[]="Nombre $labelGroup";
                if(!in_array($entityGroupBy,$entitiesUsed))
                {
                    array_push($entitiesUsed,$entityGroupBy);         
                }
                //les group by sur l'ensemble des champs
                foreach($fields as $groupBy)
                {
                    $this->groupBy($descriptor[$groupBy]["table"].'.'.$descriptor[$groupBy]["field_sql"]);
                }

                
            }
        }*/

        //7. on opere les jointures nécessaires
        $entitiesJoin=$entitiesUsed; //les entities à joindre
        $entitiesDone=[]; //Les entities déjà faite
        //$entityFrom entity de départ du système
        array_push($entitiesDone,$entityFrom);
      
       // debug($entitiesJoin);
        //on boucle d'abord sur $entitiesDone

        include(APPPATH."Config/BanConfig/Config_jointure.php");
        foreach($entitiesJoin as $entityJoin)
        {
          //  echo "<h4>tour avec $entityJoin</h4> <br>";
          // debug($entitiesDone);
          
              // debug($entityJoin);
               // $params_join=$this->get_entities_params_jointure($entityFrom,$entityJoin);

                if(isset($jointure[$entityFrom][$entityJoin]))
                {
                    $params_join=$jointure[$entityFrom][$entityJoin];
                }
                else
                {
                    $params_join=NULL;
                }
                

           
                 
                    //debug(json_decode($var),true);



                if(!is_null($params_join))
                {
                   
                    //$left_conditions=json_decode($params_join["left_condition"]);
                    $left_conditions=$jointure[$entityFrom][$entityJoin];
                  //debug($left_conditions);

                   if(is_array($left_conditions))
                   {
                        foreach($left_conditions as $left_condition)
                        {
                            //echo "je suis un ensemble de condition";
                            //debug($left_condition);
                            if(!in_array( $left_condition["table"],$entitiesDone))
                            {
                                $this->join($left_condition["table"],$left_condition["condition"],"left"); 
                            }   
                                array_push($entitiesDone, $left_condition["table"]);
                            }
                        
                            //debug($left_condition);
                        }
                    }


                                      
                    
        }
//$this->orderBy("farmer.id_farmer DESC");
     //$this->groupBy("contact.id_contact");

        //FINAL, on execute la requête
        $this->select(implode(", ",$fieldSQLArray));
        $this->where($where);    
     
        if(!is_null($offset))
        {
            $results=$this->get(1000,$offset)->getResult();
            
        }
        else
        {
            $results=$this->paginate(200);
        }

        return 
        [
            "labels"=>$labelsArray,
            "query"=>$this->getLastQuery(),
            "results"=>$results
        ];
    }

    public function save_query($getVar)
    {
        $builder=$this->db->table("user_requete");
        $data_insert=[];
        $exclude_field=["honeypot","ci_session","csrf_cookie_name"];
        foreach($getVar as $k=>$v)
        {
            if(!in_array($k,$exclude_field))
            {

                if($k=="query"){$v=html_entity_decode(str_replace("LIMIT 200",null,$v));};
                $data_insert[$k]=$v;
            }
        }

        if(!empty($data_insert)) :

            $data_insert['date_create'] = date("Y-m-d H:i:s");
            $data_insert['id_user'] = session('loggedUserId');
            $builder->insert($data_insert);
            return $this->db->insertID();
        endif;

        return null;
    }

    public function update_query($getVar,$id_requete)
    {
        $builder=$this->db->table("user_requete");
        $data_insert=[];

        $exclude_field=["honeypot","ci_session","csrf_cookie_name"];
        foreach($getVar as $k=>$v)
        {
            if(!in_array($k,$exclude_field))
            {
                if($k=="query"){$v=html_entity_decode(str_replace("LIMIT 200",null,$v));};
                $data_insert[$k]=$v;
            }
        }

        if(!empty($data_insert)) :

            $data_insert['date_create'] = date("Y-m-d H:i:s");
            $data_insert['id_user'] = session('loggedUserId');
            $builder->where("id_requete",$id_requete);
            $builder->update($data_insert);
            return true;
        endif;

        return null;
    }
    
   

    public function get_entities_params_jointure($entity,$entity2)
    {
        $param=NULL;
        $builder=$this->db->table("ban_entities_params_jointure");
        $builder->where("entity",$entity);
        $builder->where("entity2",$entity2);

        $params = $builder->get()->getRow();


        if(!empty($params))
       {
            //$param["join_entity"]=$this->get_table_primary($params->join_entity);
          
            //$param["entity"]=$this->get_table_primary($params->entity);
            $param["entity"]=$params->entity;
           
            $param["left_condition"]=$params->left_condition;
       }
     

       return $param;
    }

    public function get_table_primary($entity)
    {
       
        $entityJoinData=$this->getOneEntities($entity);
        return $entityJoinData->table_primary;
    }

     //Gesiton des tables provisoires

     public function get_uri_provisoire($id_requete_provisoire)
     {
          $builder=$this->db->table("user_requete_provisoire");
          $builder->where("id_requete_provisoire",$id_requete_provisoire);

          $result=$builder->get()->getRow();

          if(isset($result->uri)&&!empty($result->uri))
          {
              return $result->uri;
          }

          else
          {
              return null;
          }

     }

     public function insert_provisoire($uri,$id_requete_provisoire=0)
     {
        $is_update=FALSE;

        if($id_requete_provisoire>0)
        {
            $builder=$this->db->table("user_requete_provisoire");
            $builder->where("id_requete_provisoire",$id_requete_provisoire);
            $result=$builder->get()->getRow();

            if(!empty($result))
                $is_update=TRUE;
        }
       

        if($is_update)
        {
            $builder=$this->db->table("user_requete_provisoire");
            $data["uri"]=$uri;
            $builder->where("id_requete_provisoire",$id_requete_provisoire);
            $builder->update($data);
            return $id_requete_provisoire;

        }
        else{

            $builder=$this->db->table("user_requete_provisoire");
            $data["uri"]=$uri;
            $builder->insert($data);
            return $this->db->InsertId();

        }

       
     }
   
    
}
