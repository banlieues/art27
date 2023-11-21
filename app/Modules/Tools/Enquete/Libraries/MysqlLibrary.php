<?php

namespace Enquete\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\DatabaseLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Tesorus\Libraries\TesorusLibrary;

class MysqlLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();
        
	    $globals = new \Enquete\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->db_l = new DatabaseLibrary(__NAMESPACE__);
        $this->AnswerModel = new AnswerModel();
        $this->EnqueteModel = new EnqueteModel();
        $this->TesorusLibrary = new TesorusLibrary();
    }

    public function check_database()
    {
        $this->check_table__mt_template();
        $this->check_database__fe_road_request();
        $this->check_database__en_answer();
        $this->check_database__en_enquete();
        $this->check_database__en_question();
        // $this->check_database__en_translation();
    }

    public function check_database__en_enquete()
    {
        $this->db_l->check_database_table_common($this->t_enquete, $this->path . 'Config/Json/enquete/table.json');

        $columns = $this->db->getFieldNames($this->t_enquete);
        if($this->db->fieldExists('id_type_demande', $this->t_enquete) || $this->db->fieldExists('id_type_accompagnement', $this->t_enquete)) :
            $enquetes = $this->db->table($this->t_enquete)->select('id_enquete, id_type_demande, id_type_accompagnement, id_request_type')->get()->getResult();
            foreach($enquetes as $enquete) :
                if(empty($enquete->id_request_type)) :
                    $this->id_demande_type_from_type_demande_get($enquete);
                endif;
            endforeach;
        endif;
    }

    public function check_database__en_question()
    {
        // to remove after put online and refresh module
        if($this->db->fieldExists('possibly_irrelevant', $this->t_question)) :
            $fields = array(
                'possibly_irrelevant' => array(
                        'name' => 'is_not_required',
                        'type' => 'TINYINT NULL',
                ),
            );
            $this->dbforge->modifyColumn($this->t_question, $fields);
        endif;
        // end - to remove after put online and refresh module
        
        $this->db_l->check_database_table_common($this->t_question, $this->path . 'Config/Json/question/table.json');

    }

    public function check_database__fe_road_request()
    {
        $mysql_fe = new \Tesorus\Libraries\MysqlLibrary();
        $mysql_fe->check_database__fe_road('demande');
    }

    public function check_database__en_answer()
    {
        if(!$this->db->tableExists($this->t_answer)) :
            // $this->db_l->sql_create_table_from_file($this->t_answer, $file_answer);
            $this->sql_copy_answer_enquete_to_en_answer();
        endif;

        if($this->db->fieldExists('id_demande', $this->t_answer)) :
            $answers = $this->db->table($this->t_answer)->select('id_answer, id_demande, id_request_type')->get()->getResult();
            foreach($answers as $answer) :
                if(empty($answer->id_request_type)) :
                    $this->id_demande_type_from_demande_get($answer);
                endif;
            endforeach;
        endif;
        if(
            $this->db->fieldExists('id_personne', $this->t_answer) && 
            !$this->db->fieldExists('id_person', $this->t_answer)
            ) :
            $this->db->query('ALTER TABLE ' . $this->t_answer . ' CHANGE `id_personne` `id_person` INT(11) NOT NULL;');
        endif;

        $file_answer = $this->path . 'Config/Json/answer/table.json';
        $fields = json_decode(file_get_contents($file_answer));
        $this->db_l->sql_alter_table_from_file($this->t_answer, $file_answer);
        
    }

    public function check_table__mt_template()
    {
        $mysql_mt = new \MailTemplate\Libraries\MysqlLibrary();
        $mysql_mt->check_table__mt_template();

        $emodels = $this->db->table($this->t_mail_template)->where('ref', $this->emodel_ref)->get()->getResult();
        if(empty($emodels)) :
            $file_emodel_init = $this->path . 'Config/Json/emodel/init.json';
            if(file_exists($file_emodel_init)) $this->db_l->sql_insert_to_table_from_file($this->t_mail_template, $file_emodel_init);
        endif;
    }

    private function id_demande_type_from_demande_get($answer)
    {
        $demandes = $this->db->table($this->t_demande)->where('id_demande', $answer->id_demande)->get()->getResult();
        if(empty($demandes)) return false;

        $id_type_demande = !empty($demandes) ? $demandes[0]->id_type_demande : null;
        $accomps = $this->db->table($this->t_demande_carac)->where($this->t_demande_carac . '.id_demande', $answer->id_demande)->get()->getResult();
        $id_type_accompagnement = !empty($accomps) ? $accomps[0]->id_type_accompagnement : null;

        $id_request_type = $this->TesorusLibrary->convert_to_id_road_demande($id_type_demande, $id_type_accompagnement);

        if(empty($id_request_type)) return false;

        $q = 'UPDATE ' . $this->t_answer . ' SET id_request_type = ' . $id_request_type . ' WHERE id_answer = ' . $answer->id_answer;
        $this->db->query($q);
    }

    private function id_demande_type_from_type_demande_get($enquete)
    {
        $id_request_type = $this->TesorusLibrary->convert_to_id_road_demande($enquete->id_type_demande, $enquete->id_type_accompagnement);

        if(empty($id_request_type)) return false;

        $q = 'UPDATE ' . $this->t_enquete . ' SET id_request_type = ' . $id_request_type . ' WHERE id_enquete = ' . $enquete->id_enquete;
        $this->db->query($q);
    }

    // public function print_columns_from_table()
    // {
    //     $file = $this->path . 'Config/Json/question/table.json';
    //     if(!file_exists($file)) $this->db_l->print_columns_from_table($this->t_question, $file);
    // }

    public function sql_copy_answer_enquete_to_en_answer()
    {
        if(!$this->db->tableExists($this->t_answer)) :
            $q = [];
            $q[] = "CREATE TABLE $this->t_answer AS SELECT * FROM answer_enquete;";
            // $q[] = "ALTER TABLE `$this->t_answer` DROP `id_answer`;";
            // $q[] = "ALTER TABLE `$this->t_answer` ADD PRIMARY KEY(`id_answer`); ";
            // $q[] = "ALTER TABLE `$this->t_answer` CHANGE `id_answer` `id_answer` INT(11) NOT NULL AUTO_INCREMENT=15304;";
            $q[] = "ALTER TABLE `$this->t_answer` AUTO_INCREMENT=15304;";
            $q[] = "ALTER TABLE `$this->t_answer` ADD `id_request_type` INT(11) NOT NULL;";

            $q[] = "ALTER TABLE `$this->t_answer` CHANGE `id_personne` `id_person` INT(11) NOT NULL;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `id_type_demande`;";
            $q[] = "ALTER TABLE `$this->t_answer` CHANGE `statut_answer` `id_statut_answer` INT(11) NOT NULL DEFAULT '1'; ";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `is_finished`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `quality_contact`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `has_solution_concrete_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `conseil_connaissance_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `comment_appel`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `source_personne_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `appreciation_visite`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `appreciation_visite_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `rapport_ecrit_appreciation_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `rapport_ecrit_accessibilite_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `reponses_accessibilite`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `reponses_accessibilite_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `relation_conseiller_quality`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `pertinence_conseil`;";
            $q[] = "ALTER TABLE `$this->t_answer` CHANGE `accesible_message` `accessible_message` INT(11) NULL DEFAULT NULL; ";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `appreciation_rdv_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `conference_atelier_presence`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `conference_atelier_presence_c`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `cherche_reponse`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `information_destination`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `travail_secteur_batiment`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `information_concretes`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `decision_future`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `qualite_support`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `qualite_accueil`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `qualite_infrastructure`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `origine_info_programme`;";
            $q[] = "ALTER TABLE `$this->t_answer` DROP `aide_rdv`;";

            $q[] = "ALTER TABLE `$this->t_answer` ADD PRIMARY KEY(`id_answer`);";
            $q[] = "ALTER TABLE `$this->t_answer` CHANGE `id_answer` `id_answer` INT(11) NOT NULL AUTO_INCREMENT;";

            $q[] = "UPDATE `$this->t_answer` SET rapport_ecrit_accessibilite=10 WHERE rapport_ecrit_accessibilite>10;";
            $q[] = "UPDATE `$this->t_answer` SET delai_visite_rapport=10 WHERE delai_visite_rapport>10;";
            
        endif;
            
        if(!empty($q)) :
            foreach($q as $query) :
                // $this->db->query($query);
                if($this->db->query($query)) echo '<br> OK - ' . $query . '<br>';
                else echo '<br> ERROR - ' . $query . '<br>';
            endforeach;
        endif;
    }
}