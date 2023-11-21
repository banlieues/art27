<?php 

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;

class DatabaseLibrary extends BaseLibrary
{
    public function __construct($namespace)
    {
        parent::__construct($namespace);

        $this->forge = \Config\Database::forge();
    }

    public function create_table($tablename, $metadatas, $primary_key)
    {
        $this->forge->addField($metadatas);
        $this->forge->addKey($primary_key, true);
        if(!$this->db->tableExists($tablename)) $this->forge->createTable($tablename);
    }

    public function convert_export_table_metadata_to_db($fields)
    {
        $datas = (object) [];
        foreach($fields as $key=>$metadata):
            if(!empty(preg_match('/^_/', $key))) continue;
            $data = (object) [];

            // forge fields
            $forge_fields = ['type', 'constraint', 'unsigned', 'null', 'auto_increment', 'unique'];
            foreach($forge_fields as $ff) if(!empty($metadata->$ff)) $data->$ff = $metadata->$ff;
            
            // export from getFieldData
            if(!empty($metadata->max_length)) $data->constraint = $metadata->max_length;
            if(!empty($metadata->nullable)) $data->null = true;
            if(!empty($metadata->default)) :
                if($metadata->default=='current_timestamp()') $data->default = new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP');
                elseif($metadata->default!='null') $data->default = $metadata->default;
            endif;
            if(!empty($metadata->primary_key)) :
                $data->auto_increment = true;
                $data->unique = 1;
            endif;
            $datas->$key = (array) $data;
        endforeach;

        return (array) $datas;
    }

    // public function get_primary_key($fields)
    // {
    //     foreach($fields as $key=>$metadata) :
    //         if(!empty($metadata->primary_key)) return $key;
    //     endforeach;
    // }

    public function check_table_common($label, $structure)
    {
        if(!$this->db->tableExists($label)) $this->table_create($label, $structure);
        else $this->table_update($label, $structure);
    }

    public function table_create($label, $structure)
    {
        $primary = (object) [];
        foreach($structure as $ref=>$field) :
            $field->name = $ref;
            if(isset($field->primary_key) && $field->primary_key == 1) $primary = $field;
        endforeach;
        $auto_increment = $primary->type=='int' ? 'AUTO_INCREMENT' : '';
        $primary_type = $primary->type=='varchar' ? $primary->type . '(' . $primary->max_length . ')' : $primary->type;
        $primary_length = $primary->type=='text' ? '(255)' : '';
        $q = "
            CREATE TABLE $label (
                `$primary->name` $primary_type NOT NULL $auto_increment, 
                PRIMARY KEY(`$primary->name`$primary_length)
            )
        ";
        $this->db->query($q);
        // _print($this->db->getLastQuery()->getQuery());

        $this->table_update($label, $structure);
    }

    public function table_update($label, $structure)
    {
        foreach($structure as $name=>$metadata) :
            $metadata->name = $name;
            $this->table_update_by_field($label, $metadata);
        endforeach;
    }

    private function table_update_by_field($label, $metadata)
    {
        $fields_db = $this->db->getFieldNames($label);
        
        if(!in_array($metadata->name, $fields_db)) :
            $def = $this->convert_metadata_json_to_query($metadata);
            $q = "ALTER TABLE $label ADD `$metadata->name` $def";
            $this->db->query($q);
            // _print($this->db->getLastQuery()->getQuery());
        endif;
        
        if(isset($metadata->has_text) && $metadata->has_text==1) :
            $name_text = $metadata->name . '_text';                
            if(!in_array($name_text, $fields_db)) :
                $q = "ALTER TABLE $label ADD `$name_text` TEXT NULL AFTER `$metadata->name`";
                $this->db->query($q);
                // _print($this->db->getLastQuery()->getQuery());
            endif;
        endif;
    }

    // public function generate_sql_from_json($table, $file, $file_init=null)
    // {
        
