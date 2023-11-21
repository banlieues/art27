<?php

namespace Liste\Models;

use Base\Models\BaseModel;

class ListeModel extends BaseModel 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }   
     
    // //liste des tables de la db
    // public function displayDB()
    // {
    //     $full_tables =  $this->db->list_tables();
    //     $tables = [];
    //     foreach($full_tables as $table) :
    //         if(preg_match('/(list)/', $table) > 0 && preg_match('/_v_/', $table) == 0) $tables[] = $table;
    //     endforeach;
    //     return $tables;
    // }
    
    public function RowsGet($table)
    {       
        $q = $this->db->table($table);        
        if($this->db->fieldExists('is_actif', $table)) $q->orderBy('is_actif', 'DESC');
        if($this->db->fieldExists('rank', $table)) $q->orderBy('rank', 'ASC');
        $rows = $q->get()->getResult();

        return $rows;
    }

    public function RowGetEmptyData($table)
    {
        $db_fields = $this->db->getFieldNames($table);
        $row = (object) [];
        foreach($db_fields as $field) $row->$field = null;

        return $row;
    }

    public function RowSave($table, $data, $id=null)
    {
        $data->updated_by = session('loggedUserId');
        if(empty($id)) :
            $data->created_by = session('loggedUserId');
            foreach(['label', 'label_fr', 'name_fr', 'status_name', 'localite_fr', 'origine_fr', 'type'] as $label_field) :
                if(!empty($data->$label_field)) :
                    $data->label_original = convert_utf8_to_url($data->$label_field);
                    break;
                endif;
            endforeach;
            $max_rank = $this->db->table($table)->selectMax('rank')->get()->getRow()->rank;
            $data->rank = !empty($max_rank) ? $max_rank+1 : 0;

            $this->db->table($table)->set(database_encode($table, $data))->insert();
        else :
            $pk = get_primary_key($table);
            $data->updated_at = date('Y-m-d H:i:s');
            $this->db->table($table)->set(database_encode($table, $data))->where($pk, $id)->update();
        endif;
    }

    function RowGet($table, $id)
    {
        $q = $this->db->table($table);
        $q->select("$table.*");
        $q->select("user_updated.prenom as prenom_updated, user_updated.nom as nom_updated");
        $q->select("user_created.prenom as prenom_created, user_created.nom as nom_created");
        $q->join('users user_created', $table . '.created_by = user_created.id_user', 'left');
        $q->join('users user_updated', $table . '.updated_by = user_updated.id_user', 'left');
        $q->where(get_primary_key($table), $id);

        $row = database_decode($q->get()->getRow());

        return $row;
    }
}
