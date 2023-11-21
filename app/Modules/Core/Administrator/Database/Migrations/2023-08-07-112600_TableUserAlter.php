<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;
use Custom\Config\Globals;

class TableUserAlter_230807 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableUserAlter();
        $this->TableUserProfileDrop();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function TableUserProfileDrop()
    {
        if(!$this->db->tableExists($this->t_user_profile)) return false;

        $this->forge->renameTable($this->t_user_profile, '_' . $this->t_user_profile . '_old');
        dbdebug();
    }
    
    private function TableUserAlter()
    {
        if(!$this->db->tableExists($this->t_user) || !$this->db->tableExists($this->t_user_profile)) return false;
        
        $profiles = $this->db->table($this->t_user_profile)->get()->getResult();

        if(!$this->db->fieldExists('avatar', $this->t_user)) :
            $fields = [
                'avatar' => [ 'type' => 'varchar', 'constraint' => 255, 'default' => 'default.png', ],
            ];
            $this->forge->addColumn($this->t_user, $fields);
            dbdebug();
            foreach($profiles as $profile) :
                $this->db->table($this->t_user)->set('avatar', $profile->avatar)->where('id', $profile->user_id)->update();
                dbdebug();
            endforeach;
        endif;
        
        if(!$this->db->fieldExists('phone', $this->t_user)) :
            $fields = [
                'phone' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_user, $fields);
            dbdebug();
            foreach($profiles as $profile) :
                $this->db->table($this->t_user)->set('phone', $profile->phone)->where('id', $profile->user_id)->update();
                dbdebug();
            endforeach;
        endif;
        
        if(!$this->db->fieldExists('website', $this->t_user)) :
            $fields = [
                'website' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_user, $fields);
            dbdebug();
            foreach($profiles as $profile) :
                $this->db->table($this->t_user)->set('website', $profile->website)->where('id', $profile->user_id)->update();
                dbdebug();
            endforeach;
        endif;
    }

    public function down()
    {
    }
}