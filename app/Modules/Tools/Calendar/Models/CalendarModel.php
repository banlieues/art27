<?php

namespace Calendar\Models;

use CodeIgniter\Model;
helper('debug');

class CalendarModel extends Model
{
  
    protected $table      = 'events';
    protected $primaryKey = 'id';

   // protected $useAutoIncrement = true;

//    protected $returnType     = 'array';
//    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'start_date','end_date'];

  // protected $useTimestamps = True;
   // protected $createdField  = 'post_created_at';
  //protected $updatedField  = 'post_updates_at';
//    protected $deletedField  = 'deleted_at';

//    protected $validationRules    = [];
  //  protected $validationMessages = [];
  //  protected $skipValidation     = false;
//  protected $beforeInsert =['checkName'];
  

public function init()
 {
		$forge = \Config\Database::forge();

		if(!$this->db->tableExists("events"))
		{
			$fields = [
				'id' => [
					'type'           => 'INT',
					'auto_increment' => true,
				],
				'name' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
				],

				'start_date' => [
					'type' => 'DATETIME',
				],

				'end_date' => [
					'type' => 'DATETIME',
				]
			];
      
			$forge->addField($fields);
			$forge->addPrimaryKey('id');
			$forge->createTable('events');
		};
	}
}