<?php

namespace Modelisation\Models;

use Base\Models\BaseModel;
use DataView\Models\dataViewConstructorModel;

class DeleteModel extends BaseModel
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

    public function FieldDelete($fieldIndex)
    {
        //On efface le field
        $builder = $this->db->table("ban_fields");
        $builder->where(array("field_index"=>$fieldIndex));
        $field = $builder->get()->getRow();
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

          
        }
    }
  
    public function verifFieldIndexFormComponents()
    {
        $entities = $this->get_list_entity();

        foreach($entities as $entity) $this->oneVerifFieldIndexFormComponents($entity);
    }

    private function get_list_entity()
    {
        
        $entities = $this->db->table("ban_entities_params")->get()->getResult();

        $list_entity = [];
        foreach($entities as $entity) $list_entity[] = $entity->type;

        return $list_entity;
    }


    private function oneVerifFieldIndexFormComponents($entity)
    {
        if ($this->db->tableExists('ban_components_'.$entity.'_fields_index')) :
            $builder=$this->db->table('ban_components_'.$entity.'_fields_index');
            $builder->where("field_index NOT IN (SELECT field_index FROM ban_fields)");
            $fields = $builder->get()->getResult();

            foreach($fields as $field) :
                if(!in_array($field->field_index,$this->excludeField)) :
                    $builder->delete(["id_component_field_index" => $field->id_component_field_index]);
                    $this->setComponentsFieldIndex($field->id_component,$entity);
                endif;
            endforeach;
        endif;
    }

    private function setComponentsFieldIndex($id_components,$entity)
    {
        $table = "ban_components_$entity";
        $table_index = $table."_fields_index";
        $fields = $this->db->table($table_index)->where("id_component",$id_components)->get()->getRow();

        if(!empty($fields)) :
            $this->db->query("
                UPDATE $table 
                SET fields = (
                    SELECT GROUP_CONCAT(field_index SEPARATOR ',') 
                    FROM $table_index 
                    WHERE $table_index.id_component = $id_components ORDER BY id_component_field_index
                ) 
                WHERE $table.id_components = $id_components
            ");
        else :
            $this->db->query("UPDATE $table SET fields='' WHERE $table.id_components=$id_components");
        endif;
    }

    private function verifFieldIndexFormInjected()
    {
    
        $builder = $this->db->table('ban_injected_form_fields_index');
        $builder->where("field_index NOT IN (SELECT field_index FROM ban_fields)");
        $fields = $builder->get()->getResult();

        foreach($fields as $field) :
            if(!in_array($field->field_index,$this->excludeField)) :
                //print_r($field);
                $builder->delete(["id_injected_form_field" => $field->id_injected_form_field]);
                $this->setInjectedFieldIndex($field->id_injected_form);
            endif;
        endforeach;
    }

    private function setInjectedFieldIndex($id_injected_form)
    {
        $builder = $this->db->table("ban_injected_form_fields_index");
        $builder->where("id_injected_form", $id_injected_form);
        $fields = $builder->get()->getRow();

        if(!empty($fields)) :
            $this->db->query("
                UPDATE ban_injected_form 
                SET fields = (
                    SELECT GROUP_CONCAT(field_index SEPARATOR ',') 
                    FROM ban_injected_form_fields_index 
                    WHERE ban_injected_form_fields_index.id_injected_form = $id_injected_form 
                    ORDER BY id_injected_form_field
                ) 
                WHERE ban_injected_form.id_injected_form = $id_injected_form
            ");
        else :
            $this->db->query("
                UPDATE ban_injected_form 
                SET fields='' 
                WHERE ban_injected_form.id_injected_form = $id_injected_form
            ");
        endif;
    }

}