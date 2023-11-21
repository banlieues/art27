<?php

namespace Base\Libraries;

class BaseLibrary
{
    public function __construct($namespace=null)
    {
        $InitLibrary = new InitLibrary();
        $InitLibrary->GetHelpers();
        $globals = $InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;

        $this->db = db_connect();

        $curl_option = [];
        if(!in_array(base_url(), [$this->prod_url, "$this->prod_url/", $this->dev_url, "$this->dev_url/"])) :
            $curl_option['verify'] = false;
        endif;
        $this->curl = \Config\Services::curlrequest($curl_option);
        
        $this->request = \Config\Services::request();
        $this->session = session();

        $this->datas = (object) [];
    }
}
