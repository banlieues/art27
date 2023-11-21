<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;
use Calculator\Libraries\AdminLibrary;
use Calculator\Models\AdminModel;
use Tesorus\Libraries\TesorusLibrary;

class TableRoadCreate extends BaseMigration
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
        debug('---------- START ' . basename(__FILE__) . ' ----------');

        $this->TablePriceOriginCreate();

        if($this->db->tableExists($this->t_road)) :
            debug('---------- END ' . basename(__FILE__) . ' ----------');
            return false;
        endif;

        $this->TablePriceCreate();
        $this->TableRoadCreate();
        $this->TableRoadPopulate();

        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableRoadCreate()
    {
        if($this->db->tableExists($this->t_road)) :
            $this->forge->dropTable($this->t_road);
            dbdebug();
        endif;
        
        $fields = [
            'id_road' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_cell' => [ 'type' => 'int', ],
            'id_road_parent' => [ 'type' => 'int', ],
            'rank' => [ 'type' => 'int', ],
            'is_terminus' => [ 'type' => 'tinyint', 'null' => true, ],
            'measure' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'period_month_calcul' => [ 'type' => 'int', 'null' => true, ],
            'isActive' => [ 'type' => 'tinyint', 'default' => '1', ],
            'has_text' => [ 'type' => 'tinyint', 'null' => true, ],
            'annotation_fr' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'annotation_nl' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_road', 'id_road');
        $this->forge->createTable($this->t_road);
        dbdebug();

        // $this->db->query("ALTER TABLE $this->t_road ADD PRIMARY KEY `id_road` (`id_road`);");
        // dbdebug();

    }

    private function TablePriceOriginCreate()
    {
        if($this->db->tableExists($this->t_l_price_origin)) return false;
        
        $fields = [
            'id' => [ 'type' => 'int', 'auto_increment' => true, ],
            'label' => [ 'type' => 'varchar', 'constraint' => 255, ],
            'rank' => [ 'type' => 'int', 'null' => true, ],
            'is_actif' => [ 'type' => 'tinyint', 'default' => 1, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id', 'id');
        $this->forge->createTable($this->t_l_price_origin);
        dbdebug();

        $datas = [
            [
                'label' => 'Homegrade',
                'rank' => 1,
                'updated_by' => 0,
                'created_by' => 0,
            ],
            [
                'label' => 'Autre',
                'rank' => 2,
                'updated_by' => 0,
                'created_by' => 0,
            ],
        ];
        foreach($datas as $data) :
            $this->db->table($this->t_l_price_origin)
                ->set(database_encode($this->t_l_price_origin, $data))
                ->insert();
            dbdebug();
        endforeach;
    }

    private function TablePriceCreate()
    {
        if($this->db->tableExists($this->t_price)) :
            $this->forge->dropTable($this->t_price);
            dbdebug();
        endif;
        
        $fields = [
            'id_price' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_road' => [ 'type' => 'int', ],
            'unit_price' => [ 'type' => 'float', ],
            'date_devis' => [ 'type' => 'timestamp', 'null' => true, ],
            'price_origin' => [ 'type' => 'int', 'null' => true, ],
            'is_ignored' => [ 'type' => 'tinyint', 'null' => true, ],
            'comment' => [ 'type' => 'text', 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_price', 'id_price');
        $this->forge->createTable($this->t_price);
        dbdebug();

        // $this->db->query("ALTER TABLE $this->t_price ADD PRIMARY KEY `id_price` (`id_price`);");
        // dbdebug();
    }

    private function TableRoadPopulate()
    {
        $rows = convert_file_csv_to_array($this->path . 'Documents/import_calculator.csv');
        $rows[-1] = null;
        // debugd($rows);
        $i = 0;
        foreach($rows as $row) :
            if(!empty((array) $row)) :
                $id_road = $this->TableRoadPopulateOne($row, $rows[$i-1]);
                $this->TablePricePopulateOne($id_road, $row);
            endif;
            $i++;
        endforeach;
    }

    private function TablePricePopulateOne($id_road, $row)
    {
        for($i=9; $i<18; $i++) :
            $data = (object) [];
            $data->id_road = $id_road;
            if(in_array($i, [9, 10, 11])) $data->date_devis = '2022-01-01';
            if(in_array($i, [12, 13, 14])) $data->date_devis = '2022-02-01';
            if(in_array($i, [15, 16, 17])) $data->date_devis = '2022-03-01';
            $data->unit_price = (float) $row[$i];
            $this->db->table($this->t_price)->set(database_encode($this->t_price, $data))->insert();
            dbdebug();
        endfor;
    }

    private function TableRoadPopulateOne($row, $rowbefore)
    {
        $isset = 0;
        for($i=0; $i<5; $i++) :
            if(!empty(trim($row[$i]))) $isset = 1;
            break;
        endfor;

        if(!empty($isset)) return $this->TableRoadPopulateOneRecursive($row, $rowbefore);
    }

    private function TableRoadPopulateOneRecursive($row, $rowbefore, $i=0, $id_road_parent=0)
    {
        if(!empty(trim($row[$i]))) :
            $id_cell = $this->TableCellPopulate($row[$i]);

            $is_different = 0;
            for($j=0; $j<=$i; $j++) :
                if(!empty($rowbefore[$j]) && $row[$j]!=$rowbefore[$j]) :
                    $is_different = 1;
                    break;
                endif;
            endfor;
            if(empty($rowbefore) || !empty($is_different)) :
                $max_road = $this->db->table($this->t_road)->selectMax('rank', 'max_rank')->where('id_road_parent', $id_road_parent)->get()->getResult();
                // dbdebug();
                $rank = isset($max_road[0]->max_rank) ? $max_road[0]->max_rank + 1 : 0;
                
                $road = (object) [];
                $road->id_cell = $id_cell;
                $road->id_road_parent = $id_road_parent;
                $road->rank = $rank;
                if(empty($row[$i+1])) :
                    $road->is_terminus = 1;
                    $road->measure = $row[6];
                    $road->period_month_calcul = 6;
                endif;
                $this->db->table($this->t_road)->set(database_encode($this->t_road, $road))->insert();
                // dbdebug();
                $id_road = $this->db->insertID();
            else :
                // debug($row);
                $road = database_decode($this->db->table($this->t_road)
                    ->where('id_cell', $id_cell)
                    ->where('id_road_parent', $id_road_parent)
                    ->get()->getRow());
                $id_road = $road->id_road;
            endif;
        else :
            $id_road = $id_road_parent;
        endif;

        if($i<5 && !empty($row[$i+1])) :
            return $this->TableRoadPopulateOneRecursive($row, $rowbefore, $i+1, $id_road);
        else :
            return $id_road;
        endif;
    }

    private function TableCellPopulate($label)
    {
        $cells = $this->db->table($this->t_cell)->where("lower(label_fr)", mb_strtolower(trim($label)))->get()->getResult();
        if(empty($cells)) :
            $data = (object) [];
            $data->reference = str_replace(' ', '_', mb_strtolower(remove_accents(trim($label))));
            $data->label_fr = ucfirst(trim($label));
            $this->db->table($this->t_cell)->set(database_encode($this->t_cell, $data))->insert();
            // dbdebug();
            $id_cell = $this->db->insertID();
        else :
            // debug($label . ' already exists');
            $id_cell = $cells[0]->id_cell;
        endif;

        return $id_cell;
    }
}



