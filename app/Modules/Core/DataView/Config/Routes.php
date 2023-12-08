<?php

namespace DataView\Config;

$routes = \Config\Services::routes();

$routes->group('dataview', ['namespace' => 'DataView\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'ajout_multiple', 'DataView::ajout_multiple');

    
});


