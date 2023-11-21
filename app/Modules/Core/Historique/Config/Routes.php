<?php

namespace Historique\Config;

$routes = \Config\Services::routes();

$routes->group('historique', ['namespace' => 'Historique\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    /*$routes->match(['get', 'post'], '/', 'Outlook::liste_message');
    $routes->match(['get', 'post'], 'index', 'Outlook::liste_message');

    $routes->match(['get', 'post'], 'liste_message/(:any)', 'Outlook::liste_message/$1');

    $routes->match(['get', 'post'], 'liste_message/(:any)', 'Outlook::liste_message/$1');*/
    

    $routes->match(['get', 'post'], 'get_historique_demande/(:any)', 'Historique::get_historique_demande/$1');

    $routes->match(['get', 'post'], 'get_logs_fiche/(:any)/(:any)', 'Historique::get_logs_fiche/$1/$2');

    
   

  

});



