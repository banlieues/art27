<?php

namespace Mapping\Database\Migrations;

use Base\Database\BaseMigration;

class TableEmail_230914 extends BaseMigration
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
        if(!$this->db->fieldExists('mail_template', $this->t_email)) :
            $fields = [
                'mail_template' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_email, $fields);
            dbdebug();
        endif;    
    }

}