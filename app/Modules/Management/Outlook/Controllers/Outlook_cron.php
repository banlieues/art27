<?php 

	namespace Outlook\Controllers;

	use Base\Controllers\BaseController;


	use Outlook\Libraries\Tr_outlook;
	use Outlook\Libraries\Myoutlook_lib;

	use Outlook\Models\OutlookModel;



	class Outlook_cron extends BaseController {
		

		public function __construct()
		{
			
        	parent::__construct(__NAMESPACE__);
			

			$this->myoutlook_lib=new Myoutlook_lib();
			$this->tr_outlook=new Tr_outlook();

			$this->outlookModel=new OutlookModel;


		}


		public function import_outlook()
		{ //importation outlook
		
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
				//debugd($messages_inbox);
				$this->myoutlook_lib->save_import_mail($messages_inbox, $mail, TRUE);

				//verif l'existance de l'ID CRM ds mail$
				if(!empty($id_crm)){
					$messages_user_crm_folder=array();
					if($this->tr_outlook->get_messages_folder($id_crm, $mail)){
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
						$this->myoutlook_lib->save_import_mail($messages_user_crm_folder,$mail, FALSE);
					}
				}
			endif;

			print_r('success');	

		}
}