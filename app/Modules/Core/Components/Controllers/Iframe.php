<?php

namespace Components\Controllers;

use App\Controllers\BaseController;

class Iframe extends BaseController
{
    protected $context;

    public function __construct()
    {
        $this->context = "Loading";
    }

    public function index()
    {
        return view('Layout\loading_iframe');
    }

}