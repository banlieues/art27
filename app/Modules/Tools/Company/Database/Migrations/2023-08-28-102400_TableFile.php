<?php

namespace Company\Database\Migrations;

use Base\Database\BaseMigration;
use CodeIgniter\Files\File;

class TableFile_230828 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableFile();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableFile()
    {
        $t_file_old = 'co_file';
        if($this->db->tableExists($t_file_old)) :
            $companies = database_decode($this->db->table($this->t_company)->get()->getResult());
            foreach($companies as $company) :
                if(!empty($company->ids_file)) :
                    $ids_file_new = [];
                    foreach($company->ids_file as $id_file) :
                        $file = $this->db->table($t_file_old)->where('id', $id_file)->get()->getRow();
                        $file_exist_db = $this->db->table($this->t_file)->where('url_file', $file->url_file)->where('id', $id_file)->get()->getRow();
                        if(isset($file_exist_db)) :
                            $ids_file_new[] = $id_file;
                            debug("file already exists in table document_upload (id=$id_file)");
                        else :
                            if(empty($file->contentByte)) :
                                $f = new File(PATH_DOCU_DEMANDE . $file->url_file);
                                if(!isset($f)) continue;
                                $file->contentByte = $f->getSize();
                            endif;
                            $file->commentaire = 'from Ready to renov';
                            $file->id_message = 0;
                            $file->id_demande = 0;
                            $file->date_echeance = '0000-00-00 00:00:00';
                            $file->id_type = 0;
                            $this->db->table($this->t_file)->set(database_encode($this->t_file, $file))->insert();
                            dbdebug();
                            $ids_file_new[] = $this->db->insertID();
                        endif;
                    endforeach;
                    $data = (object) [];
                    $data->ids_file = $ids_file_new;
                    $this->db->table($this->t_company)->set(database_encode($this->t_company, $data))->where('id_company', $company->id_company)->update();
                    dbdebug();
                endif;
            endforeach;
        endif;
    }
}