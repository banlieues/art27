<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;

class TableGroupWorkAlter_230925 extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableGroupWorkAlter();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableGroupWorkAlter()
    {
        if(!$this->db->tableExists($this->t_group_work)) return false;
        
        if(!$this->db->fieldExists('is_recommended', $this->t_group)) :
            $fields = [
                'is_recommended' => [ 'type' => 'boolean', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_group_work, $fields);
            dbdebug();
        endif;

    }
}



