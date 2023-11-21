<?php
// Colors_Helper

if (!function_exists('colorBorder'))
{
    function colorBorder($name)
    {
        switch ($name)
        {
            case "contacts": 
                return "amethyst";
            case "registrations":
                return "success";
            default:
                return "primary";

        } 
    }

}
