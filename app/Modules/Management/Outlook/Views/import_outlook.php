<div style='text-align:center; margin-top: 150px'>
	<i class='fa fa-spin fa-spinner fa-4x'></i>
	<p>En cours de traitement</p>
</div>

<?php 
if(isset($_SESSION['access_token']) && isset($_SESSION['state'])){
  $token = $_SESSION['access_token'];
  $state = $_SESSION['state'];
}else{
  redirect('fh/myoutlook/auth_outlook/import_outlook');
  exit();
}

require_once FHPATH.'outlook/vendor/autoload.php';

//We store user name, id, and tokens in session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//traitement
//$url = 'https://graph.microsoft.com/v1.0/me/messages'; //affiche tous les messages
//$url = 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages'.$filter_var; //Affiche tous les messages de la boite de reception
//$url = 'https://graph.microsoft.com/v1.0/me/mailFolders/DeletedItems/messages'; //Affiche tous les message à partir du dossier CRM
$url = 'https://graph.microsoft.com/v1.0/me/mailFolders/AAMkADcyZTI0Y2RiLWY3M2ItNDBlNi1iOTVjLTQ4NDliZTg3MzA2YgAuAAAAAADTwe3XSzxUT5kvUE_d7j4kAQDMZDxJfIBVRotvg_vSKyPnAAAF6ATMAAA=/messages';
    
// Initiate curl
$ch = curl_init();

$authorization = "Authorization: Bearer $token";
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8', $authorization));
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);

// Closing
curl_close($ch);

$messages = json_decode($result);


if(!empty($messages)):

	if(isset($messages->error)){
	    $error = $messages->error;
	    if(isset($error->code) && $error->code == 'InvalidAuthenticationToken'){
			redirect('fh/myoutlook/auth_outlook/import_outlook');
			exit();
		}else{
			//print_r($error_code);
			$this->session->set_flashdata('response_error', 'Erreur code outlook : '.$error_code);
		}
	}else{
		//insertion 
	    $messages = $messages->value;
	    //probleme de token ?
	    
	    /*echo '<pre>';
	    print_r($messages); 
	    echo '</pre>';
	    die();*/
	    $messages_import = 0;
	    $messages_lier = 0;
	    foreach ($messages as $message) {
	    	$data=array();
	      	$data = array(
		      	//'odata_etag'=> $message->@odata.etag,
		      	'id'=> trim($message->id),
		      	'created_datetime'=> $message->createdDateTime,
		      	'last_modified_datetime'=> $message->lastModifiedDateTime,
		      	'change_key'=> $message->changeKey,
		      	'received_datetime'=> $message->receivedDateTime,
		      	'send_datetime'=> $message->sentDateTime,
		      	'internet_message_id'=> $message->internetMessageId,
		      	'subject'=> $message->subject,
		      	'body_preview'=> $message->bodyPreview,
		      	'importance'=> $message->importance,
		      	'parent_folder_id'=> $message->parentFolderId,
		      	'conversation_id'=> $message->conversationId,
		      	'body_type'=> $message->body->contentType,
		      	'body_content'=> $message->body->content, 
		      	'sender_mail'=> $message->from->emailAddress->address,
		      	'send_name'=> $message->from->emailAddress->name
		      );

	      
      
      		$message_isId = $this->db->select('*')->where('internet_message_id', $data['internet_message_id'])->get('email_outlook')->result();

	      	if(empty($message_isId)):
		      if($this->db->insert('email_outlook', $data)):

				$insert_id = $this->db->insert_id();
		      	$messages_import+=1;

		      	if($message->hasAttachments==1){
	      			$url_attach = 'https://graph.microsoft.com/v1.0/me/messages/'.$data['id'].'/attachments';
					// Initiate curl
					$ch_attach = curl_init();
					$authorization_attach = "Authorization: Bearer $token";
					curl_setopt($ch_attach, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8', $authorization_attach));
					// Disable SSL verification
					curl_setopt($ch_attach, CURLOPT_SSL_VERIFYPEER, false);
					// Will return the response, if false it print the response
					curl_setopt($ch_attach, CURLOPT_RETURNTRANSFER, true);
					// Set the url
					curl_setopt($ch_attach, CURLOPT_URL,$url_attach);
					// Execute
					$result_attach=curl_exec($ch_attach);

					// Closing
					curl_close($ch_attach);

					$attachments = json_decode($result_attach);
					
					foreach ($attachments->value as $attach) {
						//echo '<pre>'; echo base64_encode($attach->contentBytes);  echo '</pre>';
						$data_attach = array(
							"name"=> $attach->name ,
							"contentByte"=> $attach->contentBytes,
							"contentByte_Type"=>$attach->contentType,
							"id_message"=> $insert_id
						);
						$this->db->insert('email_demande_depots', $data_attach);
					}

					//echo '<pre>'; print_r($attachments); echo '</pre>';
					//die();
				}

				
				$objet = $message->subject;
				$marqueurDebutLien = "[CASE:";
				$debutLien = strpos( $objet, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
				$marqueurFinLien = "]";
				$finLien = strpos( $objet, $marqueurFinLien );
				$id_demande = substr( $objet, $debutLien, $finLien - $debutLien );
				$id_demande = trim($id_demande);
				
				if(!empty($id_demande)):
				$demande = $this->db->select('*')->where('id_demande', $id_demande)->get('demande')->result();
				//print_r($id_demande); print_r($demande); die();
				if(!empty($demande)){
					if($this->db->insert('email_outlook_lien', array('id_demande'=>$id_demande,'id_email'=>$insert_id))){
						$messages_lier+=1;	
					}		
				}
				endif;
				
		      	//archivé dans outlook
		      	/*$url_archive = 'https://outlook.office.com/api/v1.0/me/messages/'.trim($message->id).'/move';
		      	// Initiate curl
				$ch = curl_init();
				$authorization = "Authorization: Bearer $token";
				$vars = "DestinationId : Inbox";
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8', $authorization, $vars ));
				// Disable SSL verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($ch, CURLOPT_URL,$url_archive);
				// Execute
				$result_archive = curl_exec($ch);
				// Closing data:image/jpeg;base64,/
				curl_close($ch);

				//print_r($result_archive); die();*/
		      endif;
	      	endif;

      		//response ?

      }

      	$message_success = '';
  		if($messages_import==0){
  			$message_success .= $messages_import." nouveau email trouvé sur outlook";
  		}else if($messages_import<2){
  			$message_success .= $messages_import." email importé via outlook";
  		}else {
  			$message_success .= $messages_import." emails importés via outlook";
  		}
		
		if($messages_lier>0){
			$message_success .= "<br> Et ".$messages_lier." lié(s) automatiquement.";
		}

  		$this->session->set_flashdata('response_success', $message_success);

	}

else : 
	$this->session->set_flashdata('response_error', 'Aucun resultat !');
endif;

redirect('fh/myoutlook/sync_outlook');
    
?>