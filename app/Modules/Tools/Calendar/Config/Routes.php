<?php

namespace Calendar\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('calendar', ['namespace' => 'Calendar\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(["get", "post"], '/', 'Calendar::index');
    $routes->match(["get", "post"], 'delete','Calendar::DeleteEvent');
    $routes->match(["get", "post"], 'delete/(:any)','Calendar::DeleteEvent/$1');
    $routes->match(["get", "post"], 'edit/(:any)','Calendar::EditEvent/$1');
    $routes->match(["get", "post"], 'new','Calendar::CreateEvent');
});