    //     $fields = json_decode(file_get_contents($file));
    //     $primary = (object) [];
    //     foreach($fields as $field) if(isset($field->primary_key) && $field->primary_key == 1) $primary = $field;
    //     $auto_increment = ($field->type=='int') ? 'AUTO_INCREMENT' : '';
    //     $tableExists = 1;
    //     if(!$this->db->tableExists($table)) :
    //         $tableExists = 0;
    //         $q = "CREATE TABLE $table (`$primary->name` $primary->type NOT NULL $auto_increment, PRIMARY KEY(`$primary->name`))";
    //         $this->db->query($q);
    //         // _print($this->db->last_query());
    //     endif;

    //     $fields_db = $this->db->getFieldNames($table);
    //     foreach($fields as $name=>$metadata) :
    //         $def = $this->convert_metadata_json_to_query($metadata);
    //         if(!in_array($name, $fields_db)) :
    //             $this->db->query("ALTER TABLE $table ADD `$name` $def");
    //             // _print($this->db->last_query());
    //         endif;
    //     endforeach;

    //     if($tableExists == 0 && isset($file_init)) $this->generate_sql_entries_from_json($table, $file_init);

    //     // _print("$table is checked.");
    // }

    public function check_database__fe_roads($roads)
    {
        $TesorusLibrary = new \Tesorus\Libraries\MysqlLibrary();
        $TesorusLibrary->check_database__fe_feature();
        foreach($roads as $road) :
            $TesorusLibrary->check_database__fe_road($road);
        endforeach;
        $TesorusLibrary->check_database($roads);
    }
    
    public function check_database_table_common($table, $file)
    {
        if(file_exists($file)) :
            if(!$this->db->tableExists($table)) $this->sql_create_table_from_file($table, $file);
            else $this->sql_alter_table_from_file($table, $file);
        endif;
    }
    
    public function check_database_table_fe_road($road_name)
    {
        $this->load->add_package_path(APPPATH . 'modules/fe');
        $this->load->library('fe_mysql_lib');
        $this->fe_mysql_lib->check_database__fe_feature();
        $this->fe_mysql_lib->check_database__fe_road($road_name);
        $this->load->remove_package_path(APPPATH . 'modules/fe');
    }

    public function sql_create_table_from_file($table, $file)
    {
        $fields = json_decode(file_get_contents($file));
        $this->sql_create_table_from_fields($table, $fields);
    }

    public function sql_create_table_from_fields($table, $fields)
    {
        $primary = (object) [];
        foreach($fields as $ref=>$field) :
            $field->name = $ref;
            if(isset($field->primary_key) && $field->primary_key == 1) $primary = $field;
        endforeach;
        $auto_increment = $primary->type=='int' ? 'AUTO_INCREMENT' : '';
        $primary_type = $primary->type=='varchar' ? $primary->type . '(' . $primary->max_length . ')' : $primary->type;
        $primary_length = $primary->type=='text' ? '(255)' : '';
        $q = "
            CREATE TABLE $table (
                `$primary->name` $primary_type NOT NULL $auto_increment, 
                PRIMARY KEY(`$primary->name`$primary_length)
            )
        ";
        $this->db->query($q);

        $this->sql_alter_table_from_fields($table, $fields);
    }

    public function sql_alter_table_from_file($table, $file)
    {
        $fields = json_decode(file_get_contents($file));
        $this->sql_alter_table_from_fields($table, $fields);
    }

    public function sql_alter_table_from_fields($table, $datas)
    {
        foreach($datas as $name=>$metadata) :
            $metadata->name = $name;
            $this->sql_alter_table_one_field($table, $metadata);
        endforeach;
    }

    private function sql_alter_table_one_field($table, $metadata)
    {
        $fields_db = $this->db->getFieldNames($table);
        
        if(!in_array($metadata->name, $fields_db)) :
            $def = $this->convert_metadata_json_to_query($metadata);
            $q = "ALTER TABLE $table ADD `$metadata->name` $def";
            $this->db->query($q);
            // _print($this->db->last_query());
        endif;
        
        if(isset($metadata->has_text) && $metadata->has_text==1) :
            $name_text = $metadata->name . '_text';                
            if(!in_array($name_text, $fields_db)) :
                $q = "ALTER TABLE $table ADD `$name_text` TEXT NULL AFTER `$metadata->name`";
                $this->db->query($q);
                // _print($this->db->last_query());
            endif;
        endif;
    }

