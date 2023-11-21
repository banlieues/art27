<?php

namespace Company\Libraries;

use Base\Libraries\BaseLibrary;
use Company\Config\Globals;
use Components\Libraries\DatabaseLibrary;
use Tesorus\Libraries\MysqlLibrary as TesorusMysqlLibrary;
use Translator\Libraries\TranslatorLibrary;

class MysqlLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();

        $globals = new Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->db_l = new DatabaseLibrary(__NAMESPACE__);
        $this->transl_l = new TranslatorLibrary();
    }

    // public function check_database()
    // {
    //     $this->transl_l->check_database__translator();
    //     // $this->db_l->check_database__translator();
    //     $this->check_database__co_company();
    //     $this->check_database__co_deposit();
    //     // $this->check_database__co_translation();
    //     $this->check_database__fe_roads();
    // }

    private function translator_files_to_import_company()
    {
        $files = [];
        $files[] = $this->path . 'Config/Json/company/form.json';
        $files[] = $this->path . 'Config/Json/company/annotation.json';
        $files[] = $this->path . 'Config/Json/list.json';

        return $files;
    }

    // public function check_database__co_company()
    // {
    //     if(!$this->db->tableExists($this->t_company)) :
    //         $files = $this->translator_files_to_import_company();
    //         $this->transl_l->import_labels_from_files($files);
    //     endif;
    //     $this->db_l->check_database_table_common($this->t_company, $this->path . 'Config/Json/company/table.json');
    // }

    private function translator_files_to_import_deposit()
    {
        $files = [];
        $files[] = $this->path . 'Config/Json/deposit/form.json';
        $files[] = $this->path . 'Config/Json/deposit/annotation.json';
        $files[] = $this->path . 'Config/Json/list.json';

        return $files;
    }

    // public function check_database__co_deposit()
    // {
    //     if(!$this->db->tableExists($this->t_company)) :
    //         $files = $this->translator_files_to_import_deposit();
    //         $this->transl_l->import_labels_from_files($files);
    //     endif;
    //     $this->db_l->check_database_table_common($this->t_deposit, $this->path . 'Config/Json/deposit/table.json');
    // }

    // public function check_database__co_translation()
    // {
    //     $this->db_l->check_database_table_common($this->t_translation, $this->path . 'Config/Json/translation/table.json');
    // }

    // public function check_database__fe_roads()
    // {
    //     $mysql = new TesorusMysqlLibrary();
    //     $roads = ['work', 'eco_impact'];
    //     $mysql->check_database($roads);
    // }
}