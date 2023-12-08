<?php

namespace Delete\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;

class DeleteModel extends Model
{
	protected $table="ban_query_save";
	protected $tableInscription="inscriptions";
	protected $tableContact="contacts";
	protected $primaryKey= 'id_activity';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';
	protected $fields;



    public function getPartenaire($id_partenaire)
    {
        $builder=$this->db->table("partenaire");
        $builder->where("id_partenaire",$id_partenaire);

        return $builder->get()->getRow();
    }

    public function deletePartenaire($id_partenaire)
    {
        $builder=$this->db->table("partenaire");
        $builder->delete(["id_partenaire"=>$id_partenaire]);

        

        $builder=$this->db->table("partenaire");
        $builder->where("id_partenaire",$id_partenaire);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }

    public function get_partenaire_social_convention($id_partenaire_social_convention)
    {
        $builder=$this->db->table("partenaire_social_convention");
        $builder->where("id_partenaire_social_convention",$id_partenaire_social_convention);

        return $builder->get()->getRow();
    }

    public function delete_partenaire_social_convention($id_partenaire_social_convention)
    {
        $builder=$this->db->table("partenaire_social_convention");
        $builder->delete(["id_partenaire_social_convention"=>$id_partenaire_social_convention]);

        $builder=$this->db->table("partenaire_social_convention");
        $builder->where("id_partenaire_social_convention",$id_partenaire_social_convention);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }

    public function getDocument($id_document)
    {
        $builder=$this->db->table("document_upload");
        $builder->where("id",$id_document);

        return $builder->get()->getRow();
    }

    public function deleteDocument($id_document)
    {
        $builder=$this->db->table("document_upload");
        $builder->delete(["id"=>$id_document]);

        $builder=$this->db->table("document_upload_lien");
        $builder->delete(["id_document"=>$id_document]);

        $builder=$this->db->table("document_upload");
        $builder->where("id",$id_document);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }

   

    /*--- Ici les deletes traversaux commun à tous les CRM -----*/

    public function getQuery($id_query)
    {
        $builder=$this->db->table("user_requete");
        $builder->where("id_requete",$id_query);

        return $builder->get()->getRow();
    }

    public function deleteQuery($id_query)
    {

        $builder=$this->db->table("user_requete");
        $builder->delete(["id_requete"=>$id_query]);


        $builder=$this->db->table("user_requete");
        $builder->where("id_requete",$id_query);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }

    public function getEmail($id_primary)
    {
        $builder=$this->db->table("email_outlook");
        $builder->where("id_primary",$id_primary);

        return $builder->get()->getRow();
    }
    public function deleteEmail($id_primary)
    {

        $builder=$this->db->table("email_outlook");
        $builder->delete(["id_primary"=>$id_primary]);

       /* $builder=$this->db->table("email_outlook_lien");
        $builder->delete(["id_email"=>$id_primary]);*/

        $builder=$this->db->table("email_outlook");
        $builder->where("id_primary",$id_primary);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }

    public function getInjectedForm($id_injected_form)
    {
        $builder=$this->db->table("ban_injected_form");
        $builder->where("id_injected_form",$id_injected_form);

        return $builder->get()->getRow();
    }

    public function deleteInjectedForm($id_injected_form)
    {

        $builder=$this->db->table("ban_injected_form");
        $builder->delete(["id_injected_form"=>$id_injected_form]);


        $builder=$this->db->table("ban_injected_form");
        $builder->where("id_injected_form",$id_injected_form);

        $r=$builder->get()->getRow();

        if(!empty($r))
        {
            return FALSE;
        }
        else
        {
            return true;
        }

    }




}
