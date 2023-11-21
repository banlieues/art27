<?php

namespace Rdv\Config;

$routes = \Config\Services::routes();

$routes->group('rdv', ['namespace' => 'Rdv\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Rdv::liste_rdv');
    $routes->match(['get', 'post'], 'index', 'Rdv::liste_rdv');

    $routes->get('list_rdv', 'Rdv::liste_rdv');

    $routes->match(['get', 'post'], 'fiche_rdv/(:num)', 'Rdv::fiche_rdv/$1');

    $routes->match(['get', 'post'], 'get_calendar', 'Rdv::get_calendar');

    $routes->match(['get', 'post'], 'form_rdv', 'Rdv::form_rdv');
    $routes->match(['get', 'post'], 'form_rdv/(:num)', 'Rdv::form_rdv/$1');
    $routes->match(['get', 'post'], 'form_rdv/(:num)/(:num)', 'Rdv::form_rdv/$1/$2');

    $routes->match(['get', 'post'], 'set_rdv', 'Rdv::set_rdv');


    $routes->match(['get', 'post'], 'setType', 'Rdv::setType');
    $routes->match(['get', 'post'], 'setStatut', 'Rdv::setStatut');
    $routes->match(['get', 'post'], 'setCommentaire', 'Rdv::setCommentaire');
    
    $routes->match(['get', 'post'], 'deleteRdv', 'Rdv::deleteRdv');


});



