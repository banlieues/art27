<?php
/**
 * This is Avatar Model
**/

namespace Administrator\Models;

use CodeIgniter\Model;

class AvatarModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'user_avatar_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'label', 
        'description', 
        'rank', 
        'param', 
        'value', 
        'lur_created_at',
        'lur_updated_at',
        'lur_created_by',
        'lur_updated_by',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    // protected $validationRules = [];
    // protected $validationMessages = [];
    // protected $skipValidation = false;
    // protected $cleanValidationRules = true;

    public function getTable() {return $this->table;}

    public function get_cropper_settings()
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $result = $builder->get()->getResult();
        return $result;
    }

    public function set_cropper_settings($loggedUserId = 0, $cropper_settings = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $id = 0;

        foreach($cropper_settings AS $key => $value)
        {
            // if ($key <> 'selected_cropper') {;}
            // echo $key.' => '.$value.'<br>';
            $parse = explode('_', $key);
            $param = $parse[1];
            // $label = $key;
            // $description = $key;
            // $rank = ++$rank;
            $param = $param;
            $value = $value ?? 'default';
            // $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // $created_by = $loggedUserId;
            $updated_by = session('loggedUserId');
            ++$id;

            $values = [
                // 'label' => $label, 
                // 'description' => $description, 
                // 'rank' => $rank, 
                'param' => $param, 
                'value' => $value, 
                // 'created_at' => $created_at, 
                'updated_at' => $updated_at, 
                // 'created_by' => $created_by, 
                'updated_by' => $updated_by,
            ];

            $builder->where('id', $id);
            $result = $builder->update($values);
        }

        return $result;
    }

}
