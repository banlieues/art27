<?php
namespace Import\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('import', ['namespace' => 'Import\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'Import::index');
    $routes->get('index', 'Import::index');
    $routes->match(["get", "post"], "index", "Import::index");

    $routes->get('execute', 'Import::execute');
    $routes->match(["get", "post"], "execute", "Import::execute");

    $routes->get("table_importation/(:any)","Import::table_importation/$1");
    $routes->get("table_importation/(:any)/(:any)","Import::table_importation/$1/$2");

    $routes->get("retirer_importation/(:any)/(:any)","Import::retirer_importation/$1/$2");

    //$routes->get('value_select', 'Import::value_select');
    $routes->match(["get", "post"], "value_select", "Import::value_select");

    $routes->match(["get", "post"], "value_select/(:any)/(:any)/(:any)", "Import::value_select/$1/$2/$3");

    $routes->match(["get", "post"], "insert", "Import::insert");

    $routes->match(["get", "post"], "traducteur_list", "Import::traducteur_list");

    
});