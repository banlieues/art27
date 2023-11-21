<?php
/**
 * This is UserProfileModel
**/

namespace Administrator\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'user_profiles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'user_id',
        'gender_id',
        'country_id',
        'avatar',
        'website',
        'phone',
        'gsm',
        'birthday',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_actif',
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


    public function updateUser($data_statut,$id_user)
    {
        if(empty(database_encode('user_accounts', $data_statut))) return false;

        $data_statut['updated_at'] = date('Y-m-d H:i:s');
        $data_statut['updated_by'] = session('loggedUserId');
        $builder = $this->db->table("user_accounts");
        $builder->set($data_statut);
        $builder->where("id",$id_user);
        $builder->update();
    }

    public function deleteRelation($id_user)
    {
        $builder=$this->db->table("user_contacts");
        $builder->delete("id_user",$id_user);
    }

    public function Export()
    {
        $table_source='contact';

        /** field
         * 
         * id -> clé primaire
         * username
         * email
         * password
         * token
         * created_at
         * updated_at
         * created_by
         * updated_by
         * valided
         * activited
         * 
         */
        $table_destination="user_accounts";


        /***
         * 
         * id_user_contacts ->clé primaire
         * id_user
         * id_contact
         * created_at
         * created_by (if 0 par Crm)
         * 
         */

        $table_contacts="user_contacts";

        /****
         * Correspondance
         * 
         * id->id_contact
         * username->login
         * email->email
         * created_at->date_maj
         * updated_by->date_creation
         * created_by->0
         * updated_by->0
         * valided->1
         * activited->1
         * role_id->si minizero, ce sera alors 1 sinon 2
         * 
         * 
         */

         $sql="INSERT INTO user_accounts 
                (id,username,email,created_at,updated_at,valided,activited,role_id,source)
               SELECT id_contact,login,email,date_maj,date_creation,'1','1', CASE statut when '0minirezo' then 1 when '6forum' then 2 end as role_id,'spip'  FROM $table_source  WHERE statut NOT LIKE '0' AND statut NOT LIKE '1comite';   
        ";
       
    }

}
