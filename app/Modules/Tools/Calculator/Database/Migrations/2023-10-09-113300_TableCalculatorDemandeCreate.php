<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;

class TableCalculatorDemandeCreate_231009 extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableCalculatorDemandeCreate();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableCalculatorDemandeCreate()
    {
        if($this->db->tableExists($this->t_calculator_demande)) return false;
        
        $fields = [
            'id' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_demande' => [ 'type' => 'int', 'null' => false, ],
            'date_visite' => [ 'type' => 'timestamp', 'null' => true, ],
            'comment_difficulty' => [ 'type' => 'text', 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id', 'id');
        $this->forge->createTable($this->t_calculator_demande);
        dbdebug();
    }
}



