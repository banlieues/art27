<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;
use Custom\Config\Globals;

class TablesAdministrator_230709 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->t_user = 'user_accounts';
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableUserAccounts();
        $this->DatabaseLatinToUtf8(getenv('database.default.database'));
        $this->TablesDrop();
        $this->TablesLatinToUtf8(getenv('database.default.database'));
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function DatabaseLatinToUtf8($database)
    {
        $encoding = $this->db->table('information_schema.SCHEMATA')->select('default_character_set_name')->where('schema_name', $database)->get()->getRow();
        
        if(empty($encoding->default_character_set_name) || !preg_match('/utf8/', $encoding->default_character_set_name)) :
            $this->db->query("ALTER DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            debug("ALTER DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        endif;
    }
    
    private function TablesLatinToUtf8($database)
    {
        $data = [
            'TABLE_SCHEMA' => $database,
            'CHARACTER_SET_NAME' => 'latin1',
            'COLLATION_NAME' => 'latin1_swedish_ci',
        ];
        $schemas = $this->db->table('information_schema.`COLUMNS`')->distinct()->select('TABLE_NAME, COLUMN_NAME')->where($data)->where('TABLE_NAME not like "%_latin"')->get()->getResult();
        $tables = array_values(array_unique(array_filter(array_column($schemas, 'TABLE_NAME'))));

        foreach($tables as $table) :
            // $table_bkp = $table . '_latin';
            // $this->db->query("CREATE TABLE $table_bkp LIKE $table;");
            // debug("CREATE TABLE $table_bkp LIKE $table;");

            // $this->db->query("INSERT INTO $table_bkp SELECT * FROM $table;");
            // debug("INSERT INTO $table_bkp SELECT * FROM $table;");

            $this->db->query("ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            debug("ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        endforeach;
    }

    private function TableUserAccounts()
    {
        $tamhau = $this->db->table($this->t_user)->where('username', 'tamhau')->get()->getRow();
        if(isset($tamhau)) :
            $this->db->table($this->t_user)->set('username', 'tamo')->where('username', 'tamhau')->update();
            dbdebug();
        endif;
    }
    
    private function TablesDrop()
    {
        $tables = [
            'em_emodel_bis',
            'em_template',
            'ev_cycle',
            'ev_email',
            'ev_email_bis',
            'ev_email_depot',
            'ev_emodel',
            'ev_event',
            'ev_event_daytime',
            'ev_event_inscr',
            'ev_event_inscr_h',
            'ev_event_inscr_peb',
            'ev_event_link',
            'ev_event_person',
            'ev_event_user',
            'ev_list_event_category',
            'ev_list_event_status',
            'ev_list_language',
            'list_event_status',
            'list_inscr_status',
            'users_autorisation',
            '_fe_feature',
        ];
        foreach($tables as $table) :
            if($this->db->tableExists($table)) :
                $this->forge->dropTable($table);
                debug("DROP TABLE $table");
                // dbdebug();
            endif;
        endforeach;
    }

    public function down()
    {
    }
}