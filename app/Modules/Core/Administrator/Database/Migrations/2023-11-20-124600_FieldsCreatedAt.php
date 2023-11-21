<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;

class FieldsCreatedAt_231120 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->FieldsCreatedAt();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function FieldsCreatedAt()
    {
        $tables = $this->db->listTables();
        foreach($tables as $table) :
            $fields = $this->db->getFieldData($table);
            foreach($fields as $field) :
                if(!empty($field->default) && $field->default=='current_timestamp()' && $field->name=='created_at') :
                    $changes = [
                        $field->name => [
                            'name' => $field->name,
                            'type' => $field->type, 
                            'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                        ],
                    ];
                    $this->forge->modifyColumn($table, $changes);
                    dbdebug();        
                endif;
            endforeach;
        endforeach;
    }

    public function down()
    {
    }
}