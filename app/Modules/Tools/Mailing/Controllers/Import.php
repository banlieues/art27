<?php

namespace Mailing\Controllers;

use Base\Controllers\BaseController;

class Import extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->forge = \Config\Database::forge();
    }

    public function TableTemplate()
    {
        // $this->forge->dropTable($this->t_mail_template, true);

        if(!$this->db->tableExists('em_emodel')) return "La table em_emodel n'existe pas.";
        if($this->db->tableExists($this->t_mail_template)) return "La table $this->t_mail_template existe déjà.";

        $this->db->query("CREATE TABLE $this->t_mail_template SELECT * FROM em_emodel;");
        dbdebug();

        $this->db->query("ALTER TABLE $this->t_mail_template ADD PRIMARY KEY `id_emodel` (`id_emodel`);");
        dbdebug();

        $fields = [
            'id_emodel' => [
                'name' => 'id_template',
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'update_datetime' => [
                'name' => 'updated_at',
                'type' => 'timestamp',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'update_id_user' => [
                'name' => 'updated_by',
                'type' => 'INT',
            ],
            'create_datetime' => [
                'name' => 'created_at',
                'type' => 'timestamp',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'create_id_user' => [
                'name' => 'created_by',
                'type' => 'INT',
            ],
        ];

        $this->forge->modifyColumn($this->t_mail_template, $fields);
        dbdebug();
    }

}
