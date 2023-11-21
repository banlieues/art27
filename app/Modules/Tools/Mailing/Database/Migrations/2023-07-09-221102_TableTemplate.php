<?php

namespace Mailing\Database\Migrations;

use Base\Database\BaseMigration;

class TableTemplate_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableTemplate();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableTemplate()
    {
        if(!$this->db->tableExists($this->t_mail_template)) :

            if(!$this->db->tableExists('em_emodel')) :
                debug("La table $this->t_mail_template n'existe pas et ne peut pas s'importer via la table em_emodel qui n'existe pas non plus.");
                return false;
            endif;

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

            $this->forge->renameTable('em_emodel', '_em_emodel');
            dbdebug();
        endif;

        if(!$this->db->fieldExists('is_activated', $this->t_mail_template)) :
            $fields = [
                'is_activated' => [
                    'type' => 'TINYINT',
                    'default' => 1,
                    'null' => true,
                ],
            ];
            $this->forge->addColumn($this->t_mail_template, $fields);
            dbdebug();
        endif;
    }
}