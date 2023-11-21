<?php

namespace Modelisation\Models;

use Base\Models\BaseModel;
use DataView\Models\dataViewConstructorModel;

class ModelisationModel extends BaseModel
{
    protected $entities=["contact", "contact_profil"];
    protected $excludeField=["@#<hr>"];
    public $exclude_field_sql=["sqlsql"];
    public $table = 'table';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->request = \Config\Services::request();
    }

    public function getFieldsGestion($type)
    {
        $builder = $this->db->table("ban_fields");
        $builder->where("type",$type);
        $builder->orderBy("label");

        return $builder->get()->getResult();
    }

    public function getfieldsSelected($type, $entity)
    {
        $fieldsSelected = [];
        $builder = $this->db->table("ban_components_$entity");
        $builder->select("fields");
        $builder->where("type",$type);
        $fields = $builder->get()->getResult();
        if(!empty($fields)) :
            foreach($fields as $field) :
                $fieldsArray = explode(",", $field->fields);
                foreach($fieldsArray as $fieldArray) :
                    array_push($fieldsSelected, $fieldArray);
                endforeach;
            endforeach;
        endif;

        return $fieldsSelected;
    }

    public function FieldSave($mode, $post, $total_item=NULL)
    {
        if($mode=="update") :
            $builder = $this->db->table("ban_fields");
            $data_update["label"] = trim($post->label);
            $data_update["rule"] = $this->saveRules($post);
            $where_update["field_index"] = $post->field_index;
            $builder->update($data_update, $where_update);

            //2. On crée le champ dans les tables
            //2.1. Déterminer la table liée à l'entité en cours
        else :
            $builder = $this->db->table("ban_fields");
            $data_insert["field_index"] = trim(str_replace("-", "_", $post->field_index));
            $data_insert["label"] = trim($post->label);
            $data_insert["rule"] = $this->saveRules($post);
            $data_insert["type_field"] = $post->type_field;
            $data_insert["field_sql"] = str_replace("-","_", $data_insert["field_index"]);
            $data_insert["type"] = $post->entity;
            $data_insert["table"] = $this->getTableEntities($data_insert["type"]);
            
            switch($data_insert["type_field"]) :
                case "select":
                case "radio":
                case "check":
                    $data_insert["table_list"]="crm_list_".$data_insert["field_sql"];
                    $data_insert["key_list"]="id";
                    $data_insert["label_list"]="label";
                    $data_insert["order_list"]="rank,label";
                break;
            endswitch;
            
            $builder->insert($data_insert);

            //ON modifie le model data
            $this->createFields($data_insert);
        endif;

        switch($post->type_field) :
            case "check":
            case "radio":
            case "select":
                //debug($getVar,TRUE);
                if($mode=="insert") :
                    $post->table_list=$data_insert["table_list"];
                endif;

                if($this->db->tableExists($post->table_list)) :
                    for($num_item_list=1;$num_item_list<=$total_item;$num_item_list++) :
                        $builder = $this->db->table($post->table_list);
                        $data_updateItem["label"] = $post->{"label_item_##$num_item_list"};

                        if(isset($post->{"ref_item_##$num_item_list"})) :
                            $data_updateItem["ref"] = $post->{"ref_item_##$num_item_list"};
                        endif;

                        $data_updateItem["rank"] = $num_item_list;
                        $data_updateItem["is_actif"] = isset($post->{"is_actif_item_##$num_item_list"}) ? 0 : 1;

                        if($post->{"id_item_##$num_item_list"}=='0') :
                            $builder->insert($data_updateItem);
                        else :
                            $where_updateItem = [];
                            $where_updateItem["id"]=$post->{"id_item_##$num_item_list"};
                            $builder->update($data_updateItem,$where_updateItem);
                        endif;
                    endfor;
                endif;
            break;
        endswitch;
    }

    public function createFields($data_insert)
    {
        $forge = \Config\Database::forge();

        //1. On ajoute le nouveau champ

        switch($data_insert["type_field"])
        {
            case "key": $dataForge = [ 'type' => 'INT', ]; break;
            case "input": $dataForge = [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true, ]; break;
            case "textarea": $dataForge = [ 'type' => 'TEXT', 'null' => true, ]; break;
            case "email": $dataForge = [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true, ]; break;
            case "int": $dataForge = [ 'type' => 'INT', 'null' => true, ]; break;
            case "price": $dataForge = [ 'type' => 'INT', 'null' => true, ]; break;
            case "date": $dataForge = [ 'type' => 'DATE', 'null' => true, ]; break;
            case "birthday": $dataForge = [ 'type' => 'DATE', 'null' => true, ]; break;
            case "hour": $dataForge = [ 'type' => 'TIME', 'null' => true, ]; break;  
            case "select": $dataForge = [ 'type' => 'INT', 'null' => true, ]; break;
            case "radio": $dataForge = [ 'type' => 'INT', 'null' => true, ]; break;
            case "check": $dataForge = [ 'type' => 'TEXT', 'null' => true, ]; break;
            default: $dataForge = [ 'type' => 'TEXT', 'null' => true, ]; break;
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
                        $fieldsList = [
                            'label' => [ 'type' => 'VARCHAR', 'constraint' => '255', ],
                            'rank' => [ 'type' =>'INT', ],
                            'is_actif' => [ 'type' => 'INT', 'default' => 1, ],
                            'date_modification' => [ 'type' => 'TIMESTAMP', 'null' => true, ],
                            'id_user' => [ 'type' => 'INT', 'null' => true, ],
                        ];
                        $forge->addField($fieldsList);
                        $forge->addField('id');
                        $forge->createTable($data_insert["table_list"]);
                    break;
                }
            }
        }
    }

    public function getTableEntities($type)
    {
        $DataViewModel = new dataViewConstructorModel();
        $table = $DataViewModel->getOneEntities($type);
        return $table->table_primary;
    }

    protected function saveRules($post)
    {
        $rule=NULL;
        $is_required=FALSE;
        if(isset($post->rule_required) && $post->rule_required==1)
        {
            $rule.="trim|required";
            $is_required=TRUE;
        }

        switch($post->type_field)
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
}