<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;

class TablesClientCreate_230729 extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableWorkCreate();
        $this->TableGroupWorkCreate();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableWorkCreate()
    {
        if($this->db->tableExists($this->t_work)) return false;
        
        $fields = [
            'id_work' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_demande' => [ 'type' => 'int', 'null' => false, ],
            'label' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, ],
            'id_them' => [ 'type' => 'int', 'null' => true, ],
            'annotation' => [ 'type' => 'text', 'null' => true, ],
            'comment' => [ 'type' => 'text', 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_work', 'id_work');
        $this->forge->createTable($this->t_work);
        dbdebug();
    }

    private function TableGroupWorkCreate()
    {
        if($this->db->tableExists($this->t_group_work)) return false;
        
        $fields = [
            'id' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_work' => [ 'type' => 'int', 'null' => false, ],
            'id_group' => [ 'type' => 'int', 'null' => false, ],
            'is_prior' => [ 'type' => 'boolean', 'null' => true, ],
            'quantity' => [ 'type' => 'float', 'null' => true, ],
            'ids_road' => [ 'type' => 'text', 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id', 'id');
        $this->forge->createTable($this->t_group_work);
        dbdebug();
    }
}



