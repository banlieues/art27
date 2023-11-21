<?php

namespace Custom\Controllers;

use Base\Controllers\BaseController;

class Root extends BaseController 
{
    public function index()
    {
        if(session('loggedUserRoleId')==1) :

            return redirect()->to('dashboard');

        else :

            return redirect()->to('identification/logout');

        endif;
    }
}
