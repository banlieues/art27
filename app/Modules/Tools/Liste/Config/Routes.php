<?php

namespace Liste\Config;

$routes = \Config\Services::routes();

$routes->group('liste', ['namespace' => 'Liste\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Liste::TableList');
    $routes->match(['get', 'post'], 'table', 'Liste::TableList');
    $routes->match(['get', 'post'], 'table/(:segment)/modal', 'Liste::TableModal/$1');
    $routes->match(['get', 'post'], 'table/(:segment)', 'Liste::TableView/$1');
    $routes->match(['get', 'post'], 'table/(:segment)/rank/update', 'Liste::TableRankUpdate/$1');
    $routes->match(['get', 'post'], 'table/(:segment)/row/new', 'Liste::RowNew/$1');
    $routes->match(['get', 'post'], 'table/(:segment)/row/new/modal', 'Liste::RowNewModal/$1');
    $routes->match(['get', 'post'], 'table/(:segment)/row/(:num)', 'Liste::RowEdit/$1/$2');
    $routes->match(['get', 'post'], 'table/(:segment)/row/(:num)/modal', 'Liste::RowEditModal/$1/$2');
});


