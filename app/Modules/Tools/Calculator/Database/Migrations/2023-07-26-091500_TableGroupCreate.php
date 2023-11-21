<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;
use Calculator\Libraries\AdminLibrary;
use Calculator\Models\AdminModel;
use Tesorus\Libraries\TesorusLibrary;

class TableGroupCreate extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);

        $this->TesorusLibrary = new TesorusLibrary();
        $this->AdminLibrary = new AdminLibrary();
        $this->AdminModel = new AdminModel();
    }
    
    public function up() 
    {
        if($this->db->tableExists($this->t_group)) return false;

        debug('---------- START ' . basename(__FILE__) . ' ----------');

        $this->TableGroupCreate();
        $this->TableGroupPopulate();
        $this->TableGroupUpdateMeasure();

        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableGroupCreate()
    {
        if($this->db->tableExists($this->t_group)) :
            $this->forge->dropTable($this->t_group);
            dbdebug();
        endif;
        
        $fields = [
            'id_group' => [ 'type' => 'int', 'auto_increment' => true, ],
            'isActive' => [ 'type' => 'tinyint', 'default' => '1', ],
            'label_fr' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, ],
            'measure' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true],
            'id_road_parent' => [ 'type' => 'int', 'null' => false, ],
            'ids_road_children' => [ 'type' => 'text', 'null' => true, ],
            'rank' => [ 'type' => 'int', 'null' => true, ],
            'annotation_fr' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'annotation_nl' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_group', 'id_group');
        $this->forge->createTable($this->t_group);
        dbdebug();
    }

    private function TableGroupPopulate()
    {
        $rows = convert_file_csv_to_array($this->path . 'Documents/import_calculator_estimation.csv');
        $rows[-1] = null;
        // debugd($rows);
        $i = 0;
        $group_rank = 0;
        foreach($rows as $row) :
            if(!empty((array) $row)) :

                if(!$rows[$i-1] || trim($row[1])!=trim($rows[$i-1][1])) $group_rank = 0;
                if($rows[$i-1]) debug($rows[$i-1][2]);
                // $id_road = $this->TableRoadPopulateOne('calculator_group', $row, $rows[$i-1]);
                $id_group = $this->TableGroupPopulateOne($row, $rows[$i-1], $group_rank);
                if(!$rows[$i-1] || trim($row[2])!=trim($rows[$i-1][2])) $group_rank++;
            endif;
            $i++;
        endforeach;
    }

    private function TableGroupUpdateMeasure()
    {
        $groups = database_decode($this->db->table($this->t_group)->get()->getResult());
        foreach($groups as $group) :
            if(empty($group->ids_road_children)) continue;
            $roads = [];
            foreach($group->ids_road_children as $id_road) :
                $roads[] = $this->db->table($this->t_road)->where('id_road', $id_road)->get()->getRow();
            endforeach;
            if(count(array_unique(array_column($roads, 'measure')))==1) :
                $this->db->table($this->t_group)->set('measure', $roads[0]->measure)->where('id_group', $group->id_group)->update();
                dbdebug();
            endif;
        endforeach;
    }

    // private function ImportRoadGroup($row, $id_group)
    // {
    //     $newlabels = [mb_strtolower(trim($row[0])), mb_strtolower(trim($row[1])), mb_strtolower(trim($row[3]))];
    //     if(!empty($row[4])) $newlabels[] = mb_strtolower(trim($row[4]));

    //     $roads = database_decode($this->db->table($this->t_road)->get()->getResult());
    //     foreach($roads as $road) :
    //         $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $road->id_road);
    //         $i=0;
    //         foreach($labels as $label) :
    //             $labels[$i] = mb_strtolower(trim($label));
    //             $i++;
    //         endforeach;
    //         debug(implode(', ', $labels));
    //         if(array_intersect($newlabels, $labels)==$newlabels) :
    //             $data = (object) [];
    //             $ids_group = !empty($road->ids_group) ? $road->ids_group : [];
    //             $ids_group[] = $id_group;
    //             $data->ids_group = $ids_group;
    //             $this->db->table($this->t_road)->set(database_encode($this->t_road, $data))->where('id_road', $road->id_road)->update();
    //             dbdebug();
    //             break;
    //         endif;
    //     endforeach;
    // }

    private function TableGroupColumnRoadsChildrenPopulate($row)
    {
        if(empty($row[0]) || empty($row[4])) return null;

        $group = [$row[0], $row[1], $row[4]];
        if(!empty($row[5])) $group[] = $row[5];
        if(!empty($row[6])) $group[] = $row[6];
        $grouplower = [];
        foreach($group as $elem) $grouplower[] = mb_strtolower(trim($elem));

        $q = $this->db->table($this->t_road);
        $q->select("
            $this->t_road.id_road,
            $this->t_cell.id_cell,
        ");
        $q->distinct();
        $q->join("$this->t_cell", "$this->t_cell.id_cell = $this->t_road.id_cell", 'left');
        $q->join("$this->t_road as p_road", "p_road.id_road = $this->t_road.id_road_parent", 'left');
        $q->join("$this->t_cell as p_cell", "p_cell.id_cell = p_road.id_cell", 'left');
        $q->join("$this->t_road as pp_road", "pp_road.id_road = p_road.id_road_parent", 'left');
        $q->join("$this->t_cell as pp_cell", "pp_cell.id_cell = pp_road.id_cell", 'left');
        $q->join("$this->t_road as ppp_road", "ppp_road.id_road = pp_road.id_road_parent", 'left');
        $q->join("$this->t_cell as ppp_cell", "ppp_cell.id_cell = ppp_road.id_cell", 'left');
        $q->where("lower($this->t_cell.label_fr)", mb_strtolower(trim($row[1])));
        $q->orWhere("lower(p_cell.label_fr)", mb_strtolower(trim($row[1])));
        $q->orWhere("lower(pp_cell.label_fr)", mb_strtolower(trim($row[1])));
        $q->orWhere("lower(ppp_cell.label_fr)", mb_strtolower(trim($row[1])));
        $roads = database_decode($q->get()->getResult());
        // dbdebug();

        debug('--- ' . implode(' > ', $grouplower) . ' ---');
        foreach($roads as $road) :
            $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $road->id_road);
            if(count($labels)>2) :
                $labelslower = [];
                foreach($labels as $label)  $labelslower[] = mb_strtolower(trim($label));
                debug(implode(' > ', $labelslower));
                if(array_intersect($grouplower, $labelslower)==$grouplower) :
                    $id_road = $road->id_road;
                    break;
                endif;
            endif;
        endforeach;

        return !empty($id_road) ? $id_road : null;
    }

    private function TableGroupPopulateOne($row, $rowbefore, $rank)
    {
        $is_different = 0;
        for($i=0; $i<3; $i++) :
            if(!empty($rowbefore[$i]) && mb_strtolower(trim($row[$i]))!=mb_strtolower(trim($rowbefore[$i]))) :
                $is_different = 1;
                break;
            endif;
        endfor;

        $data = (object) [];
        
        if(empty($rowbefore) || (!empty($row) && !empty($is_different))) :
            $RoadModel = new \Tesorus\Models\RoadModel('calculator');
            $roads = $RoadModel->get_roads_active_flat();
            foreach($roads as $road) :
                $road->labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $road->id_road);
                if(
                    count($road->labels) == 2 &&
                    mb_strtolower(trim($road->labels[0]))==mb_strtolower(trim($row[0])) && 
                    mb_strtolower(trim($road->labels[1]))==mb_strtolower(trim($row[1]))
                ) :
                    $data->id_road_parent = $road->id_road;
                    break;
                endif;
            endforeach;
            $data->label_fr = ucfirst(trim($row[2]));
            $data->rank = $rank;
            // $data->ids_road_children = $this->ImportGroupGetIdsRoadChildren($row);
            $this->db->table($this->t_group)->set(database_encode($this->t_group, $data))->insert();
            dbdebug();
            $this->id_group = $this->db->insertID();
        endif;

        $group = database_decode($this->db->table($this->t_group)->where('id_group', $this->id_group)->get()->getRow());
        $ids_road_children = !empty($group->ids_road_children) ? $group->ids_road_children : [];
        $id_road_child_new = $this->TableGroupColumnRoadsChildrenPopulate($row);
        if(!empty($id_road_child_new)) $ids_road_children[] = $id_road_child_new;
        if(!empty($ids_road_children)) :
            $this->db->table($this->t_group)->set('ids_road_children', json_encode($ids_road_children,  JSON_NUMERIC_CHECK))->where('id_group', $group->id_group)->update();
            dbdebug();
        endif;
    }
}



