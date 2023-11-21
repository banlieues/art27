<?php
	
	namespace Outlook\Libraries; 

	use Outlook\Models\OutlookModel;
    use Outlook\Libraries\Tr_outlook;
    use Components\Models\BanCrudModel;

	class Myoutlook_lib{
		
		var $from_mail = NULL;
		var	$from_name = 'Homegrade';
		var $user_data=NULL;

		var $autorisation_tamo=[2,117,24,137,112];

		public function __construct(){

					$this->from_mail=CRMAIL;
					
					$this->outlookModel=new OutlookModel;
				//debug($_SESSION);

                    $this->ban_crud_model=new BanCrudModel();
                    $this->tr_outlook=new Tr_outlook();

                    $this->user_data = $this->ban_crud_model->read_data('user_accounts','email', 'id='.session("loggedUserId"));

		}
		
		public function get_message($params=array()){
                    if(isset($params['id_message']) ):
                        $this->db->from('email_outlook');
                        $this->db->select('*');
                        $this->db->where('id_primary = '.$params['id_message']);
                        return $this->db->get()->result();
                    else : 
                        return array();
                    endif;
		}

        public function refresh_nonlus($id_user,$is_count=FALSE)
        {
            $db = db_connect();
            $builder=$db->table('demande');

            $builder->join("user_accounts","user_accounts.id=user_accounts.id_user_back_up","left");
            $builder->join("email_outlook_lien","email_outlook_lien.id_demande=demande.id_demande","left");
            $builder->join("email_outlook","email_outlook.id_primary=email_outlook_lien.id_email","left");

            $builder->groupStart();

                $builder->where('email_outlook.sender_mail IS NOT NULL');
                $builder->where('email_outlook.deleted',0); 
                $builder->where('email_outlook.draft', 0);
                
                $builder->where(
					"(
						(
							email_outlook.created_datetime >= '2021-12-06 0000-00-00' 
							AND 
							(
								(email_outlook.lus IS NULL OR email_outlook.lus='')
								OR
								(( email_outlook.lus NOT LIKE '$id_user,')
								AND
								( email_outlook.lus NOT LIKE '$id_user,%')
								AND
								( email_outlook.lus NOT LIKE '%,$id_user,%'))
							)
						)

						OR
						(
							email_outlook.created_datetime <= '2021-12-06 0000-00-00' 
							AND 
							(email_outlook.lus IS NULL OR email_outlook.lus='')

						)
					
						
					
					)"
				
				);

            $builder->groupEnd();


            $builder->groupStart();

                $builder->orWhere("demande.id_utilisateur",$id_user);
                $builder->orWhere("demande.id_utilisateur_2",$id_user);
                $builder->orWhere("user_accounts.id_user_back_up",$id_user);

            $builder->groupEnd();

           

            $builder->groupBy("demande.id_demande");

            $builder->orderBy('email_outlook.created_datetime DESC');


            if($is_count)
            {
                return $builder->countAllResults();
            }
            else
            {
                return $builder->get(30)->getResult();
            }

            /*Select *

                    FROM
                        demande

                    WHERE
                        ‘id_utilisateur='.$id.' OR id_utilisateur_2 ='.$id OR user_accounts.id_user_back_up=$id 


                    LEFT JOIN user_accounts ON user_accounts.id_utilisateur=user_accounts.id_user_back_up 
                    LEFT JOIN email_outlook_lien ON email_outlook_lien.id_demande=demande.id_demande 
                    LEFT JOIN email_outlook ON email_outlook.id_primary= email_outlook_lien.id_email 

                        



                    GROUP BY demande.id_demande*/

        }

		public function get_ids_mydemande($id){
                    //return ids demande dont je suis en charge ou suiveur
                    $liste_id=array();
                    $db = db_connect();
                    $builder=$db->table('demande');

                    $builder->select('demande.id_demande as ids_demande');
                    
                    //$this->db->where('id_utilisateur='.$id.' OR id_utilisateur_2 ='.$id);
                    
                    $where_back_up=('id_utilisateur='.$id.' OR id_utilisateur_2 ='.$id);
                    $where_back_up.=" OR (id_utilisateur IN (SELECT user_accounts.id_user_back_up FROM user_accounts WHERE user_accounts.id_user_back_up=$id) AND (id_utilisateur_2 IS NOT NULL OR id_utilisateur_2 <>0 OR id_utilisateur_2 <>'') )";
                    
                    $builder->where($where_back_up);
                    
                    $builder->groupBy('demande.id_demande');
                    $data = $builder->get()->getResult();
                    if(isset($data[0]->ids_demande)):
                        foreach($data as $dt):
                            array_push($liste_id,$dt->ids_demande);
                        endforeach;
                    endif;
                    return $liste_id;
//			if(!empty($data)):
//				return explode(',', remove_final_virgule($data[0]->ids_demande));
//			else : 
//				return array();
//			endif;
		}

        public function get_requete_ids_demande($id)
        {
            return "
                SELECT demande.id_demande as ids_demande FROM demande
                WHERE 
                    id_utilisateur = $id 
                    OR id_utilisateur_2 = $id
                    OR (
                        id_utilisateur IN (
                            SELECT user_accounts.id
                            FROM user_accounts 
                            WHERE user_accounts.id_user_back_up = $id
                        )
                    )
                GROUP BY demande.id_demande
            ";
        }
		
		public function get_messages($params=array())
		{
            
			//print_r($params);
                    
			if(!isset($params['limit'])):
				$params['limit']=30;
			endif;
			
			if(!isset($params['sort'])):
				$params['sort']=0;
			endif;

            
                        
                        if(isset($params['count']) && $params['count'] == TRUE):
                            $params['limit']=NULL;
                            
                        endif;
            $db = db_connect();
			$builder=$db->table('email_outlook');
			$builder->select('*');
			$builder->orderBy('email_outlook.created_datetime DESC');
                        if(!is_null($params['limit'])):
			                $builder->limit($params['limit'], $params['sort']);
			            endif;
			//afficher seulement ceux qui ne sont pas supprimé
			$builder->where('email_outlook.deleted = 0'); 
			
			if(!isset($params['courriel']) || $params['courriel']!=TRUE):	
				//seulement ceux qui ont un expéditeur
				$builder->where('email_outlook.sender_mail IS NOT NULL');
				//éliminé ceux envoyé par homegrade
				//$builder->where('email_outlook.sender_mail NOT LIKE "%'.CRMAIL.'%"');
			endif;


			if(isset($params['brouillons']) && $params['brouillons']==TRUE){
				$builder->where('email_outlook.draft', 1);	
			}else{
				$builder->where('email_outlook.draft', 0);	
			}

			if(isset($params['mesmessages']) && $params['mesmessages']==TRUE)
            {
				
				$mail_session = session("loggedUserMail");

				$wheres_mesmessages = "";
				$wheres_mesmessages .= '((email_outlook.sender_mail LIKE "%'.$mail_session.'%") OR  (email_outlook.to_mail LIKE "%'.$mail_session.'%" ))';

				//+ tous les messages de mes demandes
				/*$ids_demande_session_string = implode(',', $params['mesdemandes']);
				if(!empty($ids_demande_session_string)):
					$wheres_mesmessages .= ' OR ';
					$wheres_mesmessages .= ' 
						(
							EXISTS (
								SELECT email_outlook_lien.id_email, email_outlook_lien.id_demande 
								FROM email_outlook_lien
								WHERE email_outlook.id_primary = email_outlook_lien.id_email 
								AND email_outlook_lien.id_demande IN  ('.$ids_demande_session_string.') 
                                
							)
							
						)';
				endif;*/

				if(!empty($wheres_mesmessages)):
					$wheres_mesmessages = "(".$wheres_mesmessages.")";
				endif;

				$builder->where($wheres_mesmessages);
				
			}


			if(isset($params['origin'])){
				$builder->where('email_outlook.origin_mail', $params['origin']);
			}

			if(isset($params['nolus']) && $params['nolus']==TRUE){
				//Non lus par personne
				//$id_session = $this->session->userdata('id');
				//$builder->where("email_outlook.lus NOT LIKE '%".$id_session.",%'");
				$id_user_encours=session()->get("loggedUserId");
				//Non lus = FALSE dès quelqu'un a vu le message
                $builder->where("email_outlook.isEmailAuto IS NULL");
				$builder->where("(
                    (
                        email_outlook.created_datetime >= '2021-12-06 0000-00-00' 
                        AND (
                            (
                                email_outlook.lus IS NULL 
                                OR email_outlook.lus=''
                            )
                            OR (
                                email_outlook.lus NOT LIKE '$id_user_encours,'
                                AND email_outlook.lus NOT LIKE '$id_user_encours,%'
                                AND email_outlook.lus NOT LIKE '%,$id_user_encours,%'
                            )
                        )
                    )
                    OR (
                        email_outlook.created_datetime <= '2021-12-06 0000-00-00' 
                        AND (
                            email_outlook.lus IS NULL
                            OR email_outlook.lus=''
                        )

                    )					
                )");

			}
			
			//afficher ceux qui ne sont pas traités
			if(isset($params['no_tr']) && $params['no_tr']==TRUE):
				$builder->where(
					'
						!EXISTS (
							SELECT id_email 
							FROM email_outlook_lien
							WHERE email_outlook.id_primary = email_outlook_lien.id_email
						)
					'
				);
			endif;
			
			//si id_demande existe
			 if(isset($params['id_demande']) && !is_array($params['id_demande']) && $params['id_demande']>0):
				$builder->where(
					'
						EXISTS (
							SELECT email_outlook_lien.id_email, email_outlook_lien.id_demande 
							FROM email_outlook_lien
							WHERE email_outlook.id_primary = email_outlook_lien.id_email 
							AND email_outlook_lien.id_demande = '.$params["id_demande"].'
						)
						
					'
				);
			endif;

			if(isset($params['id_demande']) && is_array($params['id_demande']) && !empty($params['id_demande']) && !empty(implode(',', $params['id_demande']))):
				$ids_demande_string = implode(',', $params['id_demande']);
				$builder->where(
					'
						EXISTS (
							SELECT email_outlook_lien.id_email, email_outlook_lien.id_demande 
							FROM email_outlook_lien
							WHERE email_outlook.id_primary = email_outlook_lien.id_email 
							AND email_outlook_lien.id_demande IN  ('.$ids_demande_string.')
						)
						
					'
				);
			endif;

          

			if(isset($params['id_requete_demandes'])):
				$id_requete_demandes=$params['id_requete_demandes'];
				$builder->where(
					'
						EXISTS (
							SELECT email_outlook_lien.id_email, email_outlook_lien.id_demande 
							FROM email_outlook_lien
							WHERE email_outlook.id_primary = email_outlook_lien.id_email 
							AND email_outlook_lien.id_demande IN  ('.$id_requete_demandes.')
						)
						
					'
				);
			endif;

			/*if(isset($params['type']) && in_array($params['type'], array('1','2'))):
				$builder->where('email_outlook.type='.$params['type']);
			else : 
				$builder->where('email_outlook.type=1');
			endif;*/

			if(isset($params['is_homegrade'])):
				if($params['is_homegrade']):
					$builder->where('email_outlook.is_homegrade=1');
				else : 
					$builder->where('email_outlook.is_homegrade=0');
				endif;
			endif;
			


			if(isset($params['count']) && $params['count'] == TRUE):
				return $builder->countAllResults();
			else :
				$results=$builder->get()->getResult();
				//echo $builder->last_query();
				return $results;
			endif;
		}
		
		public function get_pagination($params){
			$config = array();

			if(!isset($params['view'])):
				$params['view'] = "";
			endif;
			
			$config['cur_page'] = $params['page']; //numero de la page
			$config['use_page_numbers'] = TRUE;
			
			$config['anchor_class'] = 'follow_link';
			$config["base_url"] = $params['base_url'];
			$config["total_rows"] = $params['total_rows'];
			$config["per_page"] = $params['per_page'];

			$config['full_tag_open'] = '<div style="margin:0 !important;"><ul style="margin:0 !important" class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';

			$config['first_link'] = '&laquo; Début';
			$config['first_tag_open'] = '<li class="prev page user_pagination_outlook" view="'.$params['view'].'" container="'.$params['container'].'">';
			$config['first_tag_close'] = '</li>';

			$config['last_link'] = 'Fin &raquo;';
			$config['last_tag_open'] = '<li class="next page user_pagination_outlook" view="'.$params['view'].'" container="'.$params['container'].'">';
			$config['last_tag_close'] = '</li>';

			$config['next_link'] = 'Suivant &rarr;';
			$config['next_tag_open'] = '<li class="next page user_pagination_outlook" view="'.$params['view'].'" container="'.$params['container'].'">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = '&larr; Précédent';
			$config['prev_tag_open'] = '<li class="prev page user_pagination_outlook" view="'.$params['view'].'" container="'.$params['container'].'">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="active"><a>';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li  class="page user_pagination_outlook" view="'.$params['view'].'" container="'.$params['container'].'">';
			$config['num_tag_close'] = '</li>';

			if(isset($params['data_page_attr'])){
				$config['data_page_attr'] = $params['data_page_attr'];
			}	

			$config['first_url'] = $params['base_url'].'/1'; 	

		
			$this->pagination->initialize($config);
			return $this->pagination->create_links();
		}
		
        public function mail_assign_notification($params, $isTest='noTest')
        {
            //lorsque on change la personnée chargée et/ou la personne suiveur
            if(empty($params['id_demande']) || empty($params['id_charger'])) return false;
    
            $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
            $demande = $MailingLibrary->DemandeGet($params['id_demande']);
            if(!empty($demande->assign_id) && !in_array($demande->assign_id, [session('loggedUserId'), 25])) :
                $post = (object) [];
                $post->id_demande = $params['id_demande'];
                $result = $MailingLibrary->EmailSendByTemplate('assign', $post, $isTest);
                return  $result;
            endif;
        }
		
        private function is_first_email_by_demande($id_demande, $id_message)
        {
            //s'agit il du premier mail pour cette demande
            $this->db->select('id');
            $this->db->from('email_outlook_lien');
            $this->db->where('id_demande', $id_demande);
            $this->db->where_not_in('id_email', $id_message);
            $mails = $this->db->get()->result();

            if(!empty($mails)) return false;
            else return true;
        }
				
        public function mails_notification($params, $isTest='noTest')
        {
            //cette fonction s'execute à chaque ajout d'un email à une demande
            $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
            $demande = $MailingLibrary->DemandeGet($params['id_demande']);
            $mails_by_demande = $MailingLibrary->EmailsGetByDemandeOutMessage($params['id_demande'], $params['id_message']);
    
            $post = (object) [];
            $post->id_demande = $params['id_demande'];
            $datas = [];
            if(empty($mails_by_demande)) :
                $datas[] = $MailingLibrary->EmailSendByTemplate('confirm', $post, $isTest);
                if($demande->assign_mail != CRMAIL) :
                    $datas[] = $MailingLibrary->EmailSendByTemplate('update_assign', $post, $isTest);
                endif;
            else :
                if($demande->assign_mail != CRMAIL) :
                    $datas[] = $MailingLibrary->EmailSendByTemplate('update_assign', $post, $isTest);
                endif;
                if($demande->suiveur_mail != CRMAIL || $demande->suiveur_mail_default != CRMAIL) :
                    $datas[] = $MailingLibrary->EmailSendByTemplate('update_suiveur', $post, $isTest);
                endif;
            endif;
    
            $result = [];
            foreach($datas as $data) $result = array_merge($result, $data);
    
            return $result;
        }
				
        public function demande_assign_notification($id_demande, $isTest='noTest')
        {
            //cette fonction s'execute à chaque modification du user en charge
            $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
            $demande = $MailingLibrary->DemandeGet($id_demande);

            $post = (object) [];
            $post->id_demande = $id_demande;
            if(!in_array($demande->assign_mail, [CRMAIL, sessionUser()->email])) :
                $MailingLibrary->EmailSendByTemplate('assign', $post, $isTest);
            endif;
        }
				
        public function demande_update_notification($id_demande, $isTest='noTest')
        {
            //cette fonction s'execute à chaque modification d'une demande
            $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
            $demande = $MailingLibrary->DemandeGet($id_demande);

            $post = (object) [];
            $post->id_demande = $id_demande;

            if(!in_array($demande->assign_mail, [CRMAIL, sessionUser()->email])) :
                $datas[] = $MailingLibrary->EmailSendByTemplate('update_assign', $post, $isTest);
            endif;
            if((!empty($demande->suiveur_mail) && !in_array($demande->suiveur_mail, [CRMAIL, sessionUser()->email])) || (!empty($demande->suiveur_mail_default) && !in_array($demande->suiveur_mail_default, [CRMAIL, sessionUser()->email]))) :
                $datas[] = $MailingLibrary->EmailSendByTemplate('update_suiveur', $post, $isTest);
            endif;
        }

        public function mails_notification_new($params, $isTest='noTest')
        {
            //debugd($params);
            $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
            $demande = $MailingLibrary->DemandeGet($params['id_demande']);
    
            $post = (object) [];
            $post->id_demande = $params['id_demande'];
    
            $datas = [];
            $datas[] = $MailingLibrary->EmailSendByTemplate('confirm', $post, $isTest);
      
            if(!empty($demande->assign_id) && !in_array($demande->assign_id, [session('loggedUserId'), 25])) :
                $datas[] = $MailingLibrary->EmailSendByTemplate('assign', $post, $isTest);
            endif;
    
            $result = [];
            foreach($datas as $data) $result = array_merge($result, $data);
    
            return $result;
        }
		
		public function send($to, $subject, $content, $cc=NULL, $cci=NULL,$ce_qui_est_attachment=NULL){

          
                    $email_service = \Config\Services::email();

                    $from_mail = $this->from_mail;

                    $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];

                    if(!in_array(base_url(),$url_permise))
                    {
                        $from_name = "Homegrade en Mode Test";
                    }
                    else
                    {
                        $from_name = "Homegrade";
                    }

                    if(!empty($ce_qui_est_attachment))
                    {

                       // debug($ce_qui_est_attachment);
                        foreach($ce_qui_est_attachment as $attachment)
                        {
                           // debug($attachment);
                            $email_service->attach($attachment);
                        }
                    }

                    
                    $email_service->setFrom($from_mail, $from_name);
                    $email_service->setMailtype("html");
			
                    $tos = explode(',', $to);
                    $to = "";
                    foreach ($tos as $value) {
                        if(filter_var(trim($value), FILTER_VALIDATE_EMAIL)):
                                if(!empty($to)): $to .=","; endif;
                                $to.= trim($value);
                        endif;
                    }
			
                  
			/* to devient le mail de celui connecté */
