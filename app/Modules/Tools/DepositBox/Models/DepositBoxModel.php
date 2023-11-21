<?php
/**
 * This is DepositBox Module Model 
**/

namespace DepositBox\Models;

use CodeIgniter\Model;

class DepositBoxModel extends Model
{
    protected $table = 'deposit_box';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'label',
        'description',
        'comment',
        'keywords', 
        'filename', 
        'filesize', 
        'extension',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'actived',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';

    // Validation
    // protected $validationRules = [];
    // protected $validationMessages = [];
    // protected $skipValidation = false;
    // protected $cleanValidationRules = true;

    public function getTable() {return $this->table;}
    
    public function get_total_filesize()
    {
        $builder = $this->table($this->table);
        // return $builder->selectSum('filesize')->get();
        return $builder->selectSum('filesize')->get()->getResult();
    }

}
