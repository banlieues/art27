<?php
/**
 * This is DocumentsTemplates Module Model 
**/

namespace DocumentsGenerator\Models;

use CodeIgniter\Model;

class DocumentsGeneratorModel extends Model
{
    protected $table = 'templates_documents';
    protected $tableArticles="activities";
    protected $tableInscriptions="inscriptions";
    protected $tableContacts="contact";
    protected $tableTemplates="templates_documents";
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object'; // object

    // protected $useSoftDeletes = true;
    protected $protectFields = true;



    public function getTable() {return $this->table;}


    /***
     * 
     * On récupére la liste
     * 1. On récupére les données de l'activité pour récupérer les valeurs des filtes
     * Filtre peuvent être -> id_activity, id_rubrique, statut, type asbl
     * 
     * 2. On applicque les filtres sur documentstempletes et on obtient une liste
     * 
     */

    public function getList($id_activity,$id_contact)
	{
		//1. Requête pour connaitre les valeurs des filtres
        $builder=$this->db->table($this->tableInscriptions);
        $builder->select
                ( "
                    $this->tableArticles.id_rubrique,   
                    $this->tableArticles.asbl,
                    $this->tableInscriptions.id_activity,
                    $this->tableInscriptions.id_contact,
                    $this->tableInscriptions.statutsuivi,

                ");

        $builder->join($this->tableArticles,"$this->tableArticles.id_activity=$this->tableInscriptions.id_activity","left");
        $builder->where("$this->tableInscriptions.id_activity",$id_activity);
        $builder->where("$this->tableInscriptions.id_contact",$id_contact);

        $article=$builder->get()->getRow();

        if(empty($article))
            return NULL;

        //2. On récupérer la liste en fonction des filtres
        $builder=$this->db->table($this->tableTemplates);
        $builder->select
                ( "
                    $this->tableTemplates.label as titre,
                    $this->tableTemplates.id as id_template_document,    

                ");
        $builder->join("liste_type_asbl","liste_type_asbl.id=$this->tableTemplates.id_type_asbl","left");
        $builder->where("actived",1);
        $builder->where("liste_type_asbl.label",$article->asbl);
        $builder->orderBy("$this->tableTemplates.label");

        return $builder->get()->getResult();
	}

    public function getDocument($id_template_document,$id_activity,$id_contact)
    {
        $builder=$this->db->table($this->tableTemplates);
        $builder->where("id",$id_template_document);

        return $builder->get()->getRow();

    }

    public function getDocumentById($id_template_document)
    {
        $builder=$this->db->table($this->tableTemplates);
        $builder->select("content");
        $builder->where("id",$id_template_document);

       
        $document=$builder->get()->getRow();

        if(!empty($document))
        {
            return $document->content;
        }
        else
        {
            return "<b>ERREUR: IMPOSSIBLE DE TROUVER UN MODELE CORRESPONDANT A CE TAG</b>";
        }

    }

    public function insertInscriptionsDocument($data)
    {
        $builder=$this->db->table("inscriptions_document");
        return $builder->insert($data);
    }

    public function getDocumentsInscription($id_inscription)
    {
         $builder=$this->db->table("inscriptions_document");
         $builder->where("id_inscription",$id_inscription);
         $builder->orderBy("date_created");
         return $builder->get()->getResult();
 
 
    }

    public function getNameFile($id_inscription,$id_document_template)
    {
        $builder=$this->db->table("inscriptions_document");
         $builder->where("id_inscription",$id_inscription);
         $builder->where("id_document_template",$id_document_template);
         $builder->where("id_contact",$id_contact);
         $builder->where("id_activity",$id_activity);
         return $builder->get()->getRow();

    }

   /* public function getOneProfilRegistration($id_template_document,$id_activity,$id_contact)
    {
        $builder=$this->db->table($this->tableInscriptions);
        $builder->join($this->tableArticles,"$this->tableArticles.id_activity=$this->tableInscriptions.id_activity","left");
        $builder->join($this->tableContacts,"$this->tableContacts.id_contact=$this->tableInscriptions.id_contact","left");
        $builder->where("$this->tableInscriptions.id_activity",$id_activity);
        $builder->where("$this->tableInscriptions.id_contact",$id_contact);

        $builder->select("
            $this->tableContacts.nom as nom,
            $this->tableContacts.prenom as prenom,
            $this->tableContacts.adresse as adresse,
            $this->tableContacts.codepostal as codepostal,
            $this->tableContacts.localite as localite,
            $this->tableContacts.codecourtoisie as codecourtoisie,
        ");

        return $builder->get()->getRow();

    }*/




}