//	    	$id_session = $this->session->userdata('id');
//	    	$user_session_data = $this->db->select('*')->where('id_user', $id_session)->get('users')->result();
//	    	$to = $user_session_data[0]->mail;
	    	/* FIN  */

            $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];

            if(!in_array(base_url(),$url_permise))
            {
                $to=session()->get("loggedUserMail");
            }
          
                    $email_service->setTo($to);

                    

                    //cc
                    if(!is_null($cc) && !empty($cc)){
                            $ccs = explode(',', $cc);
                            $cc = "";

                            foreach ($ccs as $value) {
                                    if(filter_var(trim($value), FILTER_VALIDATE_EMAIL)):
                                            if(!empty($cc)): $cc .=","; endif;
                                            $cc.= trim($value);
                                    endif;
                            }
                            $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
                            if(!in_array(base_url(),$url_permise))
                                {
                                    $cc=session()->get("loggedUserMail");
                                }
                            $email_service->setCC($cc); 
                    }

                    //cci
                    if(!is_null($cci) && !empty($cci)){
                            $ccis = explode(',', $cci);
                            $cci = "";

                            foreach ($ccis as $value) {
                                    if(filter_var(trim($value), FILTER_VALIDATE_EMAIL)):
                                            if(!empty($cci)): $cci .=","; endif;
                                            $cci.= trim($value);
                                    endif;
                            }
                            $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
                            if(!in_array(base_url(),$url_permise))
                                {
                                    $cci=session()->get("loggedUserMail");
                                }
                            $email_service->setBCC($cci);
                    }

                    $email_service->setSubject($subject);
                    $email_service->setMessage($content);



                    if($email_service->send()){
                            return true;
                    }else{
                            return false;
                    }

		}


  
		
		
	public function save_import_mail($mails=array(), $email='', $controlle=FALSE, $count=FALSE){
               $db=db_connect();
                if($controlle){
                    $mails_tmp = array();

                    foreach ($mails as $message) {
                    
                        //Si on trouve [CASE: dans l'objet, il concerne le crm
                        /*if(stristr($message->subject, '[CASE')){
                                array_push($mails_tmp, $message);
                        }*/
                        //si il a une référence
                        if(is_reference_outlook($message->subject)):
                            //si la référence n'est pas 
                            if(!empty(get_string_between($message->subject, '#', '#'))){
                                    array_push($mails_tmp, $message);
                            }
                        endif;
                    }

                    $mails = $mails_tmp;
                    $controlle = FALSE;
                }
               
               if(!$controlle){
                    $comp_import = 0;
                    $messages_lier = 0;
                    foreach ($mails as $message) {

                        $to_mails = array();
                        //parcourir les mails destinateurs
                        foreach($message->toRecipients as $to){
                            array_push($to_mails,$to->emailAddress->address);
                        }
                        //datas du message encours
                        $data = array(
                            //'odata_etag'=> $message->@odata.etag,
                            'id'=> trim($message->id),
                            'created_datetime'=> adjust_gmt($message->createdDateTime),
                            'last_modified_datetime'=> adjust_gmt($message->lastModifiedDateTime),
                            'change_key'=> $message->changeKey,
                            'received_datetime'=> adjust_gmt($message->receivedDateTime),
                            'send_datetime'=> adjust_gmt($message->sentDateTime),
                            'internet_message_id'=> $message->internetMessageId,
                            'subject'=> $message->subject,
                            'body_preview'=> $message->bodyPreview,
                            'importance'=> $message->importance,
                            'parent_folder_id'=> $message->parentFolderId,
                            'conversation_id'=> $message->conversationId,
                            'body_type'=> $message->body->contentType,
                            'body_content'=> $message->body->content, 
                            'sender_mail'=> $message->from->emailAddress->address,
                            'send_name'=> $message->from->emailAddress->name,
                            'to_mail'=>implode(',', $to_mails),
                            'origin_mail'=>$email
                        );

                        //Vérifie si id message du message encours existe dans la BD 
                        $builder=$db->table("email_outlook");
                        $message_isId = $builder->select('*')->where('internet_message_id', $data['internet_message_id'])->get()->getResult();
                        
					  //debug($message_isId);

                      //on recuper id demande
                      $objet = $message->subject;
                      /*$remove_point=str_ireplace("[CASE:","[CASE",$objet);
                      $marqueurDebutLien = "[CASE";
                      $debutLien = stripos( $remove_point, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
                      $marqueurFinLien = "]";
                      $finLien = stripos( $remove_point, $marqueurFinLien );
                      $id_demande = substr( $remove_point, $debutLien, $finLien - $debutLien );
                      $id_demande = trim($id_demande);*/
                      $id_demande = findCase(get_string_between($objet, '#', '#'));
					
					  //Inserer le message encours que si ID n'est pas dans la bd et que ce n'est pas un mail du système
                        if(empty($message_isId)&&$data['sender_mail']!=CRMAIL):
                            $builder=$db->table("email_outlook");
                            if($builder->insert($data)):
                                $insert_id = $db->insertID();
                                $comp_import++; //Compteur de message importé

								//on regarde s'il y des documents au coeur



								

                                //Si il existe des pièces jointes
                                //if($message->hasAttachments==1):
									$path=PATH_DOCU_DEMANDE;
                                    $response = $this->tr_outlook->get_attachments($data['id'], $email);
                            	
									$attachments = $response;
                                    //debug($attachments);
                                    if(isset($attachments->value)):
											foreach ($attachments->value as $attach) 
											{
												if(isset($attach->contentType)&&!empty($attach->contentType&&isset($attach->contentBytes)&&!empty($attach->contentBytes)))
												{
														
														$name=$insert_id."_".slugify_name_file($attach->name);
															//echo '<pre>'; echo base64_encode($attach->contentBytes);  echo '</pre>';
														
															file_put_contents($path.$name, base64_decode($attach->contentBytes), LOCK_EX);
															
															$data_attach = array(
																	"name"=> slugify_name_file($attach->name) ,
																	"url_file"=>$name,

																	//"contentByte"=> $attach->contentBytes,
																	//"contentByte_Type"=>$attach->contentType,
																	// "id_message"=> $insert_id
															);
												  
												  //debug($data_attach,TRUE); 243188_watchmen.jpg
                                                            $builder=$db->table("document_upload");
															$builder->insert($data_attach);
                                                            $id_document=$db->insertID();

                                                            if(isset($id_demande)&&$id_demande>0)
                                                            {
                                                                $data_upload_lien["id_message"]=$insert_id;
                                                                $data_upload_lien["id_demande"]=$id_demande;
                                                                $data_upload_lien["id_document"]=$id_document;

                                                                $builder=$db->table("document_upload_lien");
                                                                $builder->insert($data_upload_lien);
                                                            }
                                                            else
                                                            {
                                                                $data_upload_lien["id_message"]=$insert_id;
                                                                $data_upload_lien["id_demande"]=0;
                                                                $data_upload_lien["id_document"]=$id_document;

                                                                $builder=$db->table("document_upload_lien");
                                                                $builder->insert($data_upload_lien);
                                                            }

															//traitement pour les images dans le corps directmeent et non attachés

															$ci_id=$attach->contentId;
												  
															$data_body_content["body_content"]=str_replace("cid:$ci_id",base_url().$path.$name,$message->body->content);
															$where_body_content["id_primary"]=$insert_id;
                                                         
                            
                                                            $builder=$db->table("email_outlook");
															$builder->update($data_body_content,$where_body_content);
															$message->body->content=$data_body_content["body_content"];



												}
											}
                                    endif;
										
                               // endif;
				
                             
                             
                                
                                //id demande n'est pas vide et supérieur à 0
                                if(!empty($id_demande) && $id_demande>0):
                                    $builder=$db->table("demande");
                                    $demande = $builder->select('*')->where('id_demande', $id_demande)->get()->getResult();
                                    //print_r($id_demande); print_r($demande); die();
                                    //si on trouve une demande avec la référence, créer la liaison 
                                    if(!empty($demande)&&$id_demande>0):
                                        //insertion de laison reussi
                                        $builder=$db->table("email_outlook_lien");
                                        if($builder->insert(array('id_demande'=>$id_demande,'id_email'=>$insert_id))):
                                            $messages_lier+=1;	//message lié authomatiquement


                                            //Il faut voir maintenant si le consiller qui gère la demande est en situation de messagerie automatique
                                             $this->send_messagerie_automatique($id_demande,$data["sender_mail"],$data["subject"]);
                                            
                                        
                                            $params_outlook["id_demande"]=$id_demande;
                                            $params_outlook["id_message"]=$insert_id;
                                            //notification de mise à jour

                                            $this->mails_notification($params_outlook);
                                        endif;		
                                    endif;
							 
                                endif;
                            endif;
                        endif;

                        //suppression message
                        //$this->tr_outlook->delete_message(trim($message->id), $email);
                        
                        //SI ce n'est pas un message envoyé par le système
                        if($data['sender_mail']!=CRMAIL):
                            //Deplacer dans elements suprimés
                            $this->tr_outlook->move_message($data['id'], $email, 'deleteditems');
                        endif;
					
                    }
                    
                    if($count):
                        return array('importe'=>$comp_import, 'lier'=>$messages_lier);	      		
                    endif;

                }
		 
            }	
            
            
         public function send_messagerie_automatique($id_demande,$sender_mail,$subjet)
         {
              //Si c'est le cas il faut envoyer message à la persone pour l'avertir
            //connaître le conseiller pour la demande
            $table="demande";
            $fields="user_accounts.id as id_user,user_accounts.message_automatique";
            $left=array();
            $left_condition["user_accounts"]="demande.id_utilisateur=user_accounts.id";
            array_push($left,$left_condition);

            $where="user_accounts.is_mail_automatique=1 AND user_accounts.is_mail_automatique IS NOT NULL AND NOW() BETWEEN  user_accounts.date_debut_automatique AND user_accounts.date_fin_automatique AND "
                    . "demande.id_demande=$id_demande";

            $messages_automatique=$this->ban_crud_model->read_data($table,$fields,$where,$left);
            //echo $this->db->last_query();

            if(isset($messages_automatique[0]->id_user)):
               //Je dois récupérer l'adresse du sender du message afin d'envoyer la réponse
               
                
                $sdenr_automatique=$sender_mail;
                
                $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
                if(!in_array(base_url(),$url_permise)):
                    $sdenr_automatique=session()->get("loggedUserEmail");
                endif;

                $subject_automatique=$subjet;
                $message_automatique=nl2br($messages_automatique[0]->message_automatique);

                $from_name = "";
                $from_mail = CRMAIL;



               

                        $subject="[Réponse automatique à votre message] ".$subject_automatique;
                        $content=$message_automatique;

                       



                        if($this->send($to=$sdenr_automatique, $subject, $content)){
                                $db = db_connect();
                                $params['no_send_email'] = TRUE;

                                    $data_insert = array(
                                                        'is_homegrade'=>1,
                                                            'created_datetime'=> date("Y-m-d H:i:s"),
                                                                'send_datetime'=> date("Y-m-d H:i:s"),
                                                                'subject'=>"[Réponse automatique à votre message] ".$subject_automatique,
                                                                'body_preview'=>$message_automatique,
                                                                'body_content'=>$message_automatique,
                                                                'sender_mail'=>$from_mail,
                                                                'send_name'=>$from_name,
                                                                'to_mail'=>$sdenr_automatique
                                                        );

                                $builder=$db->table("email_outlook");
                                $builder->insert($data_insert);
                                $id_message=$db->insertID();


                                $builder=$db->table("email_outlook_lien");

                                $data_insert_2["id_email"]=$id_message;
                                $data_insert_2["id_demande"]=$id_demande;
                                $data_insert_2["is_homegrade"]=1;
                                $builder->insert($data_insert_2);

                            

                        }else{

                        }

            endif;
                                            
             return true;
             
             
             
         }

        public function save_import_mail_hge($mails=array(), $email='', $controlle=FALSE, $count=FALSE){

                  
                    $this->load->add_package_path(APPPATH.'modules/evenement');
                    $this->load->model('inscr_model');
		
                if($controlle){
                    $mails_tmp = array();

                    foreach ($mails as $message) {
                        //Si on trouve [CASE: dans l'objet, il concerne le crm
                        /*if(stristr($message->subject, '[CASE')){
                                array_push($mails_tmp, $message);
                        }*/
                        //si il a une référence
                        if(is_reference_outlook($message->subject)):
                            //si la référence n'est pas 
                            if(!empty(get_string_between($message->subject, '#', '#'))){
                                    array_push($mails_tmp, $message);
                            }
                        endif;
                    }

                    $mails = $mails_tmp;
                    $controlle = FALSE;
                }

               if(!$controlle){
                    $comp_import = 0;
                    $messages_lier = 0;
                    foreach ($mails as $message) {

                        $to_mails = array();
                        //parcourir les mails destinateurs
                        foreach($message->toRecipients as $to){
                            array_push($to_mails,$to->emailAddress->address);
                        }
                        //datas du message encours
                        $data = array(
                            //'odata_etag'=> $message->@odata.etag,
                            'id'=> trim($message->id),
                            'created_datetime'=> adjust_gmt($message->createdDateTime),
                            'last_modified_datetime'=> adjust_gmt($message->lastModifiedDateTime),
                            'change_key'=> $message->changeKey,
                            'received_datetime'=> adjust_gmt($message->receivedDateTime),
                            'sendDatetime'=> adjust_gmt($message->sentDateTime),
                            'internet_message_id'=> $message->internetMessageId,
                            'subject'=> $message->subject,
                            'body_preview'=> $message->bodyPreview,
                            'importance'=> $message->importance,
                            'parent_folder_id'=> $message->parentFolderId,
                            'conversation_id'=> $message->conversationId,
                            'body_type'=> $message->body->contentType,
                            'message'=> $message->body->content, 
                            'sendFrom'=> $message->from->emailAddress->address,
                            'send_name'=> $message->from->emailAddress->name,
                            'sendTo'=>implode(',', $to_mails),
                            'origin_mail'=>$email
                        );
                        //Vérifie si id message du message encours existe dans la BD 
                        $message_isId = $this->db->select('*')->where('internet_message_id', $data['internet_message_id'])->get('hge_email')->result();
                        //Inserer le message encours que si ID n'est pas dans la bd et que ce n'est pas un mail du système
                        if(empty($message_isId)&&$data['sendFrom']!="contact.crm@homegrade.brussels"):
                            if($this->db->insert('hge_email', $data)):
                                $insert_id = $this->db->insert_id();
                                $comp_import++; //Compteur de message importé

                                //Si il existe des pièces jointes
                                if($message->hasAttachments==1):
                                    $response = $this->tr_outlook->get_attachments($data['id'], $email);
                                        $attachments = $response;
                                        if(isset($attachments->value)):
                                        foreach ($attachments->value as $attach) {
                                                //echo '<pre>'; echo base64_encode($attach->contentBytes);  echo '</pre>';
                                                $data_attach = array(
                                                        "name"=> $attach->name ,
                                                        "contentByte"=> $attach->contentBytes,
                                                        "contentByte_Type"=>$attach->contentType,
                                                        "id_message"=> $insert_id
                                                );
                                                $this->db->insert('hge_email_demande_depots', $data_attach);
                                        }
                                        endif;
                                endif;
				
                                $objet = $message->subject;
                                /*$remove_point=str_ireplace("[CASE:","[CASE",$objet);
                                $marqueurDebutLien = "[CASE";
                                $debutLien = stripos( $remove_point, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
                                $marqueurFinLien = "]";
                                $finLien = stripos( $remove_point, $marqueurFinLien );
                                $id_hge_inscr = substr( $remove_point, $debutLien, $finLien - $debutLien );
                                $id_hge_inscr = trim($id_hge_inscr);*/
                                $id_hge_inscr = findCase(get_string_between($objet, '#', '#'));
                                //echo "<li>".print_r($id_hge_inscr);
                              
                                //id_hge_inscr n'est pas vide et supérieur à 0
                                if(isset($id_hge_inscr)&&!empty($id_hge_inscr) && $id_hge_inscr>0):
                                    $inscr = $this->inscr_model->read_by_id_hge_inscr($id_hge_inscr);

//                                    print_r($id_hge_inscr); print_r($inscr); print_r($this->db->last_query()); die();
                                    //si on trouve une demande avec la référence, créer la liaison 
                                    if(!empty($inscr)&&$id_hge_inscr>0):
                                        //update de laison reussie
                                        $data['id_hge_inscr'] = $id_hge_inscr;
                                        $data['id_hge_event'] = $inscr['id_hge_event'];
                                        $this->db->where('id_hge_email', $insert_id);
                                        if($this->db->update('hge_email', $data)):
                                            $messages_lier+=1;	//message lié authomatiquement

                                            $params_outlook["id_demande"]=$id_hge_inscr;
                                            $params_outlook["id_message"]=$insert_id;
                                            //notification de mise à jour
                                           // $this->mails_notification($params_outlook);
                                        endif;		
                                    endif;
							 
                                endif;
                            endif;
                        endif;

                        //suppression message
                        //$this->tr_outlook->delete_message(trim($message->id), $email);
                        
                        //SI ce n'est pas un message envoyé par le système
                      
					
                    }
                    
                    if($count):
                        return array('importe'=>$comp_import, 'lier'=>$messages_lier);	      		
                    endif;

                }
		 
            }
            
            public function update_calendar_outlook($id_rdv){
             
                $fields='rdv.titre as titre,'
                        . 'rdv.date_rdv_debut as date_debut,'
                        . 'rdv.date_rdv_fin as date_fin,'
                        . 'rdv.note as note,'
                        . '(SELECT id_demande FROM demande_rdv WHERE demande_rdv.id_rdv=rdv.id_rdv) as id_demande,'
                        . 'rdv.lieu as lieu,'
                        . 'rdv.id_user_rdv as id_user_rdv,'
                        . 'rdv.id_user_rdv as id_user,'
                        . 'rdv.id_statut_rdv as id_statut,'
                        . 'liste_rdv_statut.label as statut,'
                        . 'liste_rdv_type.label as type,'
                        . 'rdv.id_rdv_outlook as id_outlook,'
                        . 'CONCAT(user_accounts.prenom," ",user_accounts.nom) as organize_name,'
                        . 'user_accounts.email as organize_mail';
                
                $left=array();
                $left_condition['user_accounts']='user_accounts.id=rdv.id_user_create';
                $left_condition['liste_rdv_statut']='liste_rdv_statut.id=rdv.id_statut_rdv';
                $left_condition['liste_rdv_type']='liste_rdv_type.id=rdv.id_type_rdv';
                
                array_push($left, $left_condition);
                $rdvA=$this->ban_crud_model->read_data('rdv',$fields, 'rdv.id_rdv='.$id_rdv, $left);
               
                $fields="contact.nom_contact as nom,"
                        . "contact.prenom_contact as prenom,"
                        . "contact_profil.telephone as telephone";
                $left=array();
                $left_condition=array();
                $left_condition['personne_bien']='personne_bien.id_demande=demande_rdv.id_demande';
                $left_condition['contact']='contact.id_contact=personne_bien.id_contact';
                $left_condition['contact_profil']='contact_profil.id_contact=contact.id_contact';
                 array_push($left, $left_condition);

                $personnes=$this->ban_crud_model->read_data('demande_rdv',$fields, 'demande_rdv.id_rdv='.$id_rdv, $left);

               // debugd($rdvA[0]);
                if(!empty($rdvA)&&strstr($rdvA[0]->organize_mail, 'homegrade.brussels')): 
                    $rdv=$rdvA[0];
                    /* START NOTE */
                    $note=$rdv->note;

                    if(!empty($note)):
                        $note.='<br>---------------<br>';
                    endif;

                    if(!empty($rdv->id_demande)):
                         $note.='Numéro de la demande : '.$rdv->id_demande.'<br>';
                    endif;


                    if(!empty($rdv->type)): 
                        $note.='Type du RDV : '.$rdv->type.'<br>';
                    endif;

                    if(!empty($rdv->statut)):
                         $note.='Statut du rdv : '.$rdv->statut.'<br>';
                    endif;

                    if(!empty($rdv->lieu)): 
                        $note.='Lieu : '.$rdv->lieu.'<br>';
                    endif;

                   

                    if(!empty($personnes)):
                        $note.='Contact(s) : <br>';
                        foreach($personnes as $personne):
                            $note.=' - '.$personne->prenom.' '.$personne->nom.' '.$personne->telephone.'<br>';
                        endforeach;
                    endif;
                    /* END NOTE */
                 
                    $data['event']=array(
                       'Subject'=>$rdv->titre,
                       'Body'=>array(
                           'ContentType'=>"HTML",
                           'Content'=>$note
                        ),
                       "start"=>array(
                            "dateTime"=> $rdv->date_debut,
                            "timeZone"=> "W. Europe Standard Time"
                        ),
                        "end"=>array(
                            "dateTime"=> $rdv->date_fin,
                            "timeZone"=> "W. Europe Standard Time"
                        )
                   );
                   //participants
              
                   if(!empty($rdv->id_user_rdv)):
                       $participants=  explode(',', $rdv->id_user_rdv);
                       $attendees=array();
                       foreach($participants as $participant):
                           $user=$this->ban_crud_model->read_data('user_accounts', 'email','user_accounts.id='.$participant);
                           if(isset($user[0])):
                               $user=$user[0];
                               $temp=array(
                                   'EmailAddress'=>array(
                                        'Address'=>$user->email,
                                   )
                               );
                               array_push($attendees, $temp);
                           endif;
                       endforeach;
                       if(!empty($attendees)): 
                           
                            $data['event']['Attendees']=$attendees; 
                       endif;
                   endif;
                    
                   $data['json']=json_encode($data['event']);
                   $data['email']=$rdv->organize_mail;
                   //print_r($data['event']); die();
                   if(empty($rdv->id_outlook)):
                        //ajout 
                        $response =$this->tr_outlook->update_event_calendar($data);
                        //echo '<pre>';
                       //print_r($response); die();
                        //add id_rdv_outlook dans la base de données 
                        if($response):
                            $id_outlook=$response->id;
                            $this->outlookModel->update_data('rdv',array('id_rdv_outlook'=>$id_outlook),'rdv.id_rdv='.$id_rdv);
                        endif;
                   else : 
                       //update
                       $data['id_rdv_outlook']=$rdv->id_outlook;
                      $response =$this->tr_outlook->update_event_calendar($data, 'update');
                   endif;
                   
                else : 
                    print_r('L\'utilisateur non autorisé pour l\'insertion de rdv dans outlook');
                endif;
            }
            
            public function delete_event_calendar($id_rdv){

                 //$this->load->library('tr_outlook');
                $left=array();
                $left_condition['user_accounts']='user_accounts.id=rdv.id_user_create';
                array_push($left, $left_condition);
                $rdvA=$this->ban_crud_model->read_data('rdv','rdv.id_rdv_outlook as id_outlook, user_accounts.email as organize_mail', 'rdv.id_rdv='.$id_rdv, $left);
                if(!empty($rdvA)):
                    $rdv=$rdvA[0];
                    $id_rdv_outlook=$rdv->id_outlook;  
                    $email=$rdv->organize_mail;
                    $response=$this->tr_outlook->delete_event_calendar($email, $id_rdv_outlook);
                   
		               
                endif;
		
            }
            
		
	}




?>
