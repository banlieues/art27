<?php

namespace Mail\Models;

use Base\Models\BaseModel;

class MailModel extends BaseModel
{
    protected $primaryKey = 'id_primary';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->q_email = $this->db->table($this->t_email);
        $this->q_user = $this->db->table($this->t_user);
    }

    public function templateGet($id_template)
    {
        $q = $this->db->table($this->t_mt_template);
        $q->select("
            $this->t_mt_template.*,
            $this->t_mt_template.subject_fr as subject,
            CONCAT('<br>', $this->t_mt_template.hello_fr, $this->t_mt_template.content_fr,$this->t_mt_template.greetings_fr) as message,
        ");
        $q->where('id_template', $id_template);
        $template = $q->get()->getRow();

        return database_decode($template);
    }

    public function templatesGet()
    {
        $templates = $this->db->table($this->t_mt_template)->where('is_activated', 1)->get()->getResult();

        return database_decode($templates);
    }

    public function RecipientGetByProfil($id_profil)
    {
        $q = $this->db->table($this->t_profil);
        $q->select("
            $this->t_contact.nom_contact as lastname,
            $this->t_contact.prenom_contact as name,
            $this->t_profil.id_contact_profil,
            $this->t_profil.profil_email as email,
        ");
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_profil.id_contact", 'left');
        $q->where('id_contact_profil', $id_profil);
        $profil = $q->get()->getRow();

        return $profil;
    }

    public function email_get_list()
    {
        $emails = $this->db->table($this->t_email)->where('isArchive is null or isArchive <> 1')->get()->getResult();
        $emails = database_decode($emails);
        $i = 0;
        foreach($emails as $email):
            if(!empty($email->sender->id_user)) :
                $email->sender = $this->user_get_by_id($email->sender->id_user);
            endif;
            $emails[$i] = $email;
            $i++;
        endforeach;

        return $emails;
    }

    public function user_get_by_id($id_user)
    {
        $user = database_decode($this->db->table($this->t_user)->where('id', $id_user)->get()->getRow());
        if(empty($user)) return false;

        $data = (object) [];
        $data->id_user = $user->id;
        $data->name = !empty($user->prenom) ? $user->prenom : '';
        $data->lastname = !empty($user->nom) ? $user->nom : '';
        $data->email = $user->email;

        return $data;
    }

    public function email_get_by_id($id_email)
    {
        $email = database_decode($this->db->table($this->t_email)->where($this->primaryKey, $id_email)->get()->getRow());
        
        return $email;
    } 

    // public function email_update_by_id($id_email, $table, $post)
    // {
    //     $post->updated_at = date('Y-m-d H:i:s');
    //     $post->updated_by = session('loggedUserId');
    //     database_encode($table, $post);
    //     $q = $this->db->table($table);
    //     $q->where($this->primaryKey, $id_email)->set($post)->update();
    // } 

    public function email_delete($id_email)
    {
        $this->db->table($this->t_email)->where($this->primaryKey, $id_email)->delete();
    } 

    public function insert_attachment($post)
    {
        $q = $this->db->table($this->t_attach);
        $q->where('orig_name', $post['orig_name']);
        $q->where('client_name', $post['client_name']);
        $q->where('file_ext', $post['file_ext']);
        $q->where('file_size', $post['file_size']);
        if(isset($post['is_image']) && $post['is_image']==1):
            $q->where('image_width', $post['image_width']);
            $q->where('image_height', $post['image_height']);
        endif;
        $attachs = $q->get()->getResult();

        if(isset($attachs[0])):
            $id_attach = $attachs[0]->id_attach;
        else: 
            $post['created_by'] = session('loggedUserId');
            $this->db->table($this->t_attach)->set($post)->insert();
            $id_attach = $this->db->getInsertID();
        endif;

        return $id_attach;
    }

    public function get_recip_option_text()
    {
        $recipients = $this->SendersUserGet();
        $recipients[] = generic_crm_email();
        
        return $recipients;
    }
    
    public function sendersDefault()
    {
        $senders[0] = generic_crm_email();

        return $senders;
    }
    
    public function replysDefault()
    {
        $replys = $this->SendersUserGet();

        return $replys;
    }

    public function SendersUserGet()
    {
        $q = $this->db->table($this->t_user);
        $q->select('id, prenom as name, nom as lastname, email');
        $users = $q->get()->getResult();

        return $users;
    }

    public function SenderGetByUser($id_user)
    {
        $q = $this->db->table($this->t_user);
        $q->select('id, prenom as name, nom as lastname, email');
        $q->where('id', $id_user);
        $user = $q->get()->getRow();

        return $user;
    }

    public function EmailSave($post,$id_demande=0)
    {
        $post = $this->EmailSaveHomegrade($post);
        
        //echo $id_demande;
        //debugd($post);

        $post->updated_by = session('loggedUserId');
        // debugd($post);
        if(!isset($post->{$this->primaryKey})) :
            $post->created_by = session('loggedUserId');
            $data = database_encode($this->t_email, $post);
            if(!empty($data)) $this->db->table($this->t_email)->set($data)->insert();
            $id_email = $this->db->insertID();
            
            if($id_demande>0):
                $data_insert_lien["id_email"]=$id_email;
                $data_insert_lien["id_demande"]=$id_demande;
      
                $this->db->table("email_outlook_lien")->set($data_insert_lien)->insert();
            endif;
            
        else :
            $post->updated_at = date('Y-m-d H:i:s');
            $data = database_encode($this->t_email, $post);
            $this->db->table($this->t_email)->where($this->primaryKey, $post->{$this->primaryKey})->set($data)->update();
            $id_email = $post->{$this->primaryKey};
        endif;

        return $id_email;
    }

    private function EmailSaveHomegrade($post)
    {
       // debug($post);
        $post->created_datetime = date('Y-m-d H:i:s');
        $post->send_datetime = $post->created_at;
        $post->last_modified_datetime = date('Y-m-d H:i:s');
        $post->body_content = nl2br($post->message);
        if(isset($post->signature))
        {
            $post->body_content = $post->message." ".$post->signature;
        }
        $post->sender_mail = $post->senderEmail;
        $post->sender_name = $post->senderName;
        $post->to_mail = !empty($post->tos) ? implode(', ', $post->tos) : null;
        $post->cc_mail = !empty($post->css) ? implode(', ', $post->ccs) : null;
        $post->bcc_mail = !empty($post->ccis) ? implode(', ', $post->ccis) : null;

        return $post;
    }
}
