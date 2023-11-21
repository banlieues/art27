<?php
	
        namespace Outlook\Libraries;

use Custom\Config\Globals;

	class Tr_outlook{

		var $client_id = '4c16fa81-1b23-4229-a059-613124048ad8'; //id app sync outlook
		var $client_secret = 'fwwaTXD7#!%;cioPOAX0581'; //client secret app sync outlook
		var $scope = 'https%3A%2F%2Fgraph.microsoft.com%2F.default';
		var $grant_type = 'client_credentials';
		var $tenant = '502b669e-e4b4-467c-bd0a-c212968e6edb';	//pass partout
		var $graph = 'https://graph.microsoft.com/v1.0/';


		public function __construct(){}

                public function update_event_calendar($rdv, $request='add')
                {
                        $config = new Globals();
                        $url_permise = in_array(session('loggedUserId'), $config->webmasters) ? [$config->prod_url, $config->prod_url . '/', $config->dev_url, $config->dev_url . '/', base_url(), base_url() . '/'] : [$config->prod_url, $config->prod_url . '/'];

                        if(in_array(base_url(),$url_permise))
                        {
                                $token = $this->getAccessToken(); //access token
                                if($request=='update'): $re="/".$rdv['id_rdv_outlook']; $params['patch'] = TRUE; else : $re=""; endif;
                                $url=$this->graph.'users/'.$rdv['email'].'/events'.$re;
                                $params['url'] = $url;
                                $json=json_encode($rdv['event']);
                                $params['post']=$json;
                                $params['header'] = array(
                                                        'Content-Type: application/json', 
                                                        'Content-Length: ' . strlen($json),
                                                        'Authorization: Bearer '.urlencode($token),
                                                        'Host: graph.microsoft.com'
                                                        );
                                //envoi de params via curl
                                
                                $response = $this->curl($params);
                                
                                if(isset($response->error)){
                                        return False;
                                }else{
                                        return $response;
                                }  
                        }
                        else
                        {
                                return FALSE;

                        }

                }
                
                public function delete_event_calendar($email, $id_rdv_outlook){
                        $url_permise=["https://crm.homegrade.banlieues.be","https://crm.homegrade.banlieues.be/"];
                        if(in_array(base_url(),$url_permise))
                        {
                                $token = $this->getAccessToken(); //access token
                                $params['url']=$this->graph.'users/'.$email.'/events/'.$id_rdv_outlook;
                                $params['delete'] = TRUE;
                                $params['header'] =  array('Authorization: Bearer '.$token);

                                //envoi de params via curl
                                $response = $this->curl($params);
                                //print_r($response);
                                if(isset($response->error)){
                                        return FALSE;
                                }else{
                                        return TRUE;
                                } 
                        } 
                        else
                        {
                                return FALSE;

                        }

                        
                }
                
		public function get_events($email="", $id_event="", $where=array()){
                    if(!empty($email)){
                        $token = $this->getAccessToken(); //access token
                        $whereS="";
                        if(!empty($where)):
                            foreach($where as $key=>$value):
                                if(!empty($whereS)): $whereS.='&'; endif;
                                $whereS.=$key.'='.$value;
                            endforeach;
                        endif;

                        if(empty($id_event)): $url = $this->graph.'users/'.$email.'/events?'.$whereS.'&$select=subject,body,bodyPreview,organizer,attendees,start,end,location';
                        else: $url = $this->graph.'users/'.$email.'/events/'.$id_event.'?'.$whereS.'&$select=subject,body,bodyPreview,organizer,attendees,start,end,location'; endif;
                        //print_r($url); die();
                        $params['url'] = $url;
                        $params['header'] = array(
                                        'Content-Type: application/json;charset=utf-8', 
                                        'Authorization: Bearer '.urlencode($token),
                                        'Host: graph.microsoft.com'
                                );

                        //envoi de params via curl
                        $response = $this->curl($params);

                        if(isset($response->error)){
                                return False;
                        }else{
                                return $response;
                        }
                    }else{
                            return FALSE;
                    }
		}
                
                public function get_calendar_view($email="",$where=array()){
                    if(!empty($email)){
                        $token = $this->getAccessToken(); //access token
                        $whereS="";
                        if(!empty($where)):
                            foreach($where as $key=>$value):
                                if(!empty($whereS)): $whereS.='&'; endif;
                                $whereS.=$key.'='.$value;
                            endforeach;
                        endif;

                        $url = $this->graph.'users/'.$email.'/calendarview?'.$whereS.'&$top=3000&$select=subject,body,bodyPreview,organizer,attendees,start,end,location,sensitivity';
                       // print_r($url); die();
                        $params['url'] = $url;
                        $params['header'] = array(
                                        'Content-Type: application/json;charset=utf-8', 
                                        'Authorization: Bearer '.urlencode($token),
                                        'Host: graph.microsoft.com'
                                );

                        //envoi de params via curl
                        $response = $this->curl($params);
                        //print_r($response);
                        if(isset($response->error)){
                                return False;
                        }else{
                                return $response;
                        }
                    }else{
                            return FALSE;
                    }
                }
                
        

		//return les messages d'un dossier
		public function get_messages_folder($idforlder="", $email=""){
		
			if(!empty($idforlder) && !empty($email)):
					$token = $this->getAccessToken(); //access token
					$params['url'] = $this->graph.'users/'.$email.'/mailFolders/'.$idforlder.'/messages';
					$params['header'] = array(
										'Content-Type: application/json;charset=utf-8', 
										'Authorization: Bearer '.urlencode($token),
										'Host: graph.microsoft.com'
									);

					//envoi de params via curl
					$response = $this->curl($params);

					if(isset($response->error)){
						return FALSE;
					}else{
						return $response;
					}
			else : 
				return FALSE;
				
			endif;


		}

		public function get_messages_conversation($id_conversation, $email){
			$token = $this->getAccessToken(); //access token
			/*$params['url']= $this->graph.'users/'.$email.'/messages?$filter=conversationid eq ';
			$params['url'].= $params['url']."'".$id_conversation."'";
			$params['url']=urlencode($params['url']);*/
			$params['url'] = 'https://graph.microsoft.com/v1.0/users/'.$email.'/messages?filter=conversationId+eq+';
			$params['url'].= "'".$id_conversation."'";
			//$params['url'] = urlencode($params['url']);
		
                        $params['header'] = array(
                                                'Content-Type: application/json;charset=utf-8', 
                                                'Authorization: Bearer '.urlencode($token),
                                                'Host: graph.microsoft.com'
                                            );
                        
			//envoi de params via curl
			$response = $this->curl($params);
			if(isset($response->error)){
                            return FALSE;
			}else{
                            return $response;
			}
		}

		public function get_attachments($id="", $email=""){
				$token = $this->getAccessToken(); //access token
				$params = array();
				$params['url'] = $this->graph.'users/'.$email.'/messages/'.$id.'/attachments';
				$params['header'] =  array('Content-Type: application/json;charset=utf-8', 'Authorization: Bearer '.$token);

				//envoi de params via curl
				$response = $this->curl($params);

				return $response;
		} 

		public function delete_message($id, $email){
			$token = $this->getAccessToken(); //access token
			$params['url'] = $this->graph.'users/'.$email.'/messages/'.$id;
			$params['delete'] = TRUE;
			$params['header'] =  array('Authorization: Bearer '.$token);

			$this->curl($params);

			return TRUE;
		}

		public function move_message($id, $email, $destination){
			//echo 'toto';
			$token = $this->getAccessToken(); //access token
			$params['url'] = $this->graph.'users/'.$email.'/messages/'.$id.'/move';
			$params['post'] = json_encode(array('destinationId'=>$destination));
			$params['header'] = array('Content-Type: application/json', 'Authorization: Bearer '.urlencode($token),'Host: graph.microsoft.com');

			//print_r($params); die();
			$response = $this->curl($params);
			return TRUE;
		}

		public function get_folders($email=""){
			$token = $this->getAccessToken(); //access token
			//$params['url']= $this->graph."/users/".$email."/mailFolders";
			$params['url']= $this->graph.'/users/'.$email.'/mailFolders?&$top=100';
			$params['header'] = array(
									'Content-Type: application/json;charset=utf-8', 
									'Authorization: Bearer '.urlencode($token),
									'Host: graph.microsoft.com'
								);

				//envoi de params via curl
				$response = $this->curl($params);
				return $response;
		}
		
		public function create_folder($email="", $name="crm"){
			$token = $this->getAccessToken(); //access token
			$params['url']= $this->graph."/users/".$email."/mailFolders";
			$params['post'] = json_encode(array('displayName'=>$name));
			$params['header']=array( 
									'Authorization: Bearer '.$token,
               						'Content-Type'  => 'application/json',
									'Content-Length' => strlen($params['post'])
								);

			//envoi de params via curl
			$response = $this->curl($params);

			if(isset($response->error) ):
				return FALSE;
			else :
				return $response;
			endif;
		}


		//return true si l'email depend du {tenant} sinon false
		public function in_users_outlook($user=""){
			if(!empty($user)):
				//user est soit un id ou un email
				$params['url'] =  $this->graph."users/".$user;
				$token = $this->getAccessToken(); //access token
				$params['header'] = array(
										'Content-Type: application/json;charset=utf-8', 
										'Authorization: Bearer '.$token,
										'Host: graph.microsoft.com'
									);

				//envoi de params via curl
				$response = $this->curl($params);
				if(isset($response->error)):
					return FALSE;
				else :
						return TRUE;
				endif;
			else : 
				return FALSE;
			endif;
		}		

		private function getAccessToken(){
			$params['url'] = 'https://login.microsoftonline.com/'.$this->tenant.'/oauth2/v2.0/token';
			$params['header'] = array('Content-Type: application/x-www-form-urlencoded');
			$params['post'] = "client_id=".$this->client_id."&scope=".$this->scope."&client_secret=".urlencode($this->client_secret)."&grant_type=".$this->grant_type;

			//envoi de params via curl
			$response = $this->curl($params);
			return $response->access_token;
		}


		private function curl($params=array()){
                    // Initiate curl
                    if(isset($params['url'])){
                        $ch = curl_init();

                        if(isset($params['post'])) : 
                                //curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($ch, CURLOPT_POSTFIELDS,$params['post']);
                        endif;

                        //HTTPHEADER
                        if(isset($params['header'])):
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $params['header']);
                        endif;

                        //SSL
                        if(isset($params['ssl']) && $params['ssl'] == TRUE):
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                        else : 
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        endif;

                        //DELETE
                        if(isset($params['delete']) && $params['delete']==TRUE){
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                        }
                        
                        //PATH
                        if(isset($params['patch']) && $params['patch']==TRUE){
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                        }

                        // Will return the response, if false it print the response
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        // Set the url
                        curl_setopt($ch, CURLOPT_URL,$params['url']);
                        // Execute
                        $response=curl_exec($ch);
                        // Closing
                        curl_close($ch);

                        return json_decode($response);

                    }else {
                        return FALSE;
                    }
		}

	}
?>