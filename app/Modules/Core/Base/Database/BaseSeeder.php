<?php

namespace Base\Database;

use Base\Libraries\InitLibrary;
use CodeIgniter\Database\Seeder;
use Components\Libraries\DatabaseLibrary;

class BaseSeeder extends Seeder
{
    public function __construct($namespace)
    {
        $InitLibrary = new InitLibrary();
        $InitLibrary->GetHelpers();
        $globals = $InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;
    }
}