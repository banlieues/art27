<?php

namespace App\Controllers;

use Base\Controllers\BaseController;

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function index()
    {
        return view('welcome_message');
    }
}
