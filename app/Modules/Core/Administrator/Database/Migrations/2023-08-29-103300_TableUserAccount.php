<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;
use Custom\Config\Globals;

class TableUserAccount_230829 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableUserAccount();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function TableUserAccount()
    {
        if(!$this->db->tableExists($this->t_user)) return false;

        if($this->db->fieldExists('id', $this->t_user)) :
            $fields = [
                'id' => [ 'type' => 'int', 'null' => false, 'auto_increment' => true, 'unique' => true],
            ];
            $this->forge->modifyColumn($this->t_user, $fields);
            dbdebug();
        endif;
    }

    public function down()
    {
    }
}