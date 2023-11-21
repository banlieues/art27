<?php

namespace Tesorus\Database\Migrations;

use Base\Database\BaseMigration;

class TablesTesorus_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TablesRoad();
        $this->TableCell();
        $this->TableRoadDemande();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function TablesRoad()
    {
        $tables = $this->db->listTables();
        foreach($tables as $table) :
            if(preg_match('/^fe_road_(.*)$/', $table, $matches)) :
                $road_table = 'tesorus_road_' . $matches[1];
                if(!$this->db->tableExists($road_table)) :
                    $this->TableRoad($table, $road_table);
                endif;
            endif;
        endforeach;
    }

    private function TableRoadDemande()
    {
        if(!$this->db->tableExists($this->t_road_demande) && $this->db->tableExists('tesorus_road_request')) :
            $this->forge->renameTable('tesorus_road_request', $this->t_road_demande);
            dbdebug();
        endif;
    }

    private function TableRoad($old_table, $table)
    {
        $this->forge->renameTable($old_table, $table);
        dbdebug();

        $update_fields = [
            'id_feat' => [ 'name' => 'id_cell', 'type' => 'INT', 'null' => false, ],
        ];
        $this->forge->modifyColumn($table, $update_fields);
        dbdebug();

        $db_fields = $this->db->getFieldNames($table);
        $new_fields = (object) [];
        if(!in_array('updated_at', $db_fields)) $new_fields->updated_at = [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ];
        if(!in_array('updated_by', $db_fields)) $new_fields->updated_by = [ 'type' => 'int', 'null' => false, ];
        if(!in_array('created_at', $db_fields)) $new_fields->created_at = [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ];
        if(!in_array('created_by', $db_fields)) $new_fields->created_by = [ 'type' => 'int', 'null' => false, ];
        $this->forge->addColumn($table, $new_fields);
        dbdebug();
    }

    public function TableCell()
    {
        if($this->db->tableExists($this->t_cell)) return false;

        $this->db->query("CREATE TABLE $this->t_cell SELECT * FROM fe_feature;");
        dbdebug();

        $this->db->query("ALTER TABLE $this->t_cell ADD PRIMARY KEY `id_feat` (`id_feat`);");
        dbdebug();

        $fields = [
            'id_feat' => [ 'name' => 'id_cell', 'type' => 'INT', 'auto_increment' => true, ],
        ];
        $this->forge->modifyColumn($this->t_cell, $fields);
        dbdebug();

        $this->forge->renameTable('fe_feature', '_fe_feature');
        dbdebug();
    }
}