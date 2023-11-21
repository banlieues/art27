<?php

namespace Mapping\Database\Migrations;

use Base\Database\BaseMigration;

class TableEmail_231004 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->t_email = 'email_outlook';
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableEmail();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function TableEmail()
    {
        if(!$this->db->fieldExists('is_brouillon', $this->t_email)) :
            $fields = [
                'is_brouillon' => [ 'type' => 'boolean', 'null' => false, 'default' => 0],
            ];
            $this->forge->addColumn($this->t_email, $fields);
            dbdebug();
        endif;    
    }

}