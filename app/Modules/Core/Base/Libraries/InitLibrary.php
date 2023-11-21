<?php

namespace Base\Libraries;

class InitLibrary
{
    public function GetHelpers()
    {
        helper('form');
        helper('url');

        // helper('Components\cemea');
        helper('Components\calcul');
        helper('Components\colors');
        helper('Components\database');
        helper('Components\date');
        helper('Components\debug');
        helper('Components\email');
        helper('Components\file');
        helper('Components\files');
        helper('Components\fontawesome');
        helper('Components\homegrade');
        helper('Components\icons');
        helper('Components\images');
        helper('Components\object');
        helper('Components\outlook');
        helper('Components\session');
        helper('Components\sql_ban');
        helper('Components\string');
        helper('Components\type');
        helper('Components\url_ban');
        helper('Custom\client');
        helper('Translator\translator');
    }

    public function GetGlobals($namespace=null)
    {
        $data = (object) [];

        $globals = new \Custom\Config\Globals();
        foreach($globals as $key=>$value) $data->$key = $value;

        if(!empty($namespace)) :
            $module = explode('\\', $namespace)[0];
            $global_namespace = '\\' . $module . '\Config\Globals';
            if(class_exists($global_namespace)) :
                $globals_module = new $global_namespace();
                foreach($globals_module as $key=>$value) $data->$key = $value;
            endif;
        endif;

        return $data;
    }
}
