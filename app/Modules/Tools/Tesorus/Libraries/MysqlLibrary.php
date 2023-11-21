<?php

namespace Tesorus\Libraries;

use Tesorus\Libraries\TesorusLibrary;
use Base\Libraries\BaseLibrary;
use Components\Libraries\DatabaseLibrary;
use Translator\Libraries\TranslatorLibrary;

class MysqlLibrary extends BaseLibrary
{   
    public function __construct()
    {
        parent::__construct();
        $globals = new \Tesorus\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->db_l = new DatabaseLibrary(__NAMESPACE__);
        $this->TesorusLibrary = new TesorusLibrary();
        $this->forge = \Config\Database::forge(); 
    }

    public function check_database($roads_selected)
    {
        $this->check_database__fe_feature();
        // check database fe_translation must be called before fe_road_...
        foreach($roads_selected as $road_name) :
            $this->check_database__fe_road($road_name);
        endforeach;
    }

    public function check_database__fe_feature()
    {
        $file = $this->path . 'Config/Json/cell/table.json';
        $this->db_l->check_database_table_common($this->t_cell, $file);
    }

    public function check_database__fe_road($road_name)
    {
        $file = $this->path . 'Config/Json/road/table.json';
        $road_table = $this->TesorusLibrary->TableRoadGet($road_name);
        // $table = $this->{'t_road_' . $road_name};
        $this->db_l->check_database_table_common($road_table, $file);

        if($this->db->table($road_table)->countAllResults()==0 && file_exists($this->path . 'Config/Json/road_' . $road_name . '/init.json')) :
            $this->import_roads($road_name);
            $this->translator_road($road_name);
        endif;
    }

    private function translator_road($road_name)
    {
        $transl_l = new TranslatorLibrary();
        
        $files = [];
        $files[] = $this->path . 'Config/Json/road_' . $road_name . '/init.json';
        $files[] = $this->path . 'Config/Json/list.json';

        $transl_l->import_labels_from_files($files);
    }

    private function import_roads($road_name)
    {
        $db_cells = $this->db->table($this->t_cell)->get()->getResult();
        $db_ref_list = [];
        foreach($db_cells as $db_cell) :
            $db_ref_list[] = htmlspecialchars(trim($db_cell->label_fr));
        endforeach;

        $this->import_roads_recursive($road_name, $db_ref_list);
    }

    private function import_roads_recursive($road_name, $db_ref_list, $roads=null, $id_road_parent=0)
    {
        if(empty($roads)) $roads = json_decode(file_get_contents($this->path . 'Config/Json/road_' . $road_name . '/init.json'));

        $i = 0;
        foreach($roads as $road):
            $label_fr = htmlspecialchars(trim($road->label_fr));
            if(!in_array($label_fr, $db_ref_list)) : 
                $id_cell = $this->import_roads_insert_cell_line($road);
                $db_ref_list[] = $road->label_fr;
            else :
                $id_cell = $this->db->table($this->t_cell)->where('label_fr', $road->label_fr)->get()->getResult()[0]->id_cell;
            endif;

            $has_text = isset($road->has_text) ? $road->has_text : null;
            $id_road = $this->import_roads_insert_road_line($road_name, $id_cell, $id_road_parent, $i, $has_text);

            if(!empty($road->children)) $db_ref_list = $this->import_roads_recursive($road_name, $db_ref_list, $road->children, $id_road);

            $i++;
        endforeach;

        return $db_ref_list;
    }

    private function import_roads_insert_cell_line($road)
    {
        $data = (object) [];
        $data->label_fr = $road->label_fr;
        $this->db->table($this->t_cell)->insert((array) $data);
        $id_cell = $this->db->insertID();

        return $id_cell;
    }

    private function import_roads_insert_road_line($road_name, $id_cell, $id_road_parent, $rank, $has_text=null)
    {
        $data = (object) [];
        $data->id_cell = $id_cell;
        $data->id_road_parent = $id_road_parent;
        $data->rank = $rank;

        if(!empty($has_text)) $data->has_text = $has_text;
        $RoadModel = new \Tesorus\Models\RoadModel($road_name);
        $id_road = $RoadModel->RoadSave($data);

        return $id_road;
    }

    public function print_columns_from_table()
    {
        $file = $this->path . 'Config/Json/road/table.json';
        $tables = $this->roads;
        array_unshift($tables, 'cell');
        foreach($tables as $name) :
            $table = 'fe_' . $name;
            if(!file_exists($file)) $this->db_l->print_columns_from_table($table, $file);
        endforeach;
    }

    private function execute_queries($queries)
    {
        foreach($queries as $query) :
            if(!$this->db->query($query)) echo '<br> ERROR - ' . $query . '<br>';
        endforeach;
    }
}

