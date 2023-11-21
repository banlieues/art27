<?php

namespace Components\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\ReminderLibrary;

class ReminderController extends BaseController 
{
  	function __construct() 
    {
        parent::__construct();
        $this->ReminderLibrary = new ReminderLibrary();
    }

    public function AccessGetByToken($token, $id_user)
    {
    	if( (is_numeric($id_user)) && (ctype_alnum($token)) ) :
    		$params = (object) [
    			"token_reminder" => $token,
    			"id_user" => $id_user,
            ];
    		return $this->ReminderLibrary->AccessGetByParams($params);
    	else :
    		debugd('Le token renseigné est incorrect ou expiré.');
        endif;
    }
    
}