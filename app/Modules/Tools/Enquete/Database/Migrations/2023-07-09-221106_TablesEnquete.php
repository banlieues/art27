<?php

namespace Enquete\Database\Migrations;

use Base\Database\BaseMigration;

class TablesEnquete_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableAnswer();
        $this->TableEnquete();
        $this->TableQuestion();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableAnswer()
    {
        if($this->db->fieldExists('id_person', $this->t_answer)) :
            $fields = [
                'id_person' => [
                    'name' => 'id_contact_profil',
                    'type' => 'int',
                ],
            ];
            $this->forge->modifyColumn($this->t_answer, $fields);
            dbdebug();

            $fields = [
                '_id_person' => [
                    'type' => 'int', 
                ],
            ];
            $this->forge->addColumn($this->t_answer, $fields);
            dbdebug();

            $this->db->query("update $this->t_answer set _id_person = id_contact_profil;");
            dbdebug();
        endif;
    }

    private function TableEnquete()
    {
        if($this->db->fieldExists('date_modification', $this->t_enquete) && !$this->db->fieldExists('updated_at', $this->t_enquete)) :
            $fields = [
                'date_modification' => [ 'type' => 'timestamp', 'name' => 'updated_at', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->modifyColumn($this->t_enquete, $fields);
            dbdebug();
        endif;
        if($this->db->fieldExists('id_user', $this->t_enquete) && !$this->db->fieldExists('updated_by', $this->t_enquete)) :
            $fields = [
                'id_user' => [ 'type' => 'int', 'name' => 'updated_by', 'null' => false, ],
            ];
            $this->forge->modifyColumn($this->t_enquete, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_at', $this->t_enquete)) :
            $fields = [
                'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->addColumn($this->t_enquete, $fields);
            dbdebug();
            $this->db->query("update $this->t_enquete set created_at = updated_at;");
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_by', $this->t_enquete)) :
            $fields = [
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_enquete, $fields);
            dbdebug();
            $this->db->query("update $this->t_enquete set created_by = updated_by;");
            dbdebug();
        endif;
        if(!$this->db->fieldExists('ids_question', $this->t_enquete) && $this->db->fieldExists('nums_question', $this->t_enquete)) :
            $fields = [
                'ids_question' => [ 'type' => 'text', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_enquete, $fields);
            dbdebug();
            $this->TableEnqueteIdsQuestion();
            $this->forge->dropColumn($this->t_enquete, 'nums_question');
            dbdebug();
        endif;
    }

    private function TableEnqueteIdsQuestion()
    {
        $enquetes = database_decode($this->db->table($this->t_enquete)->get()->getResult());
        foreach($enquetes as $enquete) :
            if(!empty($enquete->nums_question)) :
                $ids_question = [];
                foreach($enquete->nums_question as $num_question) :
                    $question = $this->db->table($this->t_question)->where('num_question', $num_question)->get()->getRow();
                    if(empty((array) $question)) continue;
                    $ids_question[] = $question->id_question;
                endforeach;
                $this->db->table($this->t_enquete)->set('ids_question', json_encode($ids_question, JSON_NUMERIC_CHECK))->where('id_enquete', $enquete->id_enquete)->update();
                dbdebug();
            endif;
        endforeach;
    }

    private function TableQuestion()
    {
        if(!$this->db->fieldExists('updated_at', $this->t_question)) :
            $fields = [
                'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->addColumn($this->t_question, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('updated_by', $this->t_question)) :
            $fields = [
                'updated_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_question, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_at', $this->t_question)) :
            $fields = [
                'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->addColumn($this->t_question, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_by', $this->t_question)) :
            $fields = [
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_question, $fields);
            dbdebug();
        endif;
    }
}