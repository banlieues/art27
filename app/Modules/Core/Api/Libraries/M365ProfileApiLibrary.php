<?php                                                                                   

/**
 * @description : Library to access API 365 et récupérer données des profils
 */

namespace Api\Libraries;

use Api\Libraries\M365ApiLibrary;

Class M365ProfileApiLibrary extends M365ApiLibrary
{

     /**
     * Fonction qui retoure les données tous les profils d'un tenant
    *   
     * @API_365 API autorisé: User.Read.All (= permet d'obtenir le profil de tous les users) 
     */
    public function get_profil_all_users_tenant()
    {
        $token = $this->getAccessToken(); //access token
        $params['url'] =  $this->graph."users";
        $params['header'] = array(
                                'Content-Type: application/json;charset=utf-8', 
                                'Authorization: Bearer '.$token,
                                'Host: graph.microsoft.com'
                            );

        $response = $this->curl($params);

        if(isset($response->error))
        {
            return ["is_error"=>TRUE,"output"=>$response->error];
        }
        else
        {
            return ["is_error"=>false,"output"=>$response];
        }
    }

    /**
     * Fonction qui retourne les données du profil d'un user d'un teant
     * 
     * @$params["user_mail"] //Le mail présent sur le tenant
     * @$params["user_id"] //id de l'utilisateur sur le tenant
     * 
     * @API_365 API autorisé: User.Read.All (=permet d'obtenir le profil de tous les users)
     * 
     */
    
    public function get_profil_user_tenant($params=NULL)
    {
        //Traitement des params, 
        $user_mail= (isset($params["user_mail"])) ? $params["user_mail"] : NULL;
        $user_id= (isset($params["user_id"])) ? $params["user_id"] : NULL;

        //On accorde la priorité à user_id si user_id n'existe pas
        $user_search= (!is_null($user_id)) ? $user_id : $user_mail;

        if(!is_null($user_search))
        {
            $token = $this->getAccessToken();
            $params['url'] =  $this->graph."users/$user_search";
            $params['header'] = array(
                                    'Content-Type: application/json;charset=utf-8', 
                                    'Authorization: Bearer '.$token,
                                    'Host: graph.microsoft.com'
                                );
            $response = $this->curl($params);

            if(isset($response->error))
            {
                return ["is_error"=>TRUE,"output"=>$response->error];
            }
            else
            {
                return ["is_error"=>false,"output"=>$response];
            }
        }
        else
        {
            return ["is_error"=>TRUE,"output"=>"Paramètres manquants"];
        }
    }
}