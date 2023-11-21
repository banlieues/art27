<?php

namespace DemandeWeb\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\DatabaseLibrary;
use DemandeWeb\Libraries\DemandeLibrary;

class MysqlLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();

        $globals = new \DemandeWeb\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->DWLibrary = new DemandeLibrary();
        $this->db_l = new DatabaseLibrary(__NAMESPACE__);
    }

    public function check_database__re_deposit()
    {
        $this->db_l->check_database_table_common($this->t_deposit, $this->path . 'Config/Json/deposit/table.json');
        if(empty($this->db->table($this->t_deposit)->countAll())) :
            $nb_imports = $this->DWLibrary->deposit_import_from_gravityforms();
            $nb_doublons = $this->DWLibrary->deposit_import_doublon_process();

            $this->session->setFlashdata('success', "L'importation a enregistré $nb_imports demandes dont $nb_doublons ont été traitées.");
        endif;

        // important to keep because values has changed in gravityforms
        if(!empty($this->db->table($this->t_deposit)->where('ids_profile like "%Propri%"')->get()->getResult())) :
            $this->update_re_deposit_ids_building_function();
        endif;

        if(empty($this->db->table($this->t_deposit)->where('urls_file is not null AND gf_date_created BETWEEN "2022-03-31 08:31:50" AND "2022-05-11 05:47:05"')->get()->getResult())) :
            $this->update_re_deposit_urls_file();
        endif;
    }

    private function update_re_deposit_urls_file()
    {
        $q = $this->db->table($this->t_deposit);
        $q->set($this->t_deposit . '.urls_file', '(SELECT re_deposit_backup_file.urls_file FROM re_deposit_backup_file WHERE re_deposit_backup_file.id_deposit = ' . $this->t_deposit . '.id_deposit)', false);
        $q->where('gf_date_created BETWEEN "2022-03-31 08:31:50" AND "2022-05-11 05:47:05"');
        $q->update();
    }

    private function update_re_deposit_ids_building_function()
    {
        $sets = [];
        $sets[] = 'replace(ids_profile, "\"Propri\\\u00e9taire occupant\"", "po")';
        $sets[] = 'replace(ids_profile, "\"Propri\\\u00e9taire bailleur\"", "pb")';
        $sets[] = 'replace(ids_profile, "\"Locataire\"", "lo")';
        $sets[] = 'replace(ids_profile, "\"Syndic\"", "4")';
        $sets[] = 'replace(ids_profile, "\"Autre\"", "7")';

        $sets[] = 'replace(ids_profile, "[1]", "[po]")';
        $sets[] = 'replace(ids_profile, "[1,", "[po,")';
        $sets[] = 'replace(ids_profile, ",1]", ",po]")';
        $sets[] = 'replace(ids_profile, ",1,", ",po,")';
        $sets[] = 'replace(ids_profile, "[2]", "[pb]")';
        $sets[] = 'replace(ids_profile, "[2,", "[pb,")';
        $sets[] = 'replace(ids_profile, ",2]", ",pb]")';
        $sets[] = 'replace(ids_profile, ",2,", ",pb,")';
        $sets[] = 'replace(ids_profile, "[3]", "[lo]")';
        $sets[] = 'replace(ids_profile, "[3,", "[lo,")';
        $sets[] = 'replace(ids_profile, ",3]", ",lo]")';
        $sets[] = 'replace(ids_profile, ",3,", ",lo,")';

        $sets[] = 'replace(ids_profile, "[5]", "[7]")';
        $sets[] = 'replace(ids_profile, "[5,", "[7,")';
        $sets[] = 'replace(ids_profile, ",5]", ",7]")';
        $sets[] = 'replace(ids_profile, ",5,", ",7,")';

        $sets[] = 'replace(ids_profile, "po", "2")';
        $sets[] = 'replace(ids_profile, "pb", "3")';
        $sets[] = 'replace(ids_profile, "lo", "1")';

        $sets[] = 'replace(ids_profile, "20", "2")';
        $sets[] = 'replace(ids_profile, "30", "3")';
        $sets[] = 'replace(ids_profile, "10", "1")';

        $q = $this->db->table($this->t_deposit);
        foreach($sets as $set) :
            $q->resetQuery();
            $q->where('gf_date_created < "2022-04-29 00:00:00"');
            $q->set('ids_profile', $set, false);
            $q->update();
        endforeach;
    }

    // public function check_database__re_translation()
    // {
    //     $this->db_l->check_database_table_common($this->t_translation, $this->path . 'Config/Json/translation/table.json');
    // }

    public function check_database__fe_roads()
    {
        $mysql_fe = new \Tesorus\Libraries\MysqlLibrary();
        $roads = ['demande'];
        $mysql_fe->check_database($roads);
    }
}