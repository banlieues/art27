<?php

namespace Mapping\Config;

$routes = \Config\Services::routes();

$routes->group('mapping', ['namespace' => 'Mapping\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Mapping::index');
    $routes->match(['get', 'post'], 'data/(:segment)/(:num)', 'Mapping::MappingDataGet/$1/$2');
    // $routes->match(['get', 'post'], 'osmdata/get', 'Mapping::OsmDataGet');
    $routes->match(['get', 'post'], 'search', 'Mapping::Search');
    $routes->match(['get', 'post'], 'set/geocode', 'Mapping::geocodeSet');
    $routes->match(['get', 'post'], 'territory/validation', 'Mapping::TerritoryValidation');
});


