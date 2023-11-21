<?php                                                                                   

namespace Api\Libraries;

use Base\Libraries\BaseLibrary;

Class BaseApiLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getContentType($url) 
    {
        $response = $this->curl->request('GET', $url);
        $contentType = $response->getHeader('Content-Type');

        return $contentType;
    }

    protected function curl($params=array())
    {
        // Initiate curl
        if(isset($params['url']))
        {
            $ch = curl_init();

            if(isset($params['post']))
            {
                //curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params['post']);
            }

            //HTTPHEADER
            if(isset($params['header']))
            {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $params['header']);
            }

            //USER AGENT
            if(isset($params['userAgent']))
            {
                curl_setopt($ch, CURLOPT_USERAGENT, $params['userAgent']);
            }

            //SSL
            if(isset($params['ssl']) && $params['ssl'] == TRUE)
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            }
            else
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }

            //DELETE
            if(isset($params['delete']) && $params['delete']==TRUE)
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            }
            
            //PATH
            if(isset($params['patch']) && $params['patch']==TRUE)
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            }

            //AUTHORIZATION WITH Username ANd Password (aka Gravity Form)
            if(isset($params['username']) && isset($params['password']))
            {
                curl_setopt($ch, CURLOPT_USERPWD, $params['username'] . ':' . $params['password']);
            }

            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set the url
            curl_setopt($ch, CURLOPT_URL, $params['url']);

            ///sert à suivre une réponse contenue dans un header de type url à executer
            if(isset($params['followlocation']) && $params['followlocation'])
            {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); //don't follow redirects
            }

            // Execute
            $response = curl_exec($ch);

            // Recuperer information header
            if(isset($params['followlocation']) && $params['followlocation'])
            {
                $target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                return $target;
            }

            // Closing
            curl_close($ch);

            return json_decode($response, true);
        }
        else 
        {
            return FALSE;
        }     
    }




}



