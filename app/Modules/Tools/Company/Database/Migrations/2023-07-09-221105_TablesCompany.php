<?php

namespace Company\Database\Migrations;

use Base\Database\BaseMigration;
use CodeIgniter\Files\File;
use Company\Models\DepositModel;
use Components\Libraries\FileLibrary;

class TablesCompany_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->DepositModel = new DepositModel();
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableDeposit();
        $this->TableCompany();
        //$this->TableFile();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableCompany()
    {
        if($this->db->fieldExists('id_language', $this->t_company)) :
            $this->forge->dropColumn($this->t_company, 'id_language');
            dbdebug();
        endif;
        if($this->db->fieldExists('id_type', $this->t_company)) :
            $this->forge->dropColumn($this->t_company, 'id_type');
            dbdebug();
        endif;
        if($this->db->fieldExists('update_datetime', $this->t_company)) :
            if($this->db->fieldExists('updated_at', $this->t_company)) $this->forge->dropColumn($this->t_company, 'updated_at');
            $fields = [
                'update_datetime' => [
                    'name' => 'updated_at',
                    'type' => 'timestamp', 
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
                ],
            ];
            $this->forge->modifyColumn($this->t_company, $fields);
            dbdebug();
        endif;
        if($this->db->fieldExists('update_id_user', $this->t_company)) :
            if($this->db->fieldExists('updated_by', $this->t_company)) :
                $this->forge->dropColumn($this->t_company, 'updated_by');
                dbdebug();
            endif;
            $fields = [
                'update_id_user' => [
                    'name' => 'updated_by',
                    'type' => 'INT',
                ],
            ];
            $this->forge->modifyColumn($this->t_company, $fields);
            dbdebug();
        endif;
        if($this->db->fieldExists('create_datetime', $this->t_company)) :
            if($this->db->fieldExists('created_at', $this->t_company)) $this->forge->dropColumn($this->t_company, 'created_at');
            $fields = [
                'create_datetime' => [
                    'name' => 'created_at',
                    'type' => 'timestamp', 
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                ],
            ];
            $this->forge->modifyColumn($this->t_company, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_by', $this->t_company)) :
            $fields = [
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_company, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('id_contact_profil', $this->t_company)) :
            $fields = [
                'id_contact_profil' => [
                    'type' => 'int', 
                    'null' => true,
                ],
            ];
            $this->forge->addColumn($this->t_company, $fields);
            dbdebug();
        endif;
    }

    private function TableDeposit()
    {
        if(!$this->db->tableExists($this->t_deposit)) :
            debug("La table $this->t_deposit n'existe pas.");
            return false;
        endif;

        if(!$this->db->fieldExists('id_user_on_work', $this->t_deposit)) :
            $fields = [
                'id_user_on_work' => [
                    'type' => 'TINYINT',
                    'null' => true,
                ],
            ];
            $this->forge->addColumn($this->t_deposit, $fields);
            dbdebug();
        endif;
    }
}