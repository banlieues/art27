<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;
use Custom\Config\Globals;

class TableCropperDrop_230808 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableCropperDrop();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function TableCropperDrop()
    {
        if($this->db->tableExists('user_avatar_settings')) :
            if($this->db->tableExists('settings_cropper')) :
                $this->forge->dropTable('settings_cropper');
                debug("DROP TABLE settings_cropper");
            endif;
            if($this->db->tableExists('config_cropper_settings')) :
                $this->forge->dropTable('config_cropper_settings');
                debug("DROP TABLE config_cropper_settings");
            endif;
        endif;
    }

    public function down()
    {
    }
}