<?php

namespace Outlook\Config;

$routes = \Config\Services::routes();

$routes->group('outlook', ['namespace' => 'Outlook\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Outlook::liste_message');
    $routes->match(['get', 'post'], 'index', 'Outlook::liste_message');

    $routes->match(['get', 'post'], 'liste_message/(:any)', 'Outlook::liste_message/$1');

    $routes->match(['get', 'post'], 'move_mailoutlook_db', 'Outlook::move_mailoutlook_db');

    $routes->match(['get', 'post'], 'send_message', 'Outlook::send_message');

    $routes->match(['get', 'post'], 'set_message_demande', 'Outlook::set_message_demande');

    $routes->match(['get', 'post'], 'refresh_nonlus/(:any)', 'Outlook::refresh_nonlus/$1');

    $routes->match(['get', 'post'], 'refresh_nontraites', 'Outlook::refresh_nontraites');

    $routes->match(['get', 'post'], 'changement_lecture/(:any)/(:any)', 'Outlook::changement_lecture/$1/$2');

    $routes->match(['get', 'post'], 'test_envoi_mail', 'Outlook::test_envoi_mail');

    $routes->match(['get', 'post'], 'message_view/(:any)', 'Outlook::message_view/$1');

});


$routes->group('outlook_cron', ['namespace' => 'Outlook\Controllers'], function($routes)
{
    $routes->match(['get', 'post'], 'import_outlook', 'Outlook_cron::import_outlook');
 

});



