<?php 

	namespace Outlook\Controllers;

	use Base\Controllers\BaseController;

	use Layout\Libraries\LayoutLibrary;

	use Outlook\Libraries\Tr_outlook;
	use Outlook\Libraries\Myoutlook_lib;

	use Outlook\Models\OutlookModel;

	use Components\Libraries\ComponentOrderBy;

	use Components\Models\BanCrudModel;

	use Historique\Controllers\Historique;


	class Outlook extends BaseController {
		

		public function __construct()
		{
			if(session()->get("loggedUserRoleId")==2)
			{
				header("Location:" . base_url("user"));
			}
				if(session()->get("loggedUserRoleId")!=1)
			{
				header("Location:" . base_url("identification/logout"));
			}

			$this->historique=new Historique();
			
        	parent::__construct(__NAMESPACE__);
			

			
			$layout_l = new LayoutLibrary();

			$this->myoutlook_lib=new Myoutlook_lib();
			$this->tr_outlook=new Tr_outlook();

			$this->outlookModel=new OutlookModel;

			$this->context = 'outlook';
			$this->datas->theme = $layout_l->getThemeByRef($this->context);
			$this->datas->context = $this->context;
			$this->viewpath = $this->module . "\Views"; 

			$this->componentOrderBy=new ComponentOrderBy();


			$this->autorisationManager = \Config\Services::autorisationModel();

			


		}


		


		public function test_envoi_mail()
		{
			$email_service = \Config\Services::email();

			$from_mail = CRMAIL;
			$from_name = 'Homegrade';
			$email_service->setFrom($from_mail, $from_name);
			$email_service->setMailtype("html");

			$email_service->setTo("jeremy.blampa$db@banlieues.be");
			$email_service->setSubject("Test d'envoi");
			$email_service->setMessage("Ceci est un test");

			if($email_service->send()){
				   echo "Send Ok";
				}else{
					echo "FALSE SEND";
				}
		}

		//Correspond à $dbdex outlook
		public function liste_message($num_onglet=0)//anciennement sync_outlook
		{             
			
			
			session()->set("interface","outlook");
			$params=[];
			$this->datas->is_marque=0;

			$request=$this->request;
		


			$this->datas->title="Boîte Mail";
			$this->datas->titleView="Outlook-Liste des messages";

			$orderBy=$this->componentOrderBy->getOrderBy("email_outlook.created_datetime",$this->request);
			$orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);
	
			$fieldsOrder=
			[
				
				"value1"=>[null,false],
			
	
			];
			//debug(session("loggedUserId"),true);

			//Si la personne peut tout voir alors, les mails concernant l'adresse général Homegrade
			//S$dbon le système montre que les mails liés à l'adresse de la personne

			if($this->autorisationManager->is_autorise("email_all_r"))
       		{
				//debugd("autriser_partout");
            	$this->datas->mail_user=CRMAIL;
       		}
			else
			{

				//debugd("autriser_que_pour_mon_mail");
				$this->datas->mail_user=session("loggedUserMail");
				$params_count['origin']=session("loggedUserMail");//Uniquement les mails liés à l'orig$dbe
				
                $params['mesmessages'] = true;
               // $params['mesdemandes'] = $this->myoutlook_lib->get_requete_ids_demande(session("loggedUserId"));

                $type_view="mesmessages";
				$this->datas->type_view=$type_view;
				$this->datas->is_marque=1;
			}

			if($num_onglet==1)
			{
				$params['no_tr']=TRUE;
				$this->datas->no_tr=1;
			}
			else
			{
				$this->datas->no_tr=0;
			}
			

			/*if($this->request->getVar('id_demande')):
				$data_table["is_marque"]=1;
			 elseif($this->request->getVar('type_view') && $this->request->getVar('type_view')=='mesmessages'):
				 $data_table["is_marque"]=1;
			 else:
				 $data_table["is_marque"]=0;
			 endif;*/



			$this->datas->messages=$this->outlookModel->get_messages($params,$request,$orderBy,$orderDirection);
			$pager=$this->outlookModel->pager;
			$this->datas->pager=$pager;
			$this->datas->nbMessages=$pager->getTotal();
            $this->datas->itemSearch=$this->request->getVar("itemSearch");
			$this->datas->titleView="Liste des messages";
            $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
			$this->datas->outlookModel=$this->outlookModel;
			$this->datas->onglet=$num_onglet;
        

			return view($this->viewpath .'\listMessage', (array) $this->datas);

		}


		//ancien code

		public function sync_outlook()//A garder pour récupèrer les mails d'outlook
		{

		}

		public function test(){
		   
//                    $email="elena.possia@homegrade.brussels";
//    
//                    //$email="contact.crm@homegrade.brussels";
//                    $where=array(
//                        'StartDateTime'=>'2019-10-01T01:00:00',
//                        'EndDateTime'=>'2019-10-30T23:00:00'
//                    );
//                    $response=$this->tr_outlook->get_calendar_view($email, $where);
//                    $value=$response->value;
//                   echo '<pre>';
//                   print_r($response);
		}

		

		public function insert_id_crm_folder(){
                    ini_set('max_execution_time', '0'); // for infinite time of execution 
			$id_crm_inscrit = 0;
			$id_crm_nofount = 0;
                        $view_nofount="<br>";

			$users = $this->db->get('user_accounts')->result();

			foreach ($users as $user) :
				if($user)
				$user_mail = $user->mail;
				$id_user = $user->id_user;
				//si l'email est dans le groupe homegrade.brussels
				if($this->tr_outlook->in_users_outlook($user_mail)):
					$response = $this->tr_outlook->get_folders($user_mail);
                                        
					if(isset($response->value)&&!empty($response->value)):
						$folder_found=0;
						foreach ($response->value as $val) {
							if($val->displayName == 'CRM' || $val->displayName == 'crm'){
								$dataFolder = array();
								$dataFolder['id_folder_crm'] = $val->id;
							//	$this->db->where('id_user', $id_user);
							//	$this->db->update('users', $dataFolder);
								$id_crm_inscrit++;
								$folder_found++;
							}
						}
						if($folder_found==0){
							$id_crm_nofount++;
                                                        $view_nofount.=$user->prenom.' '.$user->nom.' '.$user->mail.' <br>';
						}
					endif;
				endif;

			endforeach;

			echo "C'est fait!<br>";
			echo "ID_FOLDER_CRM inscrit : ".$id_crm_inscrit." users<br>";
			echo "Dossier non trouvé :<br>".$id_crm_nofount. " users <br>";
                        echo $view_nofount;
		}
		function slugify_name_file($text, string $divider = '-')
			{
			// replace non letter or digits by divider
			$string_replace="49002100aaazimp6";
			$text=str_replace(".",$string_replace,$text);
			$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

			// transliterate
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

			$string_replace=iconv('utf-8', 'us-ascii//TRANSLIT', $string_replace);

			// remove unwanted characters
			$text = preg_replace('~[^-\w]+~', '', $text);

			// trim
			$text = trim($text, $divider);

			// remove duplicate divider
			$text = preg_replace('~-+~', $divider, $text);

			// lowercase
			$text = strtolower($text);

			if (empty($text)) {
				return $text;
			}
			$text=str_replace($string_replace,".",$text);
			return $text;
			}
		
		public function download_base64_document($id_document=0){
			$this->db->select('*');
			$this->db->from('email_demande_depots');
			$this->db->where('id', $id_document);
			$file = $this->db->get()->result();

			$name=$file[0]->name;
			$name = str_replace("e?", "é", $name);
			$name = str_replace("o?", "ô", $name);
			$name = str_replace("u?", "û", $name);
			$name = str_replace("c?", "ç", $name);
			$name = str_replace("a?", "à", $name);
			$name=$id_document."_".$this->slugify_name_file($file[0]->name);
			$path="assets/demandes/documents/";
			file_put_contents($path.$name, base64_decode($file[0]->contentByte), LOCK_EX);

                   // $sql_update="UPDATE email_demande_depots SET contentByte='',contentByte_Type='',url_file='$name' WHERE id=$id_document";
					
				   $sql_update="UPDATE email_demande_depots SET url_file='$name' WHERE id=$id_document";

					$this->db->query($sql_update);

					$this->download_document($id_document);
					exit;
					return;

			$file = $this->db->get()->result();




			if(count($file)>0):
								header("Content-Type: ".$file[0]->contentByte_Type);
                                header('Content-Disposition: attachment; filename="'.$file[0]->name.'"');
								
                               // header("Content-disposition: attachment; filename=toto.pdf");
                              // ob_clean();
                               //flush();
				echo base64_decode($file[0]->contentByte); 
                                exit;
                //force_download($file[0]->name,base64_decode($file[0]->contentByte));
			else : 
				echo 'fichier non trouvé dans la base de données.';
			endif;
		}

		public function download_document($id_document){
			$this->db->select('*');
			$this->db->from('email_demande_depots');
			$this->db->where('id', $id_document);
			$file = $this->db->get()->result();

			if(count($file)>0):

			



						$this->load->helper('download');

						

						$chemin=base_url().'assets/demandes/documents/'.$file[0]->url_file;
						
											//$type="application/octet-stream";
											//header("Content-type: ".$file[0]->contentByte_Type);
											//header("Content-Description: File Transfer");
											//header("Content-Type: $type\n");
											//header("Content-Transfer-Encoding: binary");
											//header("Content-disposition: attachment; filename=".urlencode($file[0]->name));
											//header("Content-Length: ".filesize( $chemin ) );
											force_download($file[0]->name,file_get_contents(base_url().'assets/demandes/documents/' . $file[0]->url_file));
											//readfile($chemin);
											
										
										
										
							//			
							//				    header("Content-type: application/force-download");
							//		    header("Content-Disposition: attachment; filename=".urlencode($file[0]->name)); 
							// //ici ont met le nom original
							//		    $dossier=base_url().'assets/demandes/documents/';
							//			readfile($dossier.$file[0]->url_file);
											//force_download($file[0]->name,file_get_contents(base_url().'assets/demandes/documents/' . $file[0]->url_file));
				
			else : 
				echo 'fichier non trouvé dans la base de données.';
			endif;
		}

		public function index(){
			echo '<p>Bienvenu dans le package Myoutlook!</p>';
			$params['id_demande'] = 6649;
			$params['id_message'] = 10445;
			$this->myoutlook_lib->mails_notification($params);
		}
		
		

		public function mesmessages(){                                 
			$params_count["id_user"]=session("loggedUserId");
			$data['span_count_no_lus'] = $this->l_messagerie->span_count_no_lus($params_count);
			$data["token"]= $this->l_messagerie->get_token();
			$data["type_view"]="mesmessages";
			$data["num_onglet"]=0;

			session()->set("interface",'outlook');
			$this->load->view("template/header"); //view app général
			$this->load->view("js_messagerie/reload_js",$data); //js module messagerie
			$this->load->view("fh_dao_controller_form_js");
			$this->load->view("fh_dao_js");
			$this->load->view("template/rae_js");
			$this->load->view("template/nav",$data); //view app général
			$this->load->view('sync_outlook'); //view module outlool
			$this->load->view("template/footer"); //view app général
		}

		public function mesmessages_internes(){
			$params = array();
		    $params["id_user"]=session("loggedUserId");
		    $params['type']='mesmessages';
		   

		    $params_count["id_user"]=session("loggedUserId");
			$data['span_count_no_lus'] = $this->l_messagerie->span_count_no_lus($params_count);
			$data["token"]= $this->l_messagerie->get_token();
			$data["type_view"]="mesmessages";
			$data["num_onglet"]=0;

			session()->set("interface",'outlook');
			$this->load->view("template/header"); //view app général
			$this->load->view("js_messagerie/reload_js",$data); //js module messagerie
			$this->load->view("fh_dao_controller_form_js");
			$this->load->view("fh_dao_js");
			$this->load->view("template/rae_js");
			echo $this->load->view("template/nav",$data, TRUE); //view app général
			 echo $this->l_messagerie->get_view($params);
			echo $this->load->view("template/footer", NULL, TRUE); //view app général
		}


		public function get_message_iframe($id_message=0, $courriel=1){
			$data['id_message']= $id_message;
			$params['id_message'] = $id_message;
			$message = $this->myoutlook_lib->get_message($params);
			$data['message']= $message[0];

			$this->load->add_package_path(FHPATH."fh/messagerie");
			echo $this->load->view('template/header', NULL, TRUE);
			echo $this->load->view('css_messagerie/mail-box', $data, TRUE);
			echo $this->load->view('content_mail_outlook_iframe', $data, TRUE);
		}
		
		public function get_message($id_message=0, $courriel=1){
				
			$id_message = trim($id_message);
			$data = array();
			$data['courriel']=$courriel;
			$data['id_message'] = $id_message;
			
			$this->load->add_package_path(FHPATH."fh/messagerie");
			echo $this->load->view('css_messagerie/mail-box', $data, TRUE);
			echo $this->load->view('content_mail_outlook', $data, TRUE);
		}
		
		public function get_form_message($id_demande=0, $id_message=0, $type="response"){

			if(!in_array($type, array('response', 'transfere', 'brouillon'))){
				$type = "response";
			}

			$data =array();
			$data['id_demande']=$id_demande;
			$data['id_message']=$id_message;
			$data['type']=$type;

			if($id_message>0){
				$params['id_message']=$id_message;
				$data['message']=$this->myoutlook_lib->get_message($params);
			}
			
			echo $this->load->view('send_outlook', $data, TRUE);
		}

		public function refresh_nontraites($num_onglet=0)//anciennement sync_outlook
		{             
			
			
			session()->set("interface","outlook");
			$params=[];
			$this->datas->is_marque=0;

			$request=$this->request;
		


			$this->datas->title="Boîte Mail";
			$this->datas->titleView="Outlook-Liste des messages";

			$orderBy=$this->componentOrderBy->getOrderBy("email_outlook.created_datetime");
			$orderDirection=$this->componentOrderBy->getOrderDirection("DESC");
	
			$fieldsOrder=
			[
				
				"value1"=>[null,false],
			
	
			];
			//debug(session("loggedUserId"),true);

			//Si la personne peut tout voir alors, les mails concernant l'adresse général Homegrade
			//S$dbon le système montre que les mails liés à l'adresse de la personne

			if($this->autorisationManager->is_autorise("email_all_r"))
       		{
				//debugd("autriser_partout");
            	$this->datas->mail_user=CRMAIL;
       		}
			else
			{

				//debugd("autriser_que_pour_mon_mail");
				$this->datas->mail_user=session("loggedUserMail");
				$params_count['origin']=session("loggedUserMail");//Uniquement les mails liés à l'orig$dbe
				
                $params['mesmessages'] = true;
               // $params['mesdemandes'] = $this->myoutlook_lib->get_requete_ids_demande(session("loggedUserId"));

                $type_view="mesmessages";
				$this->datas->type_view=$type_view;
				$this->datas->is_marque=1;
			}

		
				$params['no_tr']=TRUE;
				$this->datas->no_tr=1;
			
			
			

			/*if($this->request->getVar('id_demande')):
				$data_table["is_marque"]=1;
			 elseif($this->request->getVar('type_view') && $this->request->getVar('type_view')=='mesmessages'):
				 $data_table["is_marque"]=1;
			 else:
				 $data_table["is_marque"]=0;
			 endif;*/



			$this->datas->messages=$this->outlookModel->get_messages($params,$request,$orderBy,$orderDirection);
			$pager=$this->outlookModel->pager;
			//$this->datas->pager=$pager;
			echo $this->datas->nbMessages=$pager->getTotal();
           /* $this->datas->itemSearch=$this->request->getVar("itemSearch");
			$this->datas->titleView="Liste des messages";
            $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
			$this->datas->outlookModel=$this->outlookModel;
			$this->datas->onglet=$num_onglet;*/
        

			//return view($this->viewpath .'\listMessage', (array) $this->datas);

		}

		public function refresh_nontraites_old(){

			//on vérifie si on est encore connecter
			$url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
			if(!in_array(base_url(),$url_permise))
			{
				//echo "666!";
				
				//return;
			}
		

			/*echo "cool";
			exit();
			return;*/
			$post = $this->request->getVar();
			if(isset($post['origin'])):
				$config_nontraites = array(
					'count'=>TRUE,
				    'origin'=>$post['origin'], //Si il n'a pas l'autorisation de tout voir
					'no_tr'=>TRUE
				);
			else:
				$config_nontraites = array(
					'count'=>TRUE,
					'no_tr'=>TRUE
				);
			endif;


			if($this->autorisationManager->is_autorise("email_all_r"))
			{
				

			}
			else
			{
				//$config_nontraites['mesmessages'] = true;
			}



			$count_nontraites =  $this->myoutlook_lib->get_messages($config_nontraites);

			echo $count_nontraites;
			//exit();
		}

		public function refresh_nonlus($count=1){

			$url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
				if(!in_array(base_url(),$url_permise))
				{
					echo "666!tt";
					//exit();
					return;
				}
			//$response= $this->myoutlook_lib->refresh_nonlus(session()->get("loggedUserId"),true);

			//debugd($response);
			/*echo "ccol";
			exit();
			return;*/
			//SQL count mails non traités
			if($count){

				$config_nonlus = array
				(
					'count'=>TRUE,
					//'id_demande'=> $this->myoutlook_lib->get_ids_mydemande(session()->get("loggedUserId")),
					'id_requete_demandes'=> $this->myoutlook_lib->get_requete_ids_demande(session()->get("loggedUserId")),
					'nolus'=>TRUE,
					'is_homegrade'=>FALSE 
				);

			
					$count_nonlus =  $this->myoutlook_lib->get_messages($config_nonlus);
				



				echo $count_nonlus;


			}
			else
			{

				$config_nonlus = array(
					//'id_demande'=> $this->myoutlook_lib->get_ids_mydemande(session()->get("loggedUserId")),
					'id_requete_demandes'=> $this->myoutlook_lib->get_requete_ids_demande(session()->get("loggedUserId")),
					'nolus'=>TRUE,
				    'limit'=>10000,
				    'is_homegrade'=>FALSE
				);
				
				$view = '';




					$response =  $this->myoutlook_lib->get_messages($config_nonlus);

					
					foreach ($response as  $value) 
					{
						//Recupere demande 
						$db = db_connect();
						$builder=$db->table('email_outlook_lien');
						$builder->where('id_email',$value->id_primary);
						$lien=$builder->get()->getResult();
						
						//$lien = $this->db->select('*')->where('id_email',$value->id_primary)->get('email_outlook_lien')->result();
						if(isset($lien[0]->id_demande)):
							$id_demande = $lien[0]->id_demande;

							//$date = new DateTime($value->created_datetime);
							$date=$value->created_datetime;

							$view.=view($this->viewpath .'\liste_mail_non_lu',
										[
											"id_demande"=>$id_demande,
											'date'=>$date,
											'value'=>$value,
											'id_email_primary'=>$value->id_primary,
										]
								);
							/*$view .= '<li><div onglet="outllokk_message" style="padding-left: 5px; cursor: pointer;" class="col-md-12 col-sm-12 col-xs-12 pd-l0 fh_dao_fiche open-msg-nolu-notif" href="'.base_url().'fh/fhc_dao/page_view/'.$id_demande.'fhd_liste_demande" fh-descriptor="fhd_liste_demande" href-ajax="'.base_url().'fh/fhc_dao/get_fiche/'.$id_demande.'" href-title="'.base_url().'fh/fhc_dao/get_fiche_title/'.$id_demande.'" >';
							$view .= '<p class="time">'.date_format($date, 'd/m/Y à H:i').'</p><br>';*/
							/*$view.='<a href="'.base_url().'/demande/fiche/'.$id_demande.'" class="list-group-item list-group-item-action">';
							$view .= $date; 
							$view .= $value->subject; 
							$view.='</a>';*/
			            	//$view .= '</div></li>';
			            endif;
					}

				

				echo $view;
			}
			
			
		}

		public function add_image_content(){
			
			$config['upload_path'] = './assets/demandes/documents/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|txt|xls|xlsx|pdf|odt|ppt|pps|pptx|ppsx';
		    $config['max_size'] = 1024 * 8;
		    $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
			$is_error=0;
			$message="";
			$url="";
		
            if ( ! $this->upload->do_upload('image'))
            {  
				$message= html_entity_decode($this->upload->display_errors('', ''));
                        $is_error=1;
            }else{
                $image_data = $this->upload->data();
				
				$data_kl["name"]=$_FILES['image']['name'];
				$data_kl["url_file"]=$image_data['file_name'];
				$this->db->insert("email_demande_depots",$data_kl);

				$url = base_url().'assets/demandes/documents/'.$image_data['file_name'];
			
				/*if($image_data['image_width']>770):
				$config=array();
				$config['image_library'] = 'gd2';
				$config['source_image']	="./assets/demandes/documents/".$image_data['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']	= 770;
				$config['height']	= 448;
				$config["master_dim"]	= "width";
				
				$this->load->library('image_lib',$config);
				$this->image_lib->resize();
				$this->image_lib->clear();
				endif;*/
                       
           	}
		
	 		    echo json_encode(
					array('url'  => $url,
						'is_error'=>$is_error,
						'message'=>$message
					)
				);

		}

		public function save_brouillon(){
			/*table email_outlook_brouillon*/
			$post = $this->request->getVar();

			$data = array(
				'is_homegrade'=>1,
				'created_datetime'=> date("Y-m-d H:i:s"),
				'subject'=> $post['objet'],
				'body_preview'=> $post['message'],
				'body_content'=> $post['message'],
				'sender_mail'=> CRMAIL,
				'to_mail'=>$post['destinataire'],
				'cc_mail'=> $post['cc'],
				'bcc_mail'=> $post['cci'],
				'draft'=>1,
				"lus"=>$_SESSION["id"].","
			);
			
			if(!isset($post['id_message'])):
				$this->db->insert('email_outlook', $data);
				$insert_id = $this->db->insert_id();
						
				$id_type_demande=array(11);
				
				$data_link = array(
					'id_email'=>$insert_id,
					'id_demande'=>$post['id_demande']
				);
				$this->db->insert('email_outlook_lien', $data_link);
			else : 
				$this->db->where('id_primary', $post['id_message']);
				$this->db->update('email_outlook', $data);
			endif;

			echo json_encode(array('id'=>TRUE));
			exit();
		}
		


		public function send_message(){

			
			$post = $this->request->getPost();

			//debugd($post);
			$db=db_connect();
			//debugd($post);

			if(isset($_FILES['files_email_demande'])):
				$post['files_email_demande'] = $_FILES['files_email_demande'];
			endif;
			
			$ce_qui_est_attachment=array();
			$ce_qui_est_attachment_id=[];
			//print_r($_FILES['files_email_demande']); die;

			if(!$post["is_brouillon"])
			{
				$errors = $this->verification_errors($post);

			}
			else
			{
				$errors = NULL;
			}


			if(empty($errors)):
				$attach_not=array();
				preg_match_all( '/src="([^"]*)"/i', $post['body_content'], $domnot ) ;
			
				if(isset($domnot)&&!empty($domnot))
				{
					foreach($domnot[1] as $src_domnot)
					{
						
						if(!empty($src_domnot)):
							
						
								array_push($attach_not,$src_domnot);
						endif;		
					}
					//die($dom[1]);
				}

				//upload files
				if(isset($post['files_email_demande'])):
	            	//print_r($post); 
					if(isset($post['files_email_demande']['name'])):
		            $number_uploaded = count($post['files_email_demande']['name']);
		            $post_files = $post["files_email_demande"];
		            $data_upload = array();
		            $error_files = array();
		            $success = 0;
					
		            // Faking upload calls to $_FILE
	            	for ($i = 0; $i < $number_uploaded; $i++) :
		                $_FILES['files_email_demande']['name']     = $post_files['name'][$i];
		                $_FILES['files_email_demande']['type']     = $post_files['type'][$i];
		                $_FILES['files_email_demande']['tmp_name'] = $post_files['tmp_name'][$i];
		                $_FILES['files_email_demande']['error']    = $post_files['error'][$i];
		                $_FILES['files_email_demande']['size']     = $post_files['size'][$i];

		                $file_element_name = 'files_email_demande';
		                $config['upload_path'] = './assets/demandes/documents';
		                $config['allowed_types'] = 'gif|jpg|jpeg|rtf|csv|png|doc|docx|txt|xls|xlsx|pdf|odt|ppt|pps|pptx|ppsx';
		                $config['max_size'] = 1024 * 8;
		                $config['encrypt_name'] = TRUE;

	                	$this->load->library('upload', $config);

		                if ( $this->upload->do_upload($file_element_name)) :
		                    $temp = array(
		                        "statut"=>"success",
		                        "libelle"=> $_FILES['files_email_demande']['name'],
		                        "options"=> $this->upload->data(),
		                     );

	                     	array_push($data_upload, $temp);

	                     	$success++;
		                endif;

		            endfor;
					endif;
		        endif;

				

				if(isset($data_upload)):
					foreach ($data_upload as $value) {

						//attach
						if(!in_array($value['options']['full_path'],$attach_not)):
						 $this->email->attach($value['options']['full_path']);
						endif;

						 array_push($ce_qui_est_attachment,$value['options']['full_path']);
					}
				endif;

				if(isset($post["id_document"])):
					foreach($post["id_document"] as $document):
						$builder=$db->table("document_upload");

						$builder->select('url_file,id');
						$builder->where('id', $document);
						$files = $builder->get()->getResult();

						foreach ($files as $file) 
						{
							if(!empty(trim($file->url_file))):

								$urlatt = PATH_DOCU_DEMANDE . $file->url_file;

							  	//$urlatt=base_url()."demandes/documents/$file->url_file";
						   
								//$urlatt= base_url()."fh/myoutlook/download_base64_document/$file->id";
							endif; 
					    }
					
						if(!in_array($urlatt,$ce_qui_est_attachment)):

							array_push($ce_qui_est_attachment_id,$document);
							array_push($ce_qui_est_attachment,$urlatt);


						endif;
						
					endforeach;		

				endif;
				

		
				//Ajout d'image
				

				preg_match_all( '/src="([^"]*)"/i', $post['body_content'], $dom ) ;
			
				if(isset($dom)&&!empty($dom))
				{
					foreach($dom[1] as $src_dom)
					{
						if(strpos($src_dom,"SignatureBoiteInfo")!=false)
						{
							continue;
						}
						if(!empty($src_dom)):
							
							if(!in_array($src_dom,$ce_qui_est_attachment)):
								//$this->email->attach($src_dom); NE pas envoyer dans ce cas
								array_push($ce_qui_est_attachment,$src_dom);
								$attachement_body_img[]=str_replace(base_url().'demandes/documents/',NULL,$src_dom);
							endif;	
						endif;		
					}
					//die($dom[1]);
				}

			

				//Ajout d'un signature 
			    // $post['message'] .= signature_homegrade();

			    //verification de l'existance du référence dans l'objet de message
                            $objet_avec_prefixe = $post['ref_message'].$post['subject'];
                            
			    if(empty(get_string_between($objet_avec_prefixe, '#', '#'))||  is_reference_outlook($objet_avec_prefixe)){
			    	$post['subject'] = $post['subject'].' #Ref:'.$post['id_demande'].'#';
			    }


	//On regarde s'il y a des images dans messages et on les joints

			
	if(!$post["is_brouillon"]):

		$is_save=$this->myoutlook_lib->send($post['to_mail'], $objet_avec_prefixe, $post['body_content'].signature(), $post['cc_mail'], $post['bcc_mail'],$ce_qui_est_attachment);
	else:
		$is_save=TRUE;
	endif;
	

				if($is_save):
					
					
					$data = array(
						'is_homegrade'=>1,
						'created_datetime'=> date("Y-m-d H:i:s"),
						'send_datetime'=> date("Y-m-d H:i:s"),
						'subject'=> $objet_avec_prefixe,
						'body_preview'=> $post['body_content'],
						'body_content'=> $post['body_content'],
						'sender_mail'=> CRMAIL,
						'to_mail'=>$post['to_mail'],
						'cc_mail'=> $post['cc_mail'],
						'bcc_mail'=> $post['bcc_mail'],
						'is_brouillon'=>$post["is_brouillon"],
						"lus"=>session()->get("loggedUserId").","
					);
					
					$is_insert_mode=false;

					if(!isset($post['id_message'])&&$post["is_brouillon"]==0&&$post["id_mail_brouillon"]==0)
					{
						$is_insert_mode=true;
					}

					

					if($post["id_mail_brouillon"]==0&&$post["is_brouillon"]==1)
					{
						$is_insert_mode=true;
					}


					if($is_insert_mode):

						//debug($post["is_brouillon"]);
						//debugd($post["id_mail_brouillon"]);
						

						$builder=$db->table("email_outlook");
						$builder->insert($data);
						$insert_id = $db->insertId();
						
						if(isset($post["other_attachement"])):
							foreach($post["other_attachement"] as $other_attachement):
								$builder=$db->table("email_demande_depots");
								$builder->select('*');
								$builder->where('id', $other_attachement);
								$files = $db->get()->result();
		
								foreach ($files as $file) {
									if(!empty(trim($file->url_file))):
										  $urlatt=base_url()."assets/demandes/documents/$file->url_file";
								   else :
										$urlatt= base_url()."fh/myoutlook/download_base64_document/file->id";
									endif; 
								}
							
								$dataatt["name"]=$file->name;
								$dataatt["url_file"]=$file->url_file;
								$dataatt["contentByte"]=$file->contentByte;
								$dataatt["contentByte_Type"]=$file->contentByte_Type;
								$dataatt["commentaire"]=$file->commentaire;
								$dataatt["id_message"]=$insert_id;
								$dataatt["display"]=$file->display;
								$dataatt["id_type"]=$file->id_type;
								$dataatt["id_user"]=session("loggedUserId");
		
								$builder=$db->table("email_demande_depots");

								$builder->insert($dataatt);
								
		
							endforeach;		
		
						endif;		




						    $id_type_demande=array(11);
						//$this->historique->set_historique_general($id_type_demande,$post['id_demande'],$id_entity_personne=0,$id_entity_bien=0,$insert_id);
						$data_link = array(
							'id_email'=>$insert_id,
							'id_demande'=>$post['id_demande']
						);
						$builder=$db->table("email_outlook_lien");

						$builder->insert($data_link);


					elseif($post["id_mail_brouillon"]>0):
					
						$builder=$db->table("email_outlook");

						$builder->where('id_primary', $post['id_mail_brouillon']);
						$builder->update($data);
						$insert_id = $post['id_mail_brouillon'];

					else : 
						$data['draft'] = 0;
						$builder=$db->table("email_outlook");

						$builder>where('id_primary', $post['id_message']);
						$builder->update($data);
						$insert_id = $post['id_message'];
					endif;

					if(isset($data_upload)):


							foreach ($data_upload as $value) {
								$data_depot = array(
									"name"=>$value["libelle"],
									"url_file"=>$value["options"]["file_name"],
									"id_demande"=> $post['id_demande'],
									"id_message"=> $insert_id,
									"id_user"=> session("loggedUserId")
								);

								//On regarde s'il est déjà ajouté 
								$builder=$db->table("email_demande_depots");

								$builder->insert($data_depot);
					}	
					endif;

					if(isset($attachement_body_img)&&!empty($attachement_body_img)):
						foreach($attachement_body_img as $img_src_body)
						{
							$data_depot_img=array(
								
								"url_file"=>$img_src_body,
								"id_demande"=> $post['id_demande'],
								"id_message"=> $insert_id,
								"id_user"=> session("loggedUserId")
							);

							if(!in_array($src_dom,$ce_qui_est_attachment)):
								$builder=$db->table("document_upload");
								$builder->where("url_file",$img_src_body);
								$builder->update($data_depot_img,$where_depot_img);
							endif;
						}	
						

					endif;


					if(!empty($ce_qui_est_attachment_id))
					{
						foreach($ce_qui_est_attachment_id as $id_document)
						{
							$data_att["id_document"]=$id_document;
							$data_att["id_demande"]=$post['id_demande'];
							$data_att["id_message"]= $insert_id;
							$data_att["id_user"]= session("loggedUserId");

							//je dois vérifier, si les documents ne sont
							//pas déjà attaché pour ce message
							$builder=$db->table("document_upload_lien");
							$builder->where("id_message",$insert_id);
							$builder->where("id_document",$id_document);
							$result=$builder->get()->getResult();

							if(empty($result))
							{
								$builder=$db->table("document_upload_lien");
								$builder->insert($data_att);

							}

							

						}
					}



					echo json_encode(array('id'=>true));
					exit();
					
				endif;
			else : 
				echo json_encode($errors);
				exit();
			endif;
			
		}
		
		private function verification_errors($post){
			//print_r($post);
			$errors = array();
			//objet
			if(!isset($post['subject']) || empty($post['subject'])):
				$e = array(
					'id'=>false,
					'msg'=>'Vous devez définir un objet',
					'cible'=> 'output_subject_msg'
				);
				array_push($errors, $e);
			endif;

			//cc
			if(isset($post['cc_mail']) || !empty($post['cc_mail'])):
				$emails = explode(",", $post['cc_mail']);

				$error_email = 0;
				foreach ($emails as $email) {
					if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
						$error_email++;
					}
				}

				if($error_email>0){
					$e = array(
						'id'=>false,
						'msg'=>'Format e-mail incorrect',
						'cible'=> 'output_cc_mail'
					);
					array_push($errors, $e);
				}
			endif;

			//cc
			if(isset($post['cci_mail']) || !empty($post['cci_mail'])):
				$emails = explode(",", $post['cci_mail']);

				$error_email = 0;
				foreach ($emails as $email) {
					if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
						$error_email++;
					}
				}

				if($error_email>0){
					$e = array(
						'id'=>false,
						'msg'=>'Format e-mail incorrect',
						'cible'=> 'output_bcc_mail'
					);
					array_push($errors, $e);
				}
			endif;
			
			//destinataires
			if(!isset($post['to_mail']) || empty($post['to_mail'])):
				$e = array(
					'id'=>false,
					'msg'=>'Vous devez indiquer au moins une adresse mail',
					'cible'=> 'output_to_mail'
				);
				array_push($errors, $e);

			else : 
				$emails = explode(",", $post['to_mail']);

				$error_email = 0;
				foreach ($emails as $email) {
					if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
						$error_email++;
					}
				}

				if($error_email>0){
					$e = array(
						'id'=>false,
						'msg'=>'Format e-mail incorrect!',
						'cible'=> 'output_to_mail'
					);
					array_push($errors, $e);
				}
				
			endif;

			//message 
			if(!isset($post['body_content']) || empty(trim($post['body_content']))){
				$e = array(
					'id'=>false,
					'msg'=>"Vous n'avez pas écrit de message",
					'cible'=> 'output_body_content'
				);
				array_push($errors, $e);
			}

			//upload
			

			
			return $errors;
		
		}
		
		private function get_depot_sql($params=array()){
			//get depot
			$this->db->select('
					email_demande_depots.id as id,
					email_demande_depots.name as name,
					email_demande_depots.date_created as date_created, 
					email_demande_depots.date_echeance as date_echeance,
					email_demande_depots.commentaire as commentaire,
					email_demande_depots.url_file as url_file,
					email_demande_depots.id_message as id_message,
					email_demande_depots.id_demande as id_demande,
					email_demande_depots.id_type as id_type,
					list_types_depot.name as type

				');
			$this->db->from('email_demande_depots');

			
			if(isset($params['id_demande'])&&$params['id_demande']>0 && isset($params['messages_ids_string']) && !empty($params['messages_ids_string'])):
					$where_demande_message = '(email_demande_depots.id_demande = '.$params['id_demande'];
					$where_demande_message.=' OR id_message IN ('.$params['messages_ids_string'].') )';
					$this->db->where($where_demande_message);
			elseif(isset($params['id_demande'])&&$params['id_demande']>0):
				$where_demande_message = '(email_demande_depots.id_demande = '.$params['id_demande'].')';
				$this->db->where($where_demande_message);
			endif;

			

			//search
			if(isset($params['search']) && !empty($params['search'])):
				$this->db->where('
						email_demande_depots.name LIKE "%'.$params['search'].'%"   
					');
			endif;

			$this->db->where('display', 1);

			//jointure
			$this->db->join('list_types_depot', 'email_demande_depots.id_type=list_types_depot.id', 'left');
			$this->db->group_by("email_demande_depots.name");
			$this->db->order_by('email_demande_depots.id', 'DESC');

			if(isset($params['limit']) && isset($params['sort'])){
				$this->db->limit($params['limit'], $params['sort']);
			}

			if(isset($params['count']) && $params['count']==TRUE){
				return $this->db->count_all_results();
			}else{
				return $this->db->get()->result();
			}
		}

		public function get_liste_depots($id_demande=0, $page=1)
		{

 			$config =array();
			$limit = 10;

			if($page<1){
				$page=1;
			}
			
			if($page<=1){
				$sort=0;
			}else{
				$sort = (($page-1)*$limit);
			}

			if($this->request->getVar('search') && !empty($this->request->getVar('search'))):
				$params['search'] = trim($this->request->getVar('search'));
			endif;

			//$this->request->getVar('id_demande')
			//$id_demande = $id_demande;

			//get messages correspondant à id_demande
			$messages = $this->db->select('group_concat(id_email SEPARATOR ",") AS ids')->from('email_outlook_lien')->where('id_demande', $id_demande)->get()->result();
			$params['messages_ids_string'] = $messages[0]->ids;
			
			$params['id_demande'] = $id_demande;
			$params['count'] = TRUE;
			$data['all_files_count'] = $this->get_depot_sql($params);
			$params['count'] = FALSE;
			$params['limit'] = $limit;
			$params['sort'] = $sort;
			$data['files'] = $this->get_depot_sql($params);
			
			//pagination config
			$config['base_url'] = base_url('fh/myoutlook/get_liste_depots/'.$id_demande);
			$config['page'] = $page;
			$config['per_page'] = $limit;
			$config['container'] = "content_table_depots";
			$config['total_rows'] = $data['all_files_count'];
			$config['data_page_attr'] = "data-ci-pagination-depots";

			if($this->request->getVar('view')):
				$data['view'] = $this->request->getVar('view');
				$config['view'] = $this->request->getVar('view');
			else : 
				$config['view'] = "";
				$data['view'] = '';
			endif;
			

			$data["pagination"]=$this->myoutlook_lib->get_pagination($config);
			$data['id_demande'] = $id_demande;
			$data['limit'] = $limit;
			$data['sort'] = $sort;
			$data['total_rows'] = $config['total_rows'];

			
		
			echo  $this->load->view('liste_depots', $data, TRUE); //view module outlook
			echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
			echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';
			exit();
		}

		public function tr_depot_files(){
			$status="error";
			$msg="je suis une error";
      		
      		//On recupere les valeurs
			$userfile=$this->request->getVar("userfile");
			$error = array();
      		
      		//ON teste si elles sont vides ou pas
	  		$post = $this->request->getVar();

        	if(isset($_FILES['files_email_demande'])):
            	//print_r($_FILES); 
	            $number_uploaded = count($_FILES['files_email_demande']['name']);
	            $post_files = $_FILES["files_email_demande"];
	            $data_upload = array();
	            $error_files = array();
	            $success = 0;

	            // Faking upload calls to $_FILE
            	for ($i = 0; $i < $number_uploaded; $i++) :
	                $_FILES['files_email_demande']['name']     = $post_files['name'][$i];
	                $_FILES['files_email_demande']['type']     = $post_files['type'][$i];
	                $_FILES['files_email_demande']['tmp_name'] = $post_files['tmp_name'][$i];
	                $_FILES['files_email_demande']['error']    = $post_files['error'][$i];
	                $_FILES['files_email_demande']['size']     = $post_files['size'][$i];

	                $file_element_name = 'files_email_demande';
	                $config['upload_path'] = './assets/demandes/documents';
	                $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|txt|xls|xlsx|pdf|odt|ppt|pps|pptx|ppsx';
	                $config['max_size'] = 1024 * 8;
	                $config['encrypt_name'] = TRUE;

                	$this->load->library('upload', $config);

	                if ( $this->upload->do_upload($file_element_name)) :
	                     $temp = array(
	                        "statut"=>"success",
	                        "libelle"=> $_FILES['files_email_demande']['name'],
	                        "options"=> $this->upload->data(),
	                     );

	                     array_push($data_upload, $temp);

	                     $success++;
	                else :
	                    $error_files[]= $_FILES['files_email_demande']['name'];
	                endif;

	            endfor;

	            if(count($error_files)>0){
	                $msg_error = $this->upload->display_errors('', ' %s% ');
	                $tab_error = explode("%s%", $msg_error);

	                $msg_error_view = "<br>";
	                $i=0; 
	                foreach ($error_files as $value) {
	                     $msg_error_view .= "<b>".$value."</b> : ".$tab_error[$i]."<br>";
	                     $i++;
	                }

	                $e = array(
	                    'status'=>false,
	                    'msg'=>$msg_error_view,
	                    'cible'=> 'error_upload'
	                );

	                array_push($error, $e);

	            }else{
                	if(empty($error)):

	                    foreach ($data_upload as $value) {
	                        $config=array(
	                            "name"=>$value["libelle"],
	                            "url_file"=>$value["options"]["file_name"],
	                            "id_demande"=> $this->request->getVar("id_demande"),
	                            "id_user"=> session("loggedUserId")
	                        );
	                        $this->outlookModel->insert_data($config,"email_demande_depots");

	                    }
		            endif;
            	}

	        else :
	            $e = array(
	                    'status'=>false,
	                    'msg'=>"Veuillez selectionner au moins un fichier!",
	                    'cible'=> 'error_upload'
	                );

	                array_push($error, $e);
	            
	        endif;

	        if(empty($error)){
	            $result =array(
	                'status' => "success", 
	                'msg' => $msg
	            );
		    
		$id_type_demande=array(14);
			$this->historique->set_historique_general($id_type_demande,$this->request->getVar('id_demande'),$id_entity_personne=0,$id_entity_bien=0,0);
	            echo json_encode($result);
	            @unlink($_FILES[$file_element_name]);

	        }else{
	            echo json_encode($error);
	            exit();
	        }
    
		}

		public function check_lus($id_message){
			$message = $this->db->select('lus')->where('id_primary', $id_message)->get('email_outlook')->result();
			$lu_message = $message[0]->lus;

			if(empty($lu_message) || strpos($lu_message, $id_message.',') == FALSE){
				$lu_message = $lu_message.session()->get("loggedUserId").',';
				$this->db->where('id_primary', $id_message);
				$this->db->update('email_outlook', array('lus'=>$lu_message));
				echo 'success';
				exit();
			}else{
				echo 'false';
				exit();
			}

		}

		public function get_table($no_tr=0,$page=1)
		{
			$config =array();
			$limit = 30;
			$post = $this->request->getVar();

			if(isset($post['origin'])){
				$config['origin']=$post['origin'];
			}
			
			if($page<=1){
				$config["sort"]=0;
			}else{
				$config["sort"] = (($page-1)*$limit);
			}

			$config['limit'] = $limit;
			if($no_tr == 1):
				$config['no_tr']=TRUE;
			else : 
				$config['no_tr']=FALSE;
			endif;
                        
                     
                        
			if($this->request->getVar('id_demande')):
				$config['id_demande'] = $this->request->getVar('id_demande');
				$config['courriel'] = TRUE;
				$data_table['id_demande'] = $this->request->getVar('id_demande');
				$data_table['params']['id_demande'] = $this->request->getVar('id_demande');
			endif;
			if($this->request->getVar('type') && $this->request->getVar('type')=='brouillons'):
				$config['brouillons'] = TRUE;
				$data_table['brouillons']=TRUE;
			endif;
			if($this->request->getVar('type_view') && $this->request->getVar('type_view')=='mesmessages'):
				$config['mesmessages'] = TRUE;
				$config['courriel'] = TRUE;
				//$config['mesdemandes'] =  $this->myoutlook_lib->get_ids_mydemande(session()->get("loggedUserId"));
				$config['mesdemandes'] = $this->myoutlook_lib->get_requete_ids_demande(session()->get("loggedUserId"));
				$data_table['type_view']='mesmessages';
			endif;
			$data_table['messages'] = $this->myoutlook_lib->get_messages($config);

			//pagination config
			$config['base_url'] = base_url('fh/myoutlook/get_table/'.$no_tr);
			$config['page'] = $page;
			$config['per_page'] = $config['limit'];
			$config['container'] = "content_table_messages";
			
			//get count messages
			$params_count = array();
			if(isset($post['origin'])){
				$params_count['origin']=$post['origin'];
			}
			$params_count['count']=TRUE;
			$params_count['no_tr']=$config['no_tr'];
			if($this->request->getVar('id_demande')):
				$params_count['id_demande'] = $this->request->getVar('id_demande');
				$config['courriel'] = TRUE;
			endif;
			if($this->request->getVar('type_view') && $this->request->getVar('type_view')=='mesmessages'):
				$params_count['mesmessages'] = TRUE;
				$config['courriel'] = TRUE;
				//$params_count['mesdemandes'] =  $this->myoutlook_lib->get_ids_mydemande(session()->get("loggedUserId"));
				$params_count['mesdemandes'] = $this->myoutlook_lib->get_requete_ids_demande(session()->get("loggedUserId"));

				$data_table['type_view']='mesmessages';
			endif;
			if($this->request->getVar('type') && $this->request->getVar('type')=='brouillons'):
				$params_count['brouillons'] = TRUE;
				$data_table['brouillons']=TRUE;
			endif;
			$config['total_rows'] = $this->myoutlook_lib->get_messages($params_count);
			$config['data_page_attr'] = "data-ci-pagination-message";
			$data_table["pagination"]=$this->myoutlook_lib->get_pagination($config);
			$data_table["limit"]=$config['limit'];
			$data_table["total_rows"]=$config['total_rows'];
			$data_table["sort"]=$config['sort'];
			if(isset($post['origin'])){
				$data_table['origin']=$post['origin'];
			}
                        if($this->request->getVar('id_demande')):
                           $data_table["is_marque"]=1;
                        elseif($this->request->getVar('type_view') && $this->request->getVar('type_view')=='mesmessages'):
                            $data_table["is_marque"]=1;
                        else:
                            $data_table["is_marque"]=0;
                        endif;
                 $data_table["no_tr"]=$config["no_tr"];
			  //print_r($data_table);
			echo  $this->load->view('table', $data_table, TRUE); //view module outlook
			exit();
		}
	 
		public function move_mailoutlook_db(){
			$id_message = trim($this->request->getVar('id_message'));
			

			
			if($this->ban_crud_model->delete_data('email_outlook_lien', array('id_email'=>$id_message))):
			          
					    $id_type_demande=array(13);
					$this->historique->set_historique_general($id_type_demande,$this->request->getVar('id_demande'),$id_entity_personne=0,$id_entity_bien=0,$id_message);
				echo "<a href='".base_url()."/demande/new/outlook/$id_message' class='btn btn-sm btn-secondary'><i class='fa fa-link'></i> Joindre</a> <a href='#'' class='btn btn-sm btn-danger email_outlook_delete_def'><i class='fa fa-trash'></i> </a>";
			endif;
		}

		public function delete_mailoutlook_db(){
			$id_message = trim($this->request->getVar('id_message'));
			if($this->db->delete('email_outlook', array('id_primary'=>$id_message))):
				echo 'success';
			else : 
				echo 'false';
			endif;
		}

		public function get_count_notraite(){
			$config_nontraites = array(
	   					'count'=>TRUE,
	   					'no_tr'=>TRUE
	   				);
	   		echo  $this->myoutlook_lib->get_messages($config_nontraites);
		}

		public function import_outlook($admin=0){ //importation outlook
			$response = array();
			$response_error = array();
			$this->load->library('tr_outlook');
			$this->load->library('myoutlook_lib');
			$id_session = $this->session->userdata('id');
			$users = $this->db->select('id_folder_crm, mail')->from('users')->where('id_user', $id_session)->get()->result();
			//verification si email personnel ds tenant 
			if(isset($users[0]->mail) && !empty($users[0]->mail)  && $this->tr_outlook->in_users_outlook($users[0]->mail)==TRUE && $users[0]->mail != CRMAIL):
				$mail = $users[0]->mail;
//                                $messages_inbox=array();
//				//recupere mails inbox
//				$mails_folder_inbox=$this->tr_outlook->get_messages_folder('inbox', $users[0]->mail);
//				//recuperer les id_conversation
//			  	if(isset($mails_folder_inbox->value)):
//                                    foreach ($mails_folder_inbox->value as $mail_folder_inbox) {
//                                        array_push($messages_inbox, $mail_folder_inbox);
//                                        $id_conversation = $mail_folder_inbox->conversationId;
//                                        //print_r($id_conversation);
//                                        $mails_inbox_conversation = $this->tr_outlook->get_messages_conversation($id_conversation, $mail);
//                                        if($mails_inbox_conversation):
//                                                foreach ($mails_inbox_conversation->value as $value_mails_conversation) {
//                                                  array_push($messages_inbox, $value_mails_conversation);
//                                                }
//                                        endif;
//                                    }
//                                endif;
//				//envoyer vers la base de données ceux qui concerne le CRM d'où controlle = TRUE
//                                //print_r($messages_inbox); die();
//				$response_var = $this->myoutlook_lib->save_import_mail($messages_inbox, $users[0]->mail, TRUE, TRUE);


				//verif l'existance de l'ID CRM ds mail
				if(!empty($users[0]->id_folder_crm)){
                                    $messages_user_crm_folder=array();
                                    $mails_folders_crm=$this->tr_outlook->get_messages_folder($users[0]->id_folder_crm, $users[0]->mail, $users[0]->mail);
                                    //recuperer les id_conversation
                                    if(isset($mails_folders_crm->value)):
                                        foreach ($mails_folders_crm->value as $mail_folder_crm) {
                                            array_push($messages_user_crm_folder, $mail_folder_crm);
                                            $id_conversation = $mail_folder_crm->conversationId;
                                            //print_r($id_conversation);
                                            $mails_crm_conversation = $this->tr_outlook->get_messages_conversation($id_conversation, $mail);
                                            if($mails_crm_conversation):
                                                    foreach ($mails_crm_conversation->value as $value_mails_crm_conversation) {
                                                      array_push($messages_user_crm_folder, $value_mails_crm_conversation);
                                                    }
                                            endif;
                                        }
                                    endif;
                                    //envoyer vers la base de données 
                                    $response_var_crm = $this->myoutlook_lib->save_import_mail($messages_user_crm_folder, $users[0]->mail, FALSE, TRUE);

                                    //addition
                                    $response_var['importe'] =  $response_var_crm['importe'];
                                    $response_var['lier'] =  $response_var_crm['lier'];

				}
				/*else{

					//$this->tr_outlook->create_folder($users[0]->mail,"crm");
				}*/

				//nombre importé 
				$response[$users[0]->mail]['importe'] =  $response_var['importe'];
				$response[$users[0]->mail]['lier'] = $response_var['lier'];
			else :
				$response_error[$users[0]->mail] = "Cette adresse email ne peut pas être importé!";
			endif;
			

			if($admin==1):
			//import crm_contact
			$mail = CRMAIL;
			$id_crm = IDCRMFOLDER;
			if($this->tr_outlook->in_users_outlook($mail)==TRUE):
                            $messages_inbox=array();
                            //recupere mails inbox
                            $mails_folder_inbox=$this->tr_outlook->get_messages_folder('inbox', $mail);
                            //ids conversation
                            if(isset($mails_folder_inbox->value)):
				foreach ($mails_folder_inbox->value as $mail_folder_inbox) {
                                    array_push($messages_inbox, $mail_folder_inbox);
                                    $id_conversation = $mail_folder_inbox->conversationId;
                                    $mails_conversation = $this->tr_outlook->get_messages_conversation($id_conversation, $mail);
                                    if($mails_conversation):
                                            foreach ($mails_conversation->value as $value_mails_conversation) {
                                              array_push($messages_inbox, $value_mails_conversation);
                                            }
                                    endif;
			  	}
			    endif;
                            //envoyer vers la base de données ceux qui concerne le CRM d'où controlle = TRUE
                            $response_var = $this->myoutlook_lib->save_import_mail($messages_inbox, $mail, TRUE, TRUE);

                            //verif l'existance de l'ID CRM ds mail
                            if(!empty($id_crm)){
                                $messages_user_crm_folder=array();
                                $mails_folders_crm=$this->tr_outlook->get_messages_folder($id_crm, $mail);
                                if(isset($mails_folders_crm->value)):
                                    foreach ($mails_folders_crm->value as $mail_folder_crm) {
                                        array_push($messages_user_crm_folder, $mail_folder_crm);
                                        $id_conversation = $mail_folder_crm->conversationId;
                                        $mails_conversation_crm = $this->tr_outlook->get_messages_conversation($id_conversation, $mail);
                                        if($mails_conversation_crm):
                                                foreach ($mails_conversation_crm->value as $value_mails_crm_conversation) {
                                                  array_push($messages_user_crm_folder, $value_mails_crm_conversation);
                                                }
                                        endif;
                                    }
                                endif;
                                //envoyer vers la base de données 
                                $response_var_crm = $this->myoutlook_lib->save_import_mail($messages_user_crm_folder, $mail, FALSE, TRUE);
                                //addition
                                $response_var['importe'] = $response_var['importe'] + $response_var_crm['importe'];
                                $response_var['lier'] = $response_var['lier'] + $response_var_crm['lier'];
                            }

                            //nombre importé 
                            $response[CRMAIL]['importe'] =  $response_var['importe'];
                            $response[CRMAIL]['lier'] = $response_var['lier'];
			endif;
                    endif;
			

                    $msg_value=array();
                    $error_value = array();
                    $i=0;
                    foreach ($response as $key=>$value) {
                            $msg_value[$i] = '<b>'.$key.'</b> : '.$value['importe'].' importé(s) dont '.$value['lier'].' lié(s)';
                            $i++;
                    }

                    foreach ($response_error as $key=>$value) {
                            $error_value[$i] = '<b>'.$key.'</b> : '.$value;
                            $i++;
                    }

                    $params_response = array(
                                    'response_importation_error'=>$error_value,
                                    'response_importation'=>$msg_value
                            );

                    /*$this->session()->set_flashdata('response_importation_error', $error_value);
                    $this->session()->set_flashdata('response_importation', $msg_value);*/


                    //redirect('fh/myoutlook/sync_outlook');
                    echo json_encode($params_response);
                    exit();

		}


		
		public function save_outlook_mail()
	    {
			if($this->request->getVar('id_message') && $this->request->getVar('id_demande')):
				//print_r($this->request->getVar('id_message')); die();
				$id_demande = trim($this->request->getVar('id_demande'));
				$id_message = trim($this->request->getVar('id_message'));
				
				$message_exist = $this->db->select('*')->like('id_primary', $id_message)->get('email_outlook')->result();

				if(!empty($message_exist)):
					$message_isId = $this->db->select('*')->like('id_email', $id_message)->get('email_outlook_lien')->result();

					if(empty($message_isId)):
						$this->db->insert('email_outlook_lien', array(
                                                                                        'id_demande'=>$id_demande,
                                                                                        'id_message'=>$message_exist[0]->internet_message_id,
                                                                                        'id_email'=>$id_message
                                                                                )
                                                                         );
					else :
						$this->db->where('id_email', $id_message);
						$this->db->update('email_outlook_lien', array('id_demande'=>$id_demande));
					endif;
				
					//envoi de notification pour les concernés
					$params['id_demande'] = $id_demande;
					$params['id_message'] = $id_message;
					$response_mail = $this->myoutlook_lib->mails_notification($params);
					
					    $id_type_demande=array(6);
					$this->historique->set_historique_general($id_type_demande,$id_demande,$id_entity_personne=0,$id_entity_bien=0,$id_message);
					echo '<button href="'.base_url().'app/load_demandes/'.$id_demande.'" class="btn btn-xs btn-success edit_email_outlook"><i class="fa fa-link"></i></button>&nbsp;
						  <button href="'.base_url().'app/load_demandes/" class="btn btn-xs btn-info edit_email_outlook"><i class="fa fa-pencil"></i></button>&nbsp;
						  <button href="#" class="btn btn-danger btn-xs email_outlook_delete"><i class="fa fa-trash"></i><i class="fa fa-link"></i></button>';

				endif;
			endif;
		}

		public function delete_depot($id_depot=0){
			if($id_depot>0){

				$depot = $this->db->select('id')->where('id', $id_depot)->where('display', 1)->get('email_demande_depots')->result();

				if(!empty($depot)){
					//display = 0 
					$this->db->where('id', $id_depot);
					$this->db->update('email_demande_depots', array('display'=>0)); 

					echo json_encode(array('id'=>true));
					exit();
				}else{
					echo json_encode(array('id'=>false));
					exit();
				}

				
			}else{
				echo json_encode(array('id'=>false));
				exit();
			}
		}


	    public function changement_lecture($statut,$id_message)
		{
			
			//receuper
			$message=$this->outlookModel->read_data("email_outlook","lus","id_primary=$id_message");
			$end=array();
			if(!empty($message[0]->lus)):
				$lu_liste=$message[0]->lus;
			else:
				$lu_liste=NULL;		
			endif;		
			
			if($statut==0):
				if(is_null($lu_liste)):
					$data["lus"]="";
				else:
					$lua=explode(",",$lu_liste);
				
					foreach($lua as $l):
						if(session("loggedUserId")!=$l):
							array_push($end,$l);
						endif;
					endforeach;	
					if(!empty($end)):	
						$dlus=implode(",",$end);
						$data["lus"]=$dlus;
					else:
						$data["lus"]="";
					endif;
					
				endif;
			else:
				$data["lus"]=session("loggedUserId").",$lu_liste";
			endif;


			$db = db_connect();

			$builder=$this->db->table("email_outlook");
			$builder->where("id_primary",$id_message);
			$builder->update($data);

			$message=$this->outlookModel->read_data("email_outlook","*","id_primary=$id_message");

			if(!empty($message))
			{
				echo view("Outlook\Views/statut_lu",[
					"email"=>$message[0]
				]);
			}
			else
			{
				echo '<div class="text-danger text-center">Erreur! Pas de message trouvé</div>';
			}
			
		}


		public function set_message_demande()
		{
			$post=$this->request->getVar();

			$data["created_datetime"]=date("Y-m-d H:is:s");
			$data["last_modified_datetime"]=date("Y-m-d H:i:s");
			$data["send_datetime"]=date("Y-m-d H:i:s");
			$data["subject"]=$post["subject"];
			$body=$this->treat_image_body_content($post["body_content"]);
			$data["body_preview"]=$body;
			$data["body_content"]=$body;
			$data["sender_mail"]=CRMAIL;
			$data["to_mail"]=$post["to_mail"];
			$data["cc_mail"]=$post["cc_mail"];
			$data["bcc_mail"]=$post["cc_mail"];

			if(!isset($post["id_document"]))
					$post["id_document"]=NULL;

			$this->outlookModel->set_message_demande($data,$post["id_document"],$post["id_demande"]);
		}

		
	   
		public function treat_image_body_content($body_content)
		{
			return $body_content;
		}
		
		public function message_view($id_message)
		{
			
			$this->datas->message=$this->outlookModel->message_view($id_message);;
			$this->datas->documents=$this->outlookModel->get_fichier_joins($id_message);

			//recuperer id_demande potentiel
			$db=db_connect();
			$id_demande=0;
			$builder=$db->table("email_outlook_lien");
			$builder->where("id_email",$id_message);
			$demande=$builder->get()->getRow();

			if(isset($demande->id_demande))
			{
				$id_demande=$demande->id_demande;
			}

			$this->datas->id_demande=$id_demande;
			
			$request = \Config\Services::request();

			if ($request->isAJAX())
			{
				$result["html"]= view( 'Outlook\message_view', (array) $this->datas);

				echo json_encode($result);
				flush();
				
				
			}
			else
			{
				return view( 'Outlook\message_view', (array) $this->datas);
			}
			
		}
	
	}
?>
