<?php

namespace Outlook\Models;
use CodeIgniter\Model;

class OutlookModel extends Model
{
	protected $table="email_outlook";
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	
	public function read_data($table,$field,$where)
	{
		$builder=$this->db->table($table);
		$builder->select($field);
		$builder->where($where);

		return $builder->get()->getResult();
	}

	public function insert_data($data,$table)
	{
		$builder=$this->db->table($table);
		$builder->insert($data);

		return $this->db->insertID();

	}

	public function update_data($table,$data,$where)
	{
		$builder=$this->db->table($table);
		$builder->where($where);
		$builder->update($data);
	}


	public function get_messages_no_lus()
	{
			$id_user_encours=session()->get("loggedUserId");
			$this->select("
			email_outlook_lien.id_demande,
			created_datetime,
			id_primary,
			subject

			
			
			");
			$this->join("email_outlook_lien","email_outlook.id_primary = email_outlook_lien.id_email","left");
			$this->join("demande","demande.id_demande=email_outlook_lien.id_demande","left");
			
			$this->orderBy("email_outlook.created_datetime",'DESC');

			$this->where('email_outlook.deleted = 0'); 
			$this->where('email_outlook.sender_mail IS NOT NULL');
			$this->where('email_outlook.draft', 0);
			$this->where('email_outlook.isEmailAuto IS NULL');
			
			$this->where("
			(
				(
					(
						email_outlook.created_datetime >= '2021-12-06 0000-00-00' 
						AND 
						(
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
					OR 
					(
						email_outlook.created_datetime <= '2021-12-06 0000-00-00' 
						AND 
						(
							email_outlook.lus IS NULL
							OR email_outlook.lus=''
						)

					)
				)
				AND
				(
					demande.id_utilisateur=$id_user_encours
					OR demande.id_utilisateur_2=$id_user_encours
					OR 
					(
						id_utilisateur IN 
						(
							SELECT user_accounts.id
							FROM user_accounts 
							WHERE user_accounts.id_user_back_up = $id_user_encours
						)
					)

				


				)
			)");

			$this->where('email_outlook.is_homegrade',0);

			return $this->paginate(50);

			//debugd($result);
			

	}


	public function get_messages($params=array(),$request,$orderBy=NULL,$orderDirection=NULL)
		{
			if(!isset($params['limit'])){$params['limit']=50;}	
			if(!isset($params['sort'])){$params['sort']=0;};        
			if(isset($params['count']) && $params['count']){$params['limit']=NULL;}
				
			
			//$this->join("email_outlook_lien","email_outlook_lien.id_email=email_outlook.id_primary","left");
			//On affiche seulement ceux qui ne sont pas supprimé
			$this->where("email_outlook.deleted",0);


			if(!isset($params['courriel']) || $params['courriel']!=TRUE)
			{
				//seulement ceux qui ont un expéditeur
				$this->where('email_outlook.sender_mail IS NOT NULL');
				//éliminé ceux envoyé par homegrade
				//$this->CI->db->where('email_outlook.sender_mail NOT LIKE "%'.CRMAIL.'%"');
			};

			if(isset($params['brouillons']) && $params['brouillons']==TRUE)//uniquement Brouillon
			{
				$this->where('email_outlook.draft', 1);	

			}
			else
			{
				$this->where('email_outlook.draft', 0);	//Sinon pas brouillon
			}

	
			//Liste uniquement des messages de l'utilisateur en cours
			if(isset($params['mesmessages']) && $params['mesmessages']==TRUE){
				
				$mail_session = session("loggedUserMail");

				$wheres_mesmessages = "";
				$wheres_mesmessages .= '((email_outlook.sender_mail LIKE "%'.$mail_session.'%") OR  (email_outlook.to_mail LIKE "%'.$mail_session.'%" ))';

				//+ tous les messages de mes demandes
				// $ids_demande_session_string = implode(',', $params['mesdemandes']);

				
				if(!empty($ids_demande_session_string)):
					$wheres_mesmessages .= ' OR ';
					$wheres_mesmessages .= ' 
						(
							EXISTS (
								SELECT email_outlook_lien.id_email, email_outlook_lien.id_demande 
								FROM email_outlook_lien
								WHERE email_outlook.id_primary = email_outlook_lien.id_email 
								AND email_outlook_lien.id_demande IN  (' . $params['mesdemandes'] . ')
							)
							
						)';
				endif;
				if(!empty($wheres_mesmessages)):
					$wheres_mesmessages = "(".$wheres_mesmessages.")";
				endif;

				$this->where($wheres_mesmessages);
				
			}

			//boite mail à l'origine
			if(isset($params['origin'])){
				$this->where('email_outlook.origin_mail', $params['origin']);
			}


			if(isset($params['nolus']) && $params['nolus']==TRUE){
				//Non lus par personne
				//$id_session = $this->CI->session->userdata('id');
				//$this->CI->db->where("email_outlook.lus NOT LIKE '%".$id_session.",%'");
				$id_user_encours=session("loggedUserId");
				//Non lus = FALSE dès quelqu'un a vu le message
				$this->where(
					"(
						(
							email_outlook.created_datetime >= '2021-12-06 0000-00-00' 
							AND 
							(
								(email_outlook.lus IS NULL OR email_outlook.lus='')
								OR
								(( email_outlook.lus NOT LIKE '$id_user_encours,')
								AND
								( email_outlook.lus NOT LIKE '$id_user_encours,%')
								AND
								( email_outlook.lus NOT LIKE '%,$id_user_encours,%'))
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

			}
			
			//afficher ceux qui ne sont pas traités
			if(isset($params['no_tr']) && $params['no_tr']==TRUE):
				
				
				$this->where("email_outlook.id_primary NOT IN 
								(
									SELECT email_outlook_lien.id_email 
									FROM email_outlook_lien
									WHERE email_outlook.id_primary = email_outlook_lien.id_email
								)"
				);
				/*$this->where(
					'
						!EXISTS (
							SELECT id_email 
							FROM email_outlook_lien
							WHERE email_outlook.id_primary = email_outlook_lien.id_email
						)
					'
				);*/
			endif;
			
			//si id_demande existe
			 if(isset($params['id_demande']) && !is_array($params['id_demande']) && $params['id_demande']>0):
				$this->where(
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
				$this->where(
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

			/*if(isset($params['type']) && in_array($params['type'], array('1','2'))):
				$this->CI->db->where('email_outlook.type='.$params['type']);
			else : 
				$this->CI->db->where('email_outlook.type=1');
			endif;*/

			if(isset($params['is_homegrade'])):
				if($params['is_homegrade']):
					$this->where('email_outlook.is_homegrade=1');
				else : 
					$this->where('email_outlook.is_homegrade=0');
				endif;
			endif;
			
			//Moteur de recherche
			if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
			{
				
				$items=explode(" ",$request->getVar("itemSearch"));
				$fieldSearchs=[
					"sender_mail",
					"body_preview",
					"to_mail",
					"subject"
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

		 
			if(!is_null($orderBy))
				$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
			//$this->like("nom","Wil");
			return $this->paginate(50);


	
		}

		public function get_ids_mydemande($id){
			//return ids demande dont je suis en charge ou suiveur
			$liste_id=array();
			$builder=$this->db->table("demande");
			$builder->select('demande.id_demande as ids_demande');
			
			//$this->CI->db->where('id_utilisateur='.$id.' OR id_utilisateur_2 ='.$id);
			
			$where_back_up=('id_utilisateur='.$id.' OR id_utilisateur_2 ='.$id);
			$where_back_up.=" OR (id_utilisateur=(SELECT users.id_user_back_up FROM users WHERE users.id_user_back_up=$id) AND (id_utilisateur_2 IS NOT NULL OR id_utilisateur_2 <>0 OR id_utilisateur_2 <>'') )";
			
			$builder->where($where_back_up);
			
			$builder->group_by('demande.id_demande');
			$data = $builder->get()->getResult();
			if(isset($data[0]->ids_demande)):
				foreach($data as $dt):
					array_push($liste_id,$dt->ids_demande);
				endforeach;
			endif;
			return $liste_id;
//			
		}

		public function  message_exist($id_primary)
		{
			$builder=$this->db->table("email_outlook_lien");
			$builder->where("id_email",trim($id_primary));

			return $builder->get()->getResult();
		}
		

		public function set_message_demande($data,$id_documents,$id_demande=0)
		{
			$builder=$this->db->table("email_outlook");
			$builder->insert($data);
			$id_email=$this->db->insertId();

			$builder=$this->db->table("email_outlook_lien");
			$data_lien["id_demande"]=$id_demande;
			$data_lien["id_email"]=$id_email;
			$data_lien["date_created"]=date("Y-m-d H:i:s");
			$builder->insert($data_lien);

			if(!empty($id_documents))
			{
				foreach($id_documents as $id_document)
				{
					$builder=$this->db->table("email_demande_depots_lien");

					$data_lien_depot["id_document"]=$id_document;
					$data_lien_depot["id_demande"]=$id_demande;
					$data_lien_depot["id_message"]=$id_email;
					$data_lien_depot["date_created"]=date("Y-m-d H:i:s");

					$builder->insert($data_lien_depot);

				}

			}
		}

		public function message_view($id_message)
		{
			$builder=$this->db->table("email_outlook");

			$builder->where("id_primary",$id_message);

			return $builder->get()->getRow();

		}

		public function nombre_fichier_join($id_message)
		{
			$builder=$this->db->table("document_upload_lien");
			
			$builder->where("document_upload_lien.id_message",$id_message);

			$fichiers=$builder->get()->getResult();

			return count($fichiers);

		}

		public function get_fichier_joins($id_message)
		{
			$builder=$this->db->table("document_upload_lien");
			
			$builder->where("document_upload_lien.id_message",$id_message);
			$builder->join("document_upload","document_upload.id=document_upload_lien.id_document","left");

			return $fichiers=$builder->get()->getResult();

			

		}

}
