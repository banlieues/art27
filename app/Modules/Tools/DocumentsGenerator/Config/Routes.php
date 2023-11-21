<?php

namespace DocumentsGenerator\Config;

$routes = \Config\Services::routes();

$routes->group('documentsgenerator', ['namespace' => 'DocumentsGenerator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->get('/', 'DocumentsGenerator::index');
    $routes->get('index', 'DocumentsGenerator::index');
    $routes->get('list', 'DocumentsGenerator::list');
   // $routes->post('list', 'DocumentsGenerator::list');
    $routes->match(['get', 'post'], 'list', 'DocumentsGenerator::list');
    $routes->match(['get', 'post'], "list/(:any)", "DocumentsGenerator::list/$1");
    $routes->match(['get', 'post'], "list/(:any)/(:any)", "DocumentsGenerator::list/$1/$2");
    $routes->get('getpdf', 'DocumentsGenerator::getPdf');
    $routes->get("getpdf/(:any)/(:any)/(:any)", "DocumentsGenerator::getPdf/$1/$2/$3");

    $routes->match(['get', 'post'], 'getListTag', 'DocumentsGenerator::getListTag');

   // $routes->match(['get', 'post'], '', 'DocumentsGenerator::list');
    //$routes->post('produce', 'DocumentsGenerator::produce');
   // $routes->match(['get', 'post'], '', 'DocumentsGenerator::produce');
 /*
   $routes->get("get_list_select_field/(:any)", "DataQuery::get_list_select_field/$1");
   $routes->get("get_list_select_field/(:any)/(:any)", "DataQuery::get_list_select_field/$1/$2");
   $routes->match(["get", "post"], "get_list_select_field/(:any)", "DataQuery::get_list_select_field/$1");
   $routes->match(["get", "post"], "get_list_select_field/(:any)/(:any)", "DataQuery::get_list_select_field/$1/$2");

   $routes->get("get_input/(:any)", "DataQuery::get_input/$1");
   $routes->match(["get", "post"], "get_input/(:any)", "DataQuery::get_input/$1");

   $routes->get("execute", "DataQuery::execute");
   $routes->match(["get", "post"], "execute", "DataQuery::execute");

   $routes->get("export_csv", "DataQuery::export_csv");
   $routes->match(["get", "post"], "export_csv", "DataQuery::export_csv");

   $routes->get("save_query", "DataQuery::save_query");
   $routes->match(["get", "post"], "save_query", "DataQuery::save_query");

   $routes->get("list", "DataQuery::list");
   */

});
