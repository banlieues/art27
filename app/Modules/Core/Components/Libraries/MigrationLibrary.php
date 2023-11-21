<?php 

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;
use Base\Libraries\InitLibrary;
use CodeIgniter\Database\RawSql;
use Custom\Config\Globals;

class MigrationLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();

        $InitLibrary = new InitLibrary();
        $InitLibrary->GetGlobals();

        $this->forge = \Config\Database::forge();
    }

    public function TableAutorisationAddColumn($column_name)
    {
        if($this->db->fieldExists($column_name, $this->t_autorisation)) return false;

        $fields = [
            $column_name => [ 'type' => 'boolean', 'default' => 0, ],
        ];
        $this->forge->addColumn($this->t_autorisation, $fields); dbdebug();

        // access for superadmins
        $config = new Globals();
        $value = preg_match('/_d$/', $column_name) ? 2 : 1;
        $this->db->table($this->t_autorisation)->set($column_name, $value)->whereIn('id_user', $config->superadmins)->update(); dbdebug();
    }

    public function TableTesorusCreate($table)
    {
        if($this->db->tableExists($table)) return false;
            
        $fields = [
            'id_road' => [ 'type' => 'int', 'auto_increment' => true, ],
            'isActive' => [ 'type' => 'tinyint', 'default' => 1, ],
            'id_cell' => [ 'type' => 'int', 'null' => false, ],
            'id_road_parent' => [ 'type' => 'int', 'null' => false, ],
            'rank' => [ 'type' => 'int', 'null' => true, ],
            'has_text' => [ 'type' => 'tinyint', 'null' => true, ],
            'annotation_fr' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'annotation_nl' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'updated_by' => [ 'type' => 'int', 'null' => false, ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            'created_by' => [ 'type' => 'int', 'null' => false, ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_road', 'id_road');
        $this->forge->createTable($table);
        dbdebug();
    }

    public function TableListCreate($table, $labels=null)
    {
        if(!$this->db->tableExists($table)) :
            $fields = [
                'id' => [ 'type' => 'int', 'auto_increment' => true, ],
                'ref' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, 'unique' => true],
                'label' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => false, ],
                'is_actif' => [ 'type' => 'tinyint', 'default' => 1, ],
                'rank' => [ 'type' => 'int', 'null' => true, ],
                'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
                'updated_by' => [ 'type' => 'int', 'null' => false, ],
                'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addField($fields);
            $this->forge->addPrimaryKey('id', 'id');
            $this->forge->createTable($table);
            dbdebug();
        endif;

        if(empty($this->db->table($table)->countAll()) && !empty($labels)) :
            $i = 0;
            foreach($labels as $label) :
                $data = (object) [];
                $data->ref = convert_utf8_to_url($label);
                $data->label = $label;
                $data->rank = $i;
                $data->created_by = session('loggedUserId');
                $data->updated_by = session('loggedUserId');
                $this->db->table($table)->set(database_encode($table, $data))->insert();
                dbdebug();
                $i++;
            endforeach;
        endif;
    }
}