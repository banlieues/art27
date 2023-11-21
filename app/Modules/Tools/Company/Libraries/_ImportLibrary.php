<?php

namespace Company\Libraries;

use Api\Libraries\GravityFormApiLibrary;
use Components\Libraries\MigrationLibrary;
use Base\Libraries\BaseLibrary;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Files\File;
use Company\Config\Globals;
use Company\Models\DepositModel;
use Components\Libraries\FileLibrary;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;

class ImportLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->DepositModel = new DepositModel();
        $this->forge = \Config\Database::forge();
        // $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        // $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    public function HomegradeToH4()
    {
        $this->HomegradeToH4TableDeposit();
        $this->HomegradeToH4TableAutorisation();
        //$this->HomegradeToH4TableFile();
    }

    private function HomegradeToH4TableFile()
    {
        $FileLibrary = new FileLibrary();

        $t_file_old = 'co_file';
        if($this->db->tableExists($t_file_old)) :
            $companies = database_decode($this->db->table($this->t_company)->get()->getResult());
            foreach($companies as $company) :
                if(!empty($company->ids_file)) :
                    foreach($company->ids_file as $id_file) :
                        $file_data = $this->db->table($t_file_old)->where('id', $id_file)->get()->getRow();
                        $file_existing = $this->db->table($this->t_file)->where('url_file', $file_data->url_file)->get()->getResult();
                        if(!empty($file_existing)) continue;
                        $file = new File(WRITEPATH . 'uploads/homegrade3/company/' . $file_data->url_file);
                        debugd($file);
                        $uploaded_file = $FileLibrary->FileServerUpload($file);
                        if(!empty($uploaded_file)) $id_file = $FileLibrary->FileDatabaseInsert($file, $uploaded_file);
                        
                    endforeach;
                endif;
            endforeach;
        endif;
    }

    private function HomegradeToH4TableCompany()
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
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
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

    private function HomegradeToH4TableDeposit()
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