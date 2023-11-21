<?php
/**
 * This is Tutorials Module Routes 
**/

namespace Tutorials\Config;

$routes = \Config\Services::routes();

$routes->group('tutorials', ['namespace' => 'Tutorials\Controllers'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->get('/', 'Tutorials::index');
    $routes->get('index', 'Tutorials::index');
    $routes->get('ci4', 'Tutorials::ci4');
    $routes->get('bs5', 'Tutorials::bs5');
});