    public function sql_insert_to_table($table, $datas)
    {
        $fields = $this->db->getFieldNames($table);
        foreach($datas as $data) :
            if(in_array('updated_by', $fields)) $data->updated_by = session('loggedUserId');
            if(in_array('created_by', $fields)) $data->created_by = session('loggedUserId');
            $this->db->insert($table, $data);
            // _print($this->db->last_query());
        endforeach;
    }

    public function sql_insert_to_table_from_file($table, $file)
    {
        $datas = json_decode(file_get_contents($file));
        $this->sql_insert_to_table($table, $datas);
    }

    private function convert_metadata_json_to_query($metadata)
    {
        $def = '';
        if(!empty($metadata->type)) $def .= $metadata->type;
        if(!empty($metadata->max_length)) $def .= '(' . $metadata->max_length . ')';
        if(isset($metadata->default)) :
            if($metadata->default=="current_timestamp()") :
                $def .= ' NOT NULL DEFAULT ' . $metadata->default;
            elseif($metadata->default == "null") :
                $def .= ' NULL';
            elseif(strlen($metadata->default)>0) :
                $def .= ' NOT NULL DEFAULT "' . $metadata->default . '"';
            endif;
        else :
            $def .= ' NOT NULL';
        endif;

        return $def;
    }

    public function export_table_datas($table_old, $database_old, $table_new=null, $limit=null)
    {
        // --- Code to add in migration ---
        // $database_l = new \Components\Libraries\DatabaseLibrary(__NAMESPACE__);
        // $database_l->export_table_datas(###TABLE_OLD###, ###DB_OLD###, ###TABLE_NEW###, ###LIMIT###);
        // $datas = json_decode(file_get_contents(MODULE_DIR . $module . '/Database/Init/d_' . ###TABLE_NEW### . '.json'));
        // debugd($datas);

        $connect_old = \Config\Database::connect($database_old);

        $table_new = !empty($table_new) ? $table_new : $table_old;
        $file = APPPATH . 'Modules/' . $this->module . '/Database/Init/d_' . $table_new . '.json';

        $query = $connect_old->table($table_old);
        if(!empty($limit)) $query->limit($limit);
        $fields = $query->get()->getResult();

        // IMPORTANT : need to check json file generated !
        write_datas_to_json_file($fields, $file);

        return $fields;
    }

    public function export_table_metadata($table_old, $database_old, $table_new=null)
    {
        // --- Code to add in migration ---
        // $database_l = new \Components\Libraries\DatabaseLibrary(__NAMESPACE__);
        // $database_l->export_table_metadata(###TABLE_OLD###, ###DB_OLD###, ###TABLE_NEW###);
        // $datas = json_decode(file_get_contents(MODULE_DIR . $module . '/Database/Init/t_' . ###TABLE_NEW### . '.json'));
        // debugd($datas);

        $connect_old = \Config\Database::connect($database_old);

        $table_new = !empty($table_new) ? $table_new : $table_old;
        $file = APPPATH . 'Modules/' . $this->module . '/Database/Init/t_' . $table_new . '.json';

        $fields = $connect_old->getFieldData($table_old);
        $object = (object) [];
        foreach($fields as $field) :
            $ref = $field->name;

            unset($field->name);
            if(!empty($field->primary_key)) :
                unset($field->default); 
            else :
                unset($field->primary_key);
                if(empty($field->default)) $field->default = 'null';
            endif;
            if($field->max_length == null) unset($field->max_length);

            $object->$ref = $field;
        endforeach;

        // IMPORTANT : need to check json file generated !
        write_datas_to_json_file($object, $file);

        return $object;
    }
}