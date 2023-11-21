<?php

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;
use Config\Autoload;

class JsonLibrary extends BaseLibrary
{
    public function __construct($namespace)
    {
        parent::__construct($namespace);

        $module = explode('\\', $namespace)[0];
        $AutoloadConfig = new Autoload();
        $psr4 = (object) $AutoloadConfig->psr4;
        $path = $psr4->$module;
        $this->jsonDir = $path . '/Config/Json';
    }

    public function getTable($table)
    {
        $file = $this->jsonDir . '/' . $table . '/table.json';

        return json_decode(file_get_contents($file));
    }

    public function getForm($table)
    {
        $file = $this->jsonDir . '/' . $table . '/form.json';

        return json_decode(file_get_contents($file));
    }

    public function getLists()
    {
        $file = $this->jsonDir . '/list.json';

        return json_decode(file_get_contents($file));
    }
}