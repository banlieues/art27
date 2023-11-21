<?php

namespace Doublon\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('doublon', ['namespace' => 'Doublon\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'Doublon::index');
    $routes->get('index', 'Doublon::index');


    $routes->match(["get", "post"], "search_by_field/(:any)", "Doublon::search_by_field/$1");
    $routes->match(["get", "post"], "search_by_id/(:any)", "Doublon::search_by_id/$1");

    $routes->match(["get", "post"], "get_tableau_fusion/(:any)", "Doublon::get_tableau_fusion/$1");

    $routes->match(["get", "post"], "fusion/(:any)", "Doublon::fusion/$1");

});


