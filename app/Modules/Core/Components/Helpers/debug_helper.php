<?php
// Debug_Helper

if (!function_exists('debug'))
{
    function debug($var, $is_die = FALSE)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        if ($is_die) {die();}
    }
}

if (!function_exists('debugd'))
{
    function debugd($var=null)
    {
        if($var) :
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        endif;
        die();
    }
}

if (!function_exists('dbdebug'))
{
    function dbdebug()
    {
        $db = db_connect();

        echo '<pre>';
        print_r((string) $db->getLastQuery());
        echo '</pre>';
    }
}

if (!function_exists('dbdebugd'))
{
    function dbdebugd()
    {
        $db = db_connect();

        echo '<pre>';
        print_r((string) $db->getLastQuery());
        echo '</pre>';

        die();
    }
}

