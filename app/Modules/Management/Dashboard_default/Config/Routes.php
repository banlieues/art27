<?php
namespace Dashboard_default\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('dashboard_default', ['namespace' => 'Dashboard_default\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'Dashboard_default::index');
    $routes->get('index', 'Dashboard_default::index');

    

});


