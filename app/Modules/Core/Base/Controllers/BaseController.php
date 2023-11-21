<?php

namespace Base\Controllers;

use Autorisation\Libraries\AutorisationLibrary;
use App\Controllers\BaseController as CI_Controller;
use Base\Libraries\InitLibrary;
use Layout\Libraries\LayoutLibrary;

class BaseController extends CI_Controller 
{
    public function __construct($namespace=null)
    {
        $this->SetGlobals($namespace);
        // $this->DatabaseMigration();

        $this->db = db_connect();

        $curl_option = [];
        if(!in_array(base_url(), [$this->prod_url, "$this->prod_url/", $this->dev_url, "$this->dev_url/"])) :
            $curl_option['verify'] = false;
        endif;
        $this->curl = \Config\Services::curlrequest($curl_option);

        $this->request = \Config\Services::request();
        // $this->validation = \Config\Services::validation();
        $this->session = session();
        
        // $this->transl_l = new TranslatorLibrary();
        // $this->transl_l->check_database__translator();

        // $this->migrate = \Config\Services::migrations();
        // $this->migrate->setNamespace($module)->latest();
        if(!empty(session('loggedUserLocale'))) $this->request->setLocale(session('loggedUserLocale'));

        
        // $DataView = new DataViewConstructor();      

        // $this->DataView = $DataView;
        // $this->UserModel = new UserModel();
        $this->Autorisation = new AutorisationLibrary();
        $this->themes = $this->SetTheme();
        // $this->fields = $DataView->getFields();
        $this->id_user = $this->request->getGet('id_user') ?? session('loggedUserId');
        $this->user = sessionUser($this->id_user);

        $this->datas = $this->SetDatas($namespace);
    }

    private function SetTheme()
    {
        $LayoutLibrary = new LayoutLibrary();
        $data = $LayoutLibrary->getThemes();

        return $data;
    }

    private function SetDatas($namespace=null)
    {
        $data = (object) [];
        // $data->dataView = $DataView;
        // $data->fields = $this->fields;
        $data->Autorisation = $this->Autorisation;
        $data->dev_url = $this->dev_url;
        $data->get = $this->GetUrlParam();
        $data->id_user = $this->id_user;
        $data->id_user_get = $this->request->getGet('id_user') ? 'id_user=' . $this->request->getGet('id_user') : '';
        $data->itemSearch = (explode('?', current_url())[0]==explode('?', previous_url())[0]) ? $this->request->getGet('itemSearch') : null;
        $data->locale = $this->request->getLocale();
        $data->namespace = $namespace;
        $data->per_page = $this->request->getGet('per_page') ?? 20;
        $data->prod_url = $this->prod_url;
        $data->themes = $this->themes;
        $data->user = $this->user;
        // $data->validation = $this->validation;

        return $data;
    }

    private function SetGlobals($namespace=null)
    {
        $InitLibrary = new InitLibrary();
        $InitLibrary->GetHelpers();
        $globals = $InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;
    }

    private function DatabaseMigration($namespace=null)
    {
        $migration = \Config\Services::migrations();
        $migration->setNamespace($namespace);
        try {
            $migration->latest();
        } catch (Throwable $e) {
            // Do something with the error here...
        }
    }

    private function GetUrlParam()
    {
        $GetUrlParam = '';
        if(!empty($this->request->getGet())) :
            foreach($this->request->getGet() as $key=>$value) :
                if(!is_array($value)) $GetUrlParam .= "$key=$value&";
            endforeach;
        endif;

        return $GetUrlParam;
    }

}
