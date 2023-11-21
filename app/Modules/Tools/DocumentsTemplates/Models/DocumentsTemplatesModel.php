<?php

namespace DocumentsTemplates\Models;

use CodeIgniter\Model;

class DocumentsTemplatesModel extends Model
{
    protected $table = 'templates_documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // object
    // protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'id',
        'label',
        'description',
        'content',
        'email_subject', 
        'email_body', 
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

    public function getListTemplatesModel($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
            $this->table.id, 
            $this->table.label,
            $this->table.description,
            $this->table.email_subject,
            $this->table.created_at,
            $this->table.updated_at,
            $this->table.created_by,
            $this->table.updated_by,
            $this->table.actived,
            user_created.username as name_created,
            user_updated.username as name_updated,
            user_created.avatar as avatar_created,
            user_updated.avatar as avatar_updated,
            liste_type_asbl.label as type_asbl
        ");

        $this->join("user_accounts as user_created","user_created.id=$this->table.created_by", "left");       
        $this->join("user_accounts as user_updated","user_updated.id=$this->table.updated_by", "left");  

        $this->join("liste_type_asbl","liste_type_asbl.id=$this->table.id_type_asbl","left");

        // $this->where("typepart<>", " ");
        // itemSearch exists
        if ($request->getVar("itemSearch") && !empty(trim($request->getVar("itemSearch"))))
        {
            $items = explode(" ", $request->getVar("itemSearch"));
            $fieldSearchs = [
                "$this->table.description",
                "$this->table.label",
                "$this->table.email_subject",
                "user_updated.username",
                "liste_type_asbl.label"
            ];

            $this->groupStart();
                foreach($items as $item):
                    $this->groupStart();
                        foreach($fieldSearchs as $fieldSearch):
                            $this->orLike($fieldSearch,$item);
                        endforeach;
                    $this->groupEnd();
                endforeach;
            $this->groupEnd();
        }
        //$this->groupBy("$this->table.id");
        if(!is_null($orderBy))
            $this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
        
        // $this->like("nom", "Wil");
        return $this->paginate(20);
	}

    public function getOneTemplateModel($id_template)
    {
        $this->select("
            $this->table.id, 
            $this->table.label,
            $this->table.description,
            $this->table.email_subject,
            $this->table.created_at,
            $this->table.updated_at,
            $this->table.created_by,
            $this->table.updated_by,
            $this->table.actived,
            user_created.username as name_created,
            user_updated.username as name_updated,
            user_created.avatar as avatar_created,
            user_updated.avatar as avatar_updated,
            liste_type_asbl.id as id_type_asbl
            liste_type_asbl.label as type_asbl
        ");

        $this->join("user_accounts as user_created","user_created.id=$this->table.created_by", "left");       
        $this->join("user_accounts as user_updated","user_updated.id=$this->table.updated_by", "left");  
        $this->join("liste_type_asbl","liste_type_asbl.id=$this->table.id_type_asbl","left");
        $this->where("$this->table.id",$id_template);

        return $this->get()->getRow();
    }


    public function get_list_statut_docu()
    {
        $builder=$this->db->table("list_template_document_statut");

        return $builder->get()->getResult();
    }

    public function get_liste_type_asbl()
    {
        $builder=$this->db->table("liste_type_asbl");

        return $builder->get()->getResult();
    }

}
