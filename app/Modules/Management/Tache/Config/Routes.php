<?php

namespace Tache\Config;

$routes = \Config\Services::routes();

$routes->group('tache', ['namespace' => 'Tache\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Tache::liste_tache');
    $routes->match(['get', 'post'], 'index', 'Tache::liste_tache');

    $routes->get('list_tache', 'Tache::liste_tache');

    $routes->match(['get', 'post'], 'fiche_tache/(:num)', 'Tache::fiche_tache/$1');

    $routes->match(['get', 'post'], 'get_calendar', 'Tache::get_calendar');

    $routes->match(['get', 'post'], 'form_tache', 'Tache::form_tache');
    $routes->match(['get', 'post'], 'form_tache/(:num)', 'Tache::form_tache/$1');
    $routes->match(['get', 'post'], 'form_tache/(:num)/(:num)', 'Tache::form_tache/$1/$2');

    $routes->match(['get', 'post'], 'set_tache', 'Tache::set_tache');


    $routes->match(['get', 'post'], 'setType', 'Tache::setType');
    $routes->match(['get', 'post'], 'setStatut', 'Tache::setStatut');
    $routes->match(['get', 'post'], 'setCommentaire', 'Tache::setCommentaire');


});



