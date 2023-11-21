<?php  

namespace Messagerie\Models;

use CodeIgniter\Model;

class MessagerieModel extends Model {
    
 
  protected $table="fh_messagerie";
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


  public function getListMessagerie($id_destinataire,$request,$orderBy=NULL,$orderDirection=NULL)
  {
      $this->select("
          fh_messagerie.id,
          fh_messagerie.subject,
          fh_messagerie.content,
          fh_messagerie.id_user,
          fh_messagerie.display,
          fh_messagerie.entity,
          fh_messagerie.id_entity,
          user_accounts.nom,
          user_accounts.prenom,
          fh_messagerie.date_created,
          fh_send_message.vu,
          fh_send_message.date_vu

      ");


   
    $this->join("fh_send_message","fh_send_message.id_messagerie=fh_messagerie.id","left");

    $this->groupStart();
        $this->where("fh_send_message.id_destinataire",$id_destinataire);
        //$this->orWhere("fh_messagerie.id_user",$id_destinataire);
    $this->groupEnd();

    $this->join("user_accounts","user_accounts.id=fh_messagerie.id_user","left");

    $this->where("fh_messagerie.display",1);

      if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
      {
        $items=explode(" ",$request->getVar("itemSearch"));
        
        $fieldSearchs=array(
          "subject",
          "content",
          "user_accounts.nom",
          "user_accounts.prenom",
           
      
          )
        ;
      


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

      if(!is_null($orderBy))
        $this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
    
      return $this->paginate(20);

  }

  public function count_non_lu($id_destinataire)
  {
      $builder=$this->db->table("fh_send_message");
      $builder->where("id_destinataire",$id_destinataire);
      $builder->where("vu",0);
      $builder->where("display",1);

      return $builder->countAllResults();
  }

  public function get_non_lu($id_destinataire)
  {
    $builder=$this->db->table("fh_send_message");
    $builder->select("
          fh_messagerie.id,
          fh_messagerie.subject,
          fh_messagerie.content,
          fh_messagerie.id_user,
          fh_messagerie.display,
          fh_messagerie.entity,
          fh_messagerie.id_entity,
          user_accounts.nom,
          user_accounts.prenom,
          fh_messagerie.date_created,
          fh_send_message.vu,
          fh_send_message.date_vu

      ");
    $builder->where("id_destinataire",$id_destinataire);
    $builder->where("fh_send_message.vu",0);
    $builder->where("fh_send_message.display",1);

    $builder->join("fh_messagerie","fh_messagerie.id=fh_send_message.id_messagerie","left");
    $builder->join("user_accounts","user_accounts.id=fh_messagerie.id_user","left");

    $builder->orderBy("fh_messagerie.date_created");
    return $builder->get()->getResult();
  }

  public function message_view($id_message)
  {
      $this->select("
        fh_messagerie.id,
        fh_messagerie.subject,
        fh_messagerie.content,
        fh_messagerie.id_user,
        fh_messagerie.display,
        fh_messagerie.entity,
        fh_messagerie.id_entity,
        user_accounts.nom,
        user_accounts.prenom,
        fh_messagerie.date_created,
        fh_send_message.vu,
        fh_send_message.date_vu

    ");



    $this->join("fh_send_message","fh_send_message.id_messagerie=fh_messagerie.id","left");
    $this->join("user_accounts","user_accounts.id=fh_messagerie.id_user","left");
    $this->where("fh_messagerie.id",$id_message);
   

    return $this->get()->getRow();

  }

  public function set_message_lu($id_messagerie,$id_destinataire)
  {
      $builder=$this->db->table("fh_send_message");
      $builder->where("id_messagerie",$id_messagerie);
      $builder->where("id_destinataire",$id_destinataire);

      $data["vu"]=1;
      $data["date_vu"]=date("Y-m-d H:i:s");

      $builder->update($data);
  }

  public function get_note_of_entity($entity,$id_entity)
  {
          $this->select("
          fh_messagerie.id,
          fh_messagerie.subject,
          fh_messagerie.content,
          fh_messagerie.id_user,
          fh_messagerie.display,
          fh_messagerie.entity,
          fh_messagerie.id_entity,
          user_accounts.nom,
          user_accounts.prenom,
          fh_messagerie.date_created,
          fh_send_message.vu,
          fh_send_message.date_vu,
          fh_send_message.id_destinataire

      ");



      $this->join("fh_send_message","fh_send_message.id_messagerie=fh_messagerie.id","left");
      $this->join("user_accounts","user_accounts.id=fh_messagerie.id_user","left");

      $this->where("fh_messagerie.id_entity",$id_entity);
      $this->where("fh_messagerie.entity",$entity);

      $this->orderBy("date_created DESC");

      $this->groupBy("fh_messagerie.id");

      return $this->get()->getResult();
  }

  public function get_note_of_entity_no_lu($entity,$id_entity,$id_destinataire)
  {
          $this->select("
          fh_messagerie.id,
          fh_messagerie.subject,
          fh_messagerie.content,
          fh_messagerie.id_user,
          fh_messagerie.display,
          fh_messagerie.entity,
          fh_messagerie.id_entity,
          user_accounts.nom,
          user_accounts.prenom,
          fh_messagerie.date_created,
          fh_send_message.vu,
          fh_send_message.date_vu,
          fh_send_message.id_destinataire

      ");



      $this->join("fh_send_message","fh_send_message.id_messagerie=fh_messagerie.id","left");
      $this->join("user_accounts","user_accounts.id=fh_messagerie.id_user","left");

      $this->where("fh_messagerie.id_entity",$id_entity);
      $this->where("fh_messagerie.entity",$entity);
      $this->where("fh_send_message.id_destinataire",$id_destinataire);
      $this->where("fh_send_message.vu",0);
      $this->where("fh_send_message.display",1);

      $this->orderBy("date_created DESC");

      return $this->get()->getResult();
  }

  public function is_entity_no_lu($entity,$id_entity,$id_destinataire)
  {

      $this->join("fh_send_message","fh_send_message.id_messagerie=fh_messagerie.id","left");

      $this->where("fh_messagerie.id_entity",$id_entity);
      $this->where("fh_messagerie.entity",$entity);
      $this->where("fh_send_message.id_destinataire",$id_destinataire);
      $this->where("fh_send_message.vu",0);
      $this->where("fh_send_message.display",1);

      $this->orderBy("date_created DESC");


      return $this->get()->getResult();
  }


  public function set_note($post)
  {
        $data["subject"]=$post["subject_note"];

        if(!empty(trim($post["object_note"])))
        {
          $data["content"]=trim($post["object_note"]);
        }

        $data["id_user"]=session()->get("loggedUserId");
        $data["entity"]=$post["entity"];
        $data["id_entity"]=$post["id_entity"];

        $builder=$this->db->table("fh_messagerie");
        $builder->insert($data);

        $id_messagerie=$this->db->insertId();

        if(isset($post["user_note"])&&!empty($post["user_note"]))
        {
           foreach($post["user_note"] as $id_destinataire)
           {
              $data_send=null;
             
              $data_send["id_messagerie"]=$id_messagerie;
              $data_send["id_destinataire"]=$id_destinataire;

              $builder=$this->db->table("fh_send_message");
              $builder->insert($data_send);
              
           }
        }
  }

}