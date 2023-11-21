<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;

class TablesBanCreate extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        return false;

        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $tables = [
            $this->t_avatar_settings,
            $this->t_component_contact, 
            $this->t_component_contact_field, 
            $this->t_contact,
            $this->t_entity, 
            $this->t_field, 
            $this->t_import,
            $this->t_user_account, 
            $this->t_contact_user, 
            $this->t_user_profile,
        ];
        foreach($tables as $tablename) :
            
            if($this->db->tableExists($tablename)) continue;

            // $this->database_l->export_table_metadata('settings_cropper', 'h4', $tablename); die;

            $fields = read_json_file($this->path . '/Database/Init/t_' . $tablename . '.json');
            $metadatas = $this->database_l->convert_export_table_metadata_to_db($fields);
            $primary_key = get_primary_key($tablename);
            $this->database_l->create_table($tablename, $metadatas, $primary_key);
        endforeach;
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function down()
    {
    }
}
