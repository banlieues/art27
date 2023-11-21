<?php

namespace App\Controllers;

use App\Libraries\api\api_365\Api_365_profil;
use App\Libraries\api\api_365\Api_365_one_drive;
use App\Libraries\api\api_365\Api_365_outlook;



class TestApi extends BaseController
{
    //Informations Philippe
    protected $uuid="a6c56681-3fa7-4ea1-856e-166ee00548c8";
    protected $mail="philippe.lemaire@rhizome-tv.com";

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")!=1)
       {
            header("Location:".base_url("identification/logout"));
       }
        $this->api_365_profil= new Api_365_profil();
        $this->api_365_outlook= new Api_365_outlook();

    }

    public function index()
    {
        $params["user_id"]=$this->mail;
        $response=$this->api_365_outlook->get_mail_user($params);

		debug($response);
    }
} 