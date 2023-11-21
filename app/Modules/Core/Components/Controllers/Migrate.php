<?php

namespace Components\Controllers;

use CodeIgniter\Controller;
use Custom\Config\Globals;

class Migrate extends Controller
{
    public function __construct()
    {
        $config = new Globals();
        if(!in_array(session('loggedUserId'), $config->superadmins)) return redirect()->to('forbidden');
    }

    public function index()
    {
        $migration = \Config\Services::migrations();
        $migration->setNamespace(null);
        try {
            $migration->latest();
        } catch (Throwable $e) {
            // Do something with the error here...
        }
    }
}