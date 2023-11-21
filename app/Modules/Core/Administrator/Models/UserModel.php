<?php
/**
 * This is Member Model
**/

namespace Administrator\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'user_accounts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'is_actif',
        'avatar',
        'date_debut_automatique',
        'date_fin_automatique',
        'email',
        'id_user_back_up',
        'is_mail_automatique',
        'is_salle',
        'message_automatique',
        'nom',
        'password',
        'phone',
        'prenom',
        'role_id',
        'source',
        'token',
        'username',
        'valided',
        'website',
        'updated_at',
        'updated_by',
        'created_at',
        'created_by',
    ];

    // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';

    // Validation
    // protected $validationRules = [];
    // protected $validationMessages = [];
    // protected $skipValidation = false;
    // protected $cleanValidationRules = true;

    public function getTable() {return $this->table;}

    public function UsersActive()
    {
        $this->select('id, nom, prenom, email');
        $this->where('is_actif', 1);
        $this->where('valided', 1);
        $this->groupStart();
            $this->where('is_salle', null);
            $this->orWhere('is_salle', 0);
        $this->groupEnd();
        $this->orderBy('nom');
        $users = database_decode($this->find());

        return $users;
    }

    public function getListUsers($request,$orderBy=NULL,$orderDirection=NULL)
    {
        $this->select("
            $this->table.id as id,
            $this->table.nom as nom,
            $this->table.prenom as prenom,
            $this->table.username as username,
            $this->table.email,
            $this->table.created_at,
            $this->table.updated_at,
            $this->table.created_by,
            $this->table.updated_by,
            $this->table.valided,
            $this->table.is_actif,
            $this->table.role_id,
            $this->table.source,
            $this->table.avatar,
            list_role.label as role
        ");

        $this->join("list_role","list_role.id=role_id","left");
        // $this->join("user_profiles","user_profiles.user_id=user_accounts.id","left");

        if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
        {
            $items=explode(" ",$request->getVar("itemSearch"));
            $fieldSearchs=array("email","nom","prenom","source","list_role.label","source","username");
            $this->groupStart();
                foreach($items as $item):
                    $this->groupStart();
                    foreach($fieldSearchs as $fieldSearch):
                        $this->orLike($fieldSearch,$item);
                    endforeach;
                    $this->groupEnd();
                endforeach;
            $this->groupEnd();
        };
        if(!is_null($orderBy))
            $this->orderBy(sql_orderByDirection($orderBy,$orderDirection));

        //$this->orderBy("nom ASC,prenom ASC,username ASC");
        $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;

        return $this->paginate($per_page);
    }

    public function Userspager()
    {
        return $this->pager;
    }

    public function UserContactInsert($data)
    {
        $data['created_by'] = session('loggedUserId');
        $data['updated_by'] = session('loggedUserId');
        $builder = $this->db->table("user_contacts");
        return $builder->insert($data);
    }
}
