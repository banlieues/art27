<?php

function full_url()
{
    $currentURL = current_url(); 

    $params   = $_SERVER['QUERY_STRING'];

    if(!empty($params))
        return $currentURL . '?' . $params; 

    return $currentURL;
}

