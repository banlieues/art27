<?php

namespace Translator\Config;

$routes = \Config\Services::routes();

$routes->group('', ['namespace' => 'Translator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'language/set/(:segment)', 'Translator::LanguageSet/$1');
});

$routes->group('translator', ['namespace' => 'Translator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'Translator::RowList');
    $routes->match(['get', 'post'], 'row/(:num)', 'Translator::RowView/$1');
    $routes->match(['get', 'post'], 'row/(:num)/delete', 'Translator::RowDelete/$1');
    $routes->match(['get', 'post'], 'row/(:num)/delete/modal', 'Translator::RowDeleteModal/$1');
    $routes->match(['get', 'post'], 'row/(:num)/save', 'Translator::RowSave/$1');
    $routes->match(['get', 'post'], 'row/(:num)/modal', 'Translator::RowViewModal/$1');
});