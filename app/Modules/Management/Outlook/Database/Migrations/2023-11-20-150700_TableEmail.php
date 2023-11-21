<?php

namespace Outlook\Database\Migrations;

use Base\Database\BaseMigration;

class TableEmail_231120 extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableEmail();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableEmail()
    {
        if(!$this->db->fieldExists('sender_user', $this->t_email)) :
            $fields = [
                'sender_user' => [ 'type' => 'int', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_email, $fields);
            dbdebug();
        endif;
    }
}



