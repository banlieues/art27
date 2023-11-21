<?php
namespace DataView\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
// $routes->group('dataview', ['namespace' => 'DataView\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
// {
//     $routes->get('/', 'DataViewConstructorController::index');
//     $routes->get('index', 'DataViewConstructorController::index');
//     $routes->get("list_add_field/(:any)/(:any)", "DataViewConstructorController::list_add_field/$1/$2");
//     $routes->match(["get", "post"], "list_add_field/(:any)/(:any)", "DataViewConstructorController::list_add_field/$1/$2");
// });


// $routes->group('modelisation', ['namespace' => 'DataView\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
// {
//     $routes->get('/', 'DataViewConstructorController::index');
//     $routes->get('index', 'DataViewConstructorController::index');
//     $routes->get("list_fields/(:any)", "DataViewConstructorController::list_fields/$1");
//     $routes->get("editfield/(:any)/(:any)/(:any)", "DataViewConstructorController::edit_field/$1/$2/$3");
//     $routes->match(["get", "post"], "editfield/(:any)/(:any)", "DataViewConstructorController::edit_field/$1/$2");
//     $routes->get("saveField","DataViewConstructorController::saveField");
//     $routes->match(["get", "post"], "saveField", "DataViewConstructorController::saveField");
//     $routes->get("injectedForm", "DataViewConstructorController::injectedForm");
//     $routes->get("injectedForm/(:any)", "DataViewConstructorController::injectedForm/$1");
//     $routes->match(["get", "post"], "injectedForm/(:any)", "DataViewConstructorController::injectedForm/(:any)");
//     $routes->get("saveInjectedForm","DataViewConstructorController::saveInjectedForm");
//     $routes->match(["get", "post"], "saveInjectedForm", "DataViewConstructorController::saveInjectedForm");
//     $routes->get("injectedFormIframeOnly/(:any)", "DataViewConstructorController::injectedFormIframeOnly/$1");
//     $routes->get("injectedFormIframe/(:any)", "DataViewConstructorController::injectedFormIframe/$1");

// });