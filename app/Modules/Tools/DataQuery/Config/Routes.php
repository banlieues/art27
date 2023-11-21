<?php
namespace DataQuery\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();

$routes->group('queries', ['namespace' => 'DataQuery\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'DataQuery::index');
    $routes->get('index', 'DataQuery::index');
    $routes->match(["get", "post"],'index/(:any)', 'DataQuery::index/$1');
    $routes->match(["get", "post"],'index/(:any)/(:any)', 'DataQuery::index/$1/$2');
    $routes->match(["get", "post"],'index/(:any)/(:any)/(:any)', 'DataQuery::index/$1/$2/$3');



    $routes->get("get_list_select_field/(:any)", "DataQuery::get_list_select_field/$1");
    $routes->get("get_list_select_field/(:any)/(:any)", "DataQuery::get_list_select_field/$1/$2");
    $routes->match(["get", "post"], "get_list_select_field/(:any)", "DataQuery::get_list_select_field/$1");
    $routes->match(["get", "post"], "get_list_select_field/(:any)/(:any)", "DataQuery::get_list_select_field/$1/$2");

    $routes->get("get_input/(:any)", "DataQuery::get_input/$1");
    $routes->match(["get", "post"], "get_input/(:any)", "DataQuery::get_input/$1");

    $routes->get("execute", "DataQuery::execute");
    $routes->match(["get", "post"], "execute", "DataQuery::execute");
    $routes->match(["get", "post"], "execute/(:any)", "DataQuery::execute/$1");
    $routes->match(["get", "post"], "execute/(:any)/(:any)", "DataQuery::execute/$1/$2");
    $routes->match(["get", "post"], "execute/(:any)/(:any)/(:any)", "DataQuery::execute/$1/$2/$3");

    $routes->get("export_csv", "DataQuery::export_csv");
    $routes->match(["get", "post"], "export_csv", "DataQuery::export_csv");
    $routes->match(["get", "post"], "export_csv/(:any)", "DataQuery::export_csv/$1");
    $routes->match(["get", "post"], "export_csv/(:any)/(:any)", "DataQuery::export_csv/$1/$2");

    $routes->get("save_query", "DataQuery::save_query");
    $routes->get("update_query/(:any)", "DataQuery::update_query/$1");
    $routes->match(["get", "post"], "save_query", "DataQuery::save_query");

    $routes->get("list", "DataQuery::list");

    $routes->match(["get", "post"], "is_dasboard/(:any)", "DataQuery::is_dasboard/$1");


    
});