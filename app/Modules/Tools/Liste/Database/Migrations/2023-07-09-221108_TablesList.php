<?php

namespace Liste\Database\Migrations;

use Base\Database\BaseMigration;
use CodeIgniter\Database\RawSql;
use Liste\Libraries\ListeLibrary;

class TablesList_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TablesList();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TablesList()
    {
        $ListeLibrary = new ListeLibrary();
        $entities = $ListeLibrary->TableEntities();

        foreach($entities as $entity) :
            foreach($entity->tables as $table=>$label) :
                $db_fields = $this->db->getFieldNames($table);
                if(!in_array('is_actif', $db_fields)) :
                    $is_actif = [ 'type' => 'tinyint', 'default' => 1, ];
                    $this->forge->addColumn($table, ['is_actif' => $is_actif]); dbdebug();
                    if(in_array('actived', $db_fields)) :
                        $this->db->table($table)->set('is_actif', new RawSql('actived'))->update();
                        dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN is_actif TINYINT NOT NULL AFTER id"); dbdebug();
                endif;
                if(!in_array('rank', $db_fields)) :
                    $rank = [ 'type' => 'int', 'null' => false, ];
                    $this->forge->addColumn($table, ['rank' => $rank]); dbdebug();
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN rank INT NOT NULL AFTER is_actif"); dbdebug();
                endif;
                if(!in_array('label', $db_fields)) :
                    $label = [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, ];
                    $this->forge->addColumn($table, ['label' => $label]); dbdebug();
                    if(in_array('label_fr', $db_fields)) :
                        $this->db->table($table)->set('label', new RawSql('label_fr'))->update(); dbdebug();
                    elseif(in_array('name', $db_fields)) :
                        $this->db->table($table)->set('label', new RawSql('name'))->update(); dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN label VARCHAR(255) NOT NULL AFTER rank"); dbdebug();
                endif;             
                if(!in_array('label_original', $db_fields)) :
                    $label_original = [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, ];
                    $this->forge->addColumn($table, ['label_original' => $label_original]); dbdebug();
                    $this->db->table($table)->set('label_original', new RawSql('LOWER(REPLACE(label, " ", "_"))'))->update(); dbdebug();
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN label_original VARCHAR(255) NOT NULL AFTER rank"); dbdebug();
                endif;
                if(!in_array('label_nl', $db_fields)) :
                    $label_nl = [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ];
                    $this->forge->addColumn($table, ['label_nl' => $label_nl]); dbdebug();
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN label_nl VARCHAR(255) NULL AFTER label"); dbdebug();
                endif;                
                if(!in_array('comment', $db_fields)) :
                    $comment = [ 'type' => 'text', 'null' => true, ];
                    $this->forge->addColumn($table, ['comment' => $comment]); dbdebug();
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN comment TEXT NULL AFTER label_nl"); dbdebug();
                endif;
                if(!in_array('updated_at', $db_fields)) :
                    $updated_at = [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ];
                    $this->forge->addColumn($table, ['updated_at' => $updated_at]); dbdebug();
                    if(in_array('date_modification', $db_fields)) :
                        $this->db->table($table)->set('updated_at', new RawSql('date_modification'))->update(); dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER comment"); dbdebug();
                endif;
                if(!in_array('updated_by', $db_fields)) :
                    $updated_by = [ 'type' => 'int', 'null' => false, ];
                    $this->forge->addColumn($table, ['updated_by' => $updated_by]); dbdebug();
                    if(in_array('id_user_modification', $db_fields)) :
                        $this->db->table($table)->set('updated_by', new RawSql('id_user_modification'))->update(); dbdebug();
                    elseif(in_array('id_user', $db_fields)) :
                        $this->db->table($table)->set('updated_by', new RawSql('id_user'))->update(); dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN updated_by INT NOT NULL AFTER updated_at"); dbdebug();
                endif;
                if(!in_array('created_at', $db_fields)) :
                    $created_at = [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ];
                    $this->forge->addColumn($table, ['created_at' => $created_at]); dbdebug();
                    if(in_array('date_creation', $db_fields)) :
                        $this->db->table($table)->set('created_at', new RawSql('date_creation'))->update();
                        dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER updated_by"); dbdebug();
                endif;
                if(!in_array('created_by', $db_fields)) :
                    $created_by = [ 'type' => 'int', 'null' => false, ];
                    $this->forge->addColumn($table, ['created_by' => $created_by]); dbdebug();
                    if(in_array('id_user_creation', $db_fields)) :
                        $this->db->table($table)->set('created_by', new RawSql('id_user_creation'))->update(); dbdebug();
                    else :
                        $this->db->table($table)->set('created_by', new RawSql('updated_by'))->update(); dbdebug();
                    endif;
                    $this->db->query("ALTER TABLE $table MODIFY COLUMN created_by INT NOT NULL AFTER created_at"); dbdebug();
                endif;
            endforeach;
        endforeach;
    }
}