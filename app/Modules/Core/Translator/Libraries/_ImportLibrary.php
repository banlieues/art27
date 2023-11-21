<?php

namespace Translator\Libraries;

use Components\Libraries\MigrationLibrary;
use Base\Libraries\BaseLibrary;

class ImportLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->forge = \Config\Database::forge();
    }

    public function HomegradeToH4()
    {
        $this->HomegradeToH4TableTranslator();
        $this->TableAutorisationAlter();
    }

    private function TableAutorisationAlter()
    {
        $Migration = new MigrationLibrary();

        $is_create = 0;
        $is_create += $Migration->TableAutorisationAddColumn('translator_r');
        $is_create += $Migration->TableAutorisationAddColumn('translator_u');
        $is_create += $Migration->TableAutorisationAddColumn('translator_c');
        $is_create += $Migration->TableAutorisationAddColumn('translator_d');
    }

    private function HomegradeToH4TableTranslator()
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
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
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