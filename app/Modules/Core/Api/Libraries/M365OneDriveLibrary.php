<?php                                                                                   

/**
 * @description : Library to access API 365 et récupérer données des profils
 */

namespace Api\Libraries;

use Api\Libraries\M365ApiLibrary;

Class M365OneDriveLibrary extends M365ApiLibrary
{

    var $user_id_test= "a86197f5-8d85-47bf-802d-8667c16c3c0b";
    var $folder_id_test="01XHQB4UAGZVPNM35YJ5B3EZ7JRRKK3MLC"; //correspond au repertoire testi
    var $file_id_test="01XHQB4UB3MC54Z6AZTFH2NNHFSEXNQICU";


 

    /**
     * Réupere les metada d'un drive
     * 
     * @API_365 API autorisé: File.Read.All 
     */



    public function get_metadata_drive($user_id=NULL)
    {
        if(is_null($user_id))
            $user_id=$this->user_id_test;

        $token = $this->getAccessToken(); //access token
        $params['url'] =  $this->graph."users/$user_id/drive";
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
     * Fonction qui retoure les fichiers d'un folder
    *   
     * @API_365 API autorisé: File.Read.All 
     * 
     * ATTENTION RETOURNE TOUT ALORS QUE L4ON NE VEUT QUE DES FICHIERS
     * 
     */

    public function get_files_in_folder($user_id=NULL,$folder_id=NULL)
    {   
        if(is_null($user_id))
            $user_id=$this->user_id_test;

        if(is_null($folder_id))
            $folder_id=$this->folder_id_test;


        $token = $this->getAccessToken(); //access token
        $params['url'] =  $this->graph."users/$user_id/drive/items/$folder_id/children";
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
     * Fonction qui retoure les fichiers et les dossiers à partir de la racine
    *   Si pas de folder_id alors folder_id= ROOT, c'est-à-dire la racine du drive
     * @API_365 API autorisé: File.Read.All 
     * 
     * 
     * 
     */
    
    public function get_elements_in_drive($user_id=NULL,$folder_id=NULL)
    {   
        if(is_null($user_id))
            $user_id=$this->user_id_test;

        if(is_null($folder_id))
            $folder_id="root";

          
        $token = $this->getAccessToken(); //access token
        $params['url'] =  $this->graph."users/$user_id/drive/items/$folder_id/children";
        
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
     * Fonction qui retoure les fichiers d'un folder
    *   
     * @API_365 API autorisé: File.Read.All 
     * 
     * 
     * 
     */
    public function create_folder_in_drive($user_id=NULL,$name_folder="salle_tu_me_vois",$folder_id=NULL)
    {   

        //$folder_id=$this->folder_id_test;

        if(is_null($name_folder))
            return ["is_error"=>false,"output"=>"Pas de nom de dossier transmis"];

        if(is_null($user_id))
            $user_id=$this->user_id_test;

        if(is_null($folder_id))
        {
            $folder_id="root";
        }
        else
        {
            $folder_id="items/$folder_id";
        }

        $token = $this->getAccessToken(); //access token

        $params['url'] =  $this->graph."users/$user_id/drive/$folder_id/children";

        $params['post']=json_encode([
                "name"=>$name_folder,
                "folder"=> (object)[],
                //"@microsoft.graph.conflictBehavior"=> "rename"
        ]);
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
     * Récuper le contenu d'un fichier
    *   
     * @API_365 API autorisé: File.Read.All 
     * 
     * 
     * 
     */
    public function get_file_content($user_id=NULL,$file_id=NULL)
    {   

        /**
         * DOCU
         *  https://docs.microsoft.com/en-us/graph/api/driveitem-get-content?view=graph-rest-1.0&tabs=http
         * 
         */
        
       

        $file_id=$this->file_id_test;

        if(is_null($user_id))
            $user_id=$this->user_id_test;

        if(is_null($file_id))
            return ["is_error"=>false,"output"=>"Pas de nom de fichier transmis"];
       

        $token = $this->getAccessToken(); //access token

        $params['url'] =  $this->graph."users/$user_id/drive/items/$file_id/content";

            //die("users/$user_id/drive/items/$file_id/content");

        $params['header'] = array(
                                'Content-Type: application/json;charset=utf-8', 
                                'Authorization: Bearer '.$token,
                                'Host: graph.microsoft.com'
                            );

        $params["followlocation"]=TRUE;                    

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




   
    
    
}