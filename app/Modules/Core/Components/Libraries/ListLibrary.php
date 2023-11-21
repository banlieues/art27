<?php

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\JsonLibrary;
use Config\Autoload;
use Tesorus\Libraries\TesorusLibrary;

class ListLibrary extends BaseLibrary
{
    protected $t_cell = 'tesorus_cell';

    public function __construct($namespace)
    {
        parent::__construct($namespace);
        
        helper('file');

        $this->db = db_connect();
        $this->namespace = explode('\\', $namespace)[0];

        // $json_l = new JsonLibrary($namespace);
        // $this->lists = $json_l->getLists();
        $this->lists = $this->get_lists();
    }

    public function get_lists($active_items=true)
    {
        $ConfigAutoload = new Autoload();
        $psr4 = $ConfigAutoload->psr4;
        $url = $psr4[$this->namespace] . '/Config/Json/list.json';
        if(!file_exists($url)) return false;

        $lists = json_decode(file_get_contents($url));

        $datalists = (object) [];
        foreach($lists as $ref=>$list) :
            $datalist = [];
            if(is_object($list) && !empty($list->table)) :
                if(!empty(preg_match('/^fe_road_/', $list->table))) :
                    $datalist = $this->get_datas_from_table_road($list->table);
                else :
                    $datalist = $this->get_datas_from_table_list($list, $active_items);
                endif;
            else :
                $i = 0;
                foreach($list as $row) :
                    $datalist[$row->rank-1] = $row;
                endforeach;
                ksort($datalist);
            endif;
            $datalists->$ref = $datalist;
        endforeach;

        return $datalists;
    }

    public function get_datas_from_table_road($road_table)
    {
        $q = $this->db->table($road_table);
        $columns = $this->db->getFieldNames($road_table);
        if(!in_array('label_fr', $columns) && in_array('label', $columns)) :
            $q->select('*');
            $q->select('label as label_fr');
        endif;
        if(in_array('is_actif', $columns)) $q->where('is_actif', 1);
        if(in_array('rank', $columns)) $q->orderBy('rank');
        $q->join($this->t_cell, $this->t_cell . '.id_cell = ' . $road_table . '.id_cell');
        $datas = $q->get()->getResult();

        return $datas;
    }

    
    public function get_datas_from_table_list($list, $active_items=true)
    {
        $q = $this->db->table($list->table);
        $columns = $this->db->getFieldNames($list->table);

        if(in_array('id', $columns)) $q->select('id');
        elseif(!empty($list->id)) $q->select($list->id . ' as id');

        if(in_array('label_fr', $columns)) $q->select('label_fr');
        elseif(!empty($list->label_fr)) $q->select($list->label_fr . ' as label_fr');
        elseif(in_array('label', $columns)) $q->select('label as label_fr');

        if(in_array('is_actif', $columns) && $active_items==true) $q->where('is_actif', 1);
        if(!empty($list->where)) $q->where($list->where);

        if(in_array('rank', $columns)) $q->orderBy('rank');
        if(!empty($list->rank)) $q->orderBy($list->rank);

        $datas = $q->get()->getResult();

        return $datas;
    }

    // public function get_datas_from_table_list($table)
    // {
    //     $query = $this->db->table($table);
    //     $columns = $this->db->getFieldNames($table);
    //     if(!in_array('label_fr', $columns) && in_array('label', $columns)) :
    //         $query->select('*');
    //         $query->select('label as label_fr');
    //     endif;
    //     if(in_array('is_actif', $columns)) $query->where('is_actif', 1);
    //     if(in_array('rank', $columns)) $query->orderBy('rank');
    //     $datas = $query->get()->getResult();

    //     return $datas;
    // }

    public function get_selected_object($data, $ref, $separator=', ')
    {
        // if(empty($this->lists->$ref)) return false;

        $object = (object) ['value'=>null, 'label'=>null];
        if(is_array($data)) :      
            $object->value = json_encode($data);
            $object->label = implode($separator, $this->get_selected_labels($data, $ref));
            $object->array = $this->get_selected_value($data, $ref);
        elseif((is_string($data) && ctype_digit(trim($data))) || is_integer($data)) :
            $object->value = $data;
            $object->label = $this->get_selected_label($data, $ref);
        endif;

        return $object;
    }

    
    public function get_selected_labels($ids, $ref)
    {
        if(empty($ids)) return false;
        
        $list = $this->get_lists(false)->$ref;
        if(empty($list)) return false;

        $labels = [];
        if(preg_match('/^ids_road_(\w+)/', $ref, $matches)):
            $TesorusLibrary = new TesorusLibrary();
            $road_name = $matches[1];
            foreach($ids as $id) :
                $labels[] = $TesorusLibrary->get_path_by_id_road($road_name, $id);
            endforeach;
        else :
            foreach($list as $elem) :
                if(in_array($elem->id, $ids)) $labels[] = $elem->label_fr;
            endforeach;
        endif;

        return $labels;
    }

    public function get_selected_label($id, $ref)
    {
        $list = $this->get_lists(false)->$ref;
        if(empty($list)) return false;

        $label = '';
        foreach($list as $elem) : 
            if($elem->id == $id) : 
                $label = $elem->label_fr;
                break;
            endif;
        endforeach;

        return $label;
    }

    public function get_selected_value($ids, $ref)
    {
        $list = $this->get_lists(false)->$ref;
        if(empty($list)) return false;

        $array = [];
        foreach($list as $elem) :
            foreach($ids as $id) :
                if($elem->id == $id) :
                    $array[] = $elem;
                    break;
                endif;
            endforeach;
        endforeach;

        return $array;
    }

    public function get_list_by_ref($ref)
    {
        if(empty($this->lists->$ref)) return false;

        return $this->lists->$ref;
    }
}