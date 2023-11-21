<?php

namespace Company\Database\Migrations;

use Base\Database\BaseMigration;
use Tesorus\Models\CellModel;

class DebugTesorus_231108 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->DebugTesorus();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    // private function DebugCellConvertByTesorus($road_name)
    // {
    //     $q = $this->db->table("fe_road_$road_name");
    //     $q->select("fe_road_$road_name.*, fe_feature.*, feature_parent.label_fr as parent_label_fr");
    //     $q->join('fe_feature', "fe_feature.id_feat = fe_road_$road_name.id_feat", 'left');
    //     $q->join("fe_road_$road_name as parent", "parent.id_road = fe_road_$road_name.id_road_parent", 'left');
    //     $q->join('fe_feature as feature_parent', "feature_parent.id_feat = parent.id_feat", 'left');
    //     $q->orderBy('id_road');
    //     $roads = $q->get()->getResult();

    //     // debug($roads[0]);

    //     $converts = [];
    //     foreach($roads as $road) :
    //         $cells = $this->db->table("tesorus_cell")
    //             ->like('LOWER(label_fr)', mb_strtolower($road->label_fr), 'before')
    //             ->like('LOWER(label_fr)', mb_strtolower($road->label_fr), 'after')
    //             ->get()->getResult();
    //         if(count($cells) > 1) :
    //             debug('--- FIND CELLS DUBLONS ---');
    //             debugd($cells);
    //         elseif(count($cells) == 1) :
    //             $cell = $cells[0];
    //             // debug($cell);
    //             if($cell->id_cell != $road->id_feat) :
    //                 $convert = (object) [];
    //                 $convert->id_road_old = $road->id_road;
    //                 $convert->id_feat = $road->id_feat;
    //                 $convert->id_cell = $cell->id_cell;
    //                 $converts[] = $convert;
    //             endif;
    //         endif;
    //     endforeach;

    //     return $converts;
    // }

    private function DebugTesorus()
    {
        $this->DebugTesorusByTable('work');
        $this->DebugTesorusByTable('eco_impact');
    }

    private function DebugTesorusByTable($road_name)
    {
        $CellModel = new CellModel();

        $this->db->query("DROP TABLE IF EXISTS tesorus_road_" . $road_name . "_new;");
        dbdebug();
        $this->db->query("CREATE TABLE tesorus_road_" . $road_name . "_new SELECT * FROM tesorus_road_$road_name;");
        dbdebug();
        $this->db->query("TRUNCATE TABLE tesorus_road_" . $road_name . "_new;");
        dbdebug();
        // $this->db->query("ALTER TABLE tesorus_road_" . $road_name . "_new DROP PRIMARY KEY;");
        // dbdebug();
        // $this->db->query("ALTER TABLE tesorus_road_" . $road_name . "_new CHANGE id_road id_road INT UNIQUE NOT NULL;");
        // dbdebug();

        $q = $this->db->table("fe_road_$road_name");
        $q->join('fe_feature', "fe_feature.id_feat = fe_road_$road_name.id_feat", 'left');
        $roads = $q->get()->getResult();

        // $q = $this->db->table("fe_road_$road_name");
        // $q->select("fe_road_$road_name.*, fe_feature.*, feature_parent.label_fr as parent_label_fr");
        // $q->join('fe_feature', "fe_feature.id_feat = fe_road_$road_name.id_feat", 'left');
        // $q->join("fe_road_$road_name as parent", "parent.id_road = fe_road_$road_name.id_road_parent", 'left');
        // $q->join('fe_feature as feature_parent', "feature_parent.id_feat = parent.id_feat", 'left');
        // $q->orderBy('id_road');
        // $roads = $q->get()->getResult();

        // debug($roads);

        $roads_new = [];
        foreach($roads as $road) :
            $cells = $this->db->table("tesorus_cell")
                ->like('LOWER(label_fr)', mb_strtolower($road->label_fr), 'before')
                ->like('LOWER(label_fr)', mb_strtolower($road->label_fr), 'after')
                ->get()->getResult();
            if(count($cells) > 1) :
                debug('--- FIND CELLS DUBLONS ---');
                debugd($cells);
            elseif(count($cells) == 1) :
                $cell = $cells[0];
                // debug($cell);
                $road->id_cell = $cell->id_cell;
                $roads_new[] = $road;
            else :
                $cell = (object) [];
                $cell->label_fr = $road->label_fr;
                $id_cell = $CellModel->CellSave($cell);
                dbdebug();
                $road->id_cell = $id_cell;
                $roads_new[] = $road;
            endif;
        endforeach;

        // debug($roads_new[0]);

        foreach($roads_new as $road) :
            // debug($road);
            $this->db->table("tesorus_road_" . $road_name . "_new")->set(database_encode("tesorus_road_" . $road_name . "_new", $road))->insert();
            dbdebug();
            // die;
        endforeach;

        $this->db->query("ALTER TABLE tesorus_road_" . $road_name . "_new ADD PRIMARY KEY(id_road);");
        dbdebug();
        $this->db->query("ALTER TABLE tesorus_road_" . $road_name . "_new CHANGE id_road id_road INT AUTO_INCREMENT;");
        dbdebug();

        $this->forge->renameTable("tesorus_road_" . $road_name, "tesorus_road_" . $road_name . "_old");
        dbdebug();
        $this->forge->renameTable("tesorus_road_" . $road_name . "_new", "tesorus_road_" . $road_name);
        dbdebug();
    }
}