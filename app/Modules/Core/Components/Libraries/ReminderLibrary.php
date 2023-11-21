<?php

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;

class ReminderLibrary extends BaseLibrary 
{
    private function ReminderModelData($params)
    {
        $q = $this->db->table($this->t_reminder);
    	foreach ($params as $key => $value):
    		$q->where($key, $value);
    	endforeach;

        return $q;
    }

    //traitement de token pour connecter un utilisateur
    public function AccessGetByParams($params)
    {
        $reminder = $this->ReminderModelData($params)->get()->getRow();

    	if(!empty($reminder)) :
            if($reminder->duree_token == 0) :
                $this->RedirectByUser($params->id_user, $reminder->link_url);
				$this->ReminderModelData($params)->delete();
            else :
                $date_j = date('Y-m-d H:i:s');
                if(
                    is_numeric($reminder->duree_token) && $reminder->duree_token>0 && 
                    ($reminder->duree_token >= (((strtotime($date_j) - strtotime($reminder->date_created))/60)/60))
                ) :
			 		return $this->RedirectByUser($params->id_user, $reminder->link_url);
			 	else :
			 		die('Le token renseigné est incorrect ou expiré.');
                endif;
			endif;
    	else :
    		die('Le token renseigné est incorrect ou expiré.');
        endif;
    }

    private function RedirectByUser($id_user, $url)
    {
        if(empty(session('loggedUserId')) || session('loggedUserId')!=$id_user) :
            return redirect()->to(base_url("identification/login?redirect=$url"));
        else :
            return redirect()->to(base_url($url));
        endif;
    }

    public function TokenSet($length)
    {
    	$alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }  
        
    public function TokenInsert($param)
    {
        $post = (object) [];
        $post->token_reminder = $param->token;
        $post->date_created = date('Y-m-d H:i:s');
        $post->duree_token = $param->duree_token;
        $post->link_url = $param->url;
        $post->id_user = $param->id_user;

        if(is_null($post->id_user))
        {
            $post->id_user =0;
        }

        $this->db->table($this->t_reminder)->set(database_encode($this->t_reminder, $post))->insert();
        $id_reminder = $this->db->InsertID();

        return $id_reminder;
    }

//     //function d'envoi demail
//     private function send_email($to, $subject, $content){

//     	/* to devient le mail de celui connecté */
// //    	$id_session = $this->CI->session->userdata('id');
// //    	$user_session_data = $this->CI->db->select('*')->where('id_user', $id_session)->get('users')->result();
// //    	$to = $user_session_data[0]->mail;
//     	/* FIN  */

//     	$from_name = $this->from_name;
// 		$from_mail = $this->from_mail;
		
//     	$this->CI->load->library('email');

//     	$this->CI->email->from($from_mail, $from_name);
// 		$this->CI->email->set_mailtype("html");
// 		$this->CI->email->to($to);
		
// 		$this->CI->email->subject($subject);
// 		$this->CI->email->message($content);

// 		if($this->CI->email->send()){
// 			return true;
// 		}else{
// 			return false;
// 		}
//     } 
}

?>