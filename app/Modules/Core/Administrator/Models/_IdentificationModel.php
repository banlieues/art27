<?php
/**
 * This is IdentificationModel
**/

namespace Administrator\Models;

use CodeIgniter\Model;

class IdentificationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'user_accounts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'username',
        'email',
        'password',
        'token',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'valided',
        'is_actif',
        'role_id',
        'source',
        'nom',
        'prenom'
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

    public function __construct()
    {
        parent::__construct();

        $globals = new \Custom\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $globals_module = new \Administrator\Config\Globals();
        foreach($globals_module as $global_module=>$value) $this->$global_module = $value;
    }

    public function getTable() {
        return $this->table;
    }

    // public function getInfoAccount($id_user)
    // {
    //     $q = $this->db->table($this->t_user);

    //     $q->select("$this->t_user.*");
    //     $q->select("$this->t_user_profile.*, $this->t_user_profile.id as id_profile");
    //     $q->select("$this->t_l_user_role.label as role_label");
        
    //     $q->join($this->t_user_profile, "$this->t_user_profile.user_id=$this->t_user.id", "left");
    //     $q->join($this->t_l_user_role, "$this->t_l_user_role.id=$this->t_user.role_id", "left");

    //     $q->where("$this->t_user.id", $id_user);

    //     return $q->get()->getFirstRow();
    // }  

    // public function insertProfil($data)
    // {
    //     $data['created_by'] = session('loggedUserId');
    //     $data['updated_by'] = session('loggedUserId');
    //     $builder = $this->db->table("user_profiles");
    //     return $builder->insert($data);
    // }

}
