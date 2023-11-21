<?php

namespace Base\Config;

use Base\Libraries\InitLibrary;

class BaseValidation
{
    public function __construct($namespace)
    {
        $InitLibrary = new InitLibrary();
        $InitLibrary->GetHelpers();
        $globals = $InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;
    }
}