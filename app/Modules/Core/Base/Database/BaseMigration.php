<?php

namespace Base\Database;

use Base\Libraries\InitLibrary;
use CodeIgniter\Database\Migration;

class BaseMigration extends Migration
{
    public function __construct($namespace)
    {
        parent::__construct();
        $this->InitLibrary = new InitLibrary();
        $this->InitLibrary->GetHelpers();
        $this->SetGlobals($namespace);
    }

    private function SetGlobals($namespace=null)
    {
        $globals = $this->InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;
    }

    public function up()
    {

    }

    public function down()
    {

    }
}