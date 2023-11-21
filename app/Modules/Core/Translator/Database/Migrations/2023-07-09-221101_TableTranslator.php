<?php

namespace Translator\Database\Migrations;

use Base\Database\BaseMigration;

class TableTranslator_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableTranslator();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableTranslator()
    {
        if(!$this->db->fieldExists('id_transl', $this->t_translator)) :
            $this->db->query("ALTER TABLE $this->t_translator DROP PRIMARY KEY;");
            dbdebug();
            $this->db->query("ALTER TABLE $this->t_translator ADD id_transl INT PRIMARY KEY AUTO_INCREMENT;");
            dbdebug();
        endif;
        if(!$this->db->fieldExists('module', $this->t_translator)) :
            $fields = [
                'module' => [ 'type' => 'varchar', 'constraint' => '256', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('ref', $this->t_translator)) :
            $fields = [
                'ref' => [ 'type' => 'varchar', 'constraint' => '256', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('description', $this->t_translator)) :
            $fields = [
                'description' => [ 'type' => 'text', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('updated_at', $this->t_translator)) :
            $fields = [
                'updated_at' => [
                    'type' => 'timestamp', 
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
                ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('updated_by', $this->t_translator)) :
            $fields = [
                'updated_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_at', $this->t_translator)) :
            $fields = [
                'created_at' => [
                    'type' => 'timestamp', 
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_by', $this->t_translator)) :
            $fields = [
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_translator, $fields);
            dbdebug();
        endif;
    }
}