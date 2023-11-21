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

$routes->group('modelisation', ['namespace' => 'Modelisation\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(["get", "post"], '/', 'ModelisationController::index');
    $routes->match(["get", "post"], 'index', 'ModelisationController::index');
    $routes->match(["get", "post"], '(:segment)/fiche', 'ModelisationController::fiche/$1');
    $routes->match(["get", "post"], "(:segment)/field/(:segment)", "ModelisationController::Field/$1/$2");
    $routes->match(["get", "post"], "(:segment)/field/(:segment)/(:segment)", "ModelisationController::Field/$1/$2/$3");
    $routes->match(["get", "post"], "(:segment)/fields", "ModelisationController::Fields/$1");
    $routes->match(["get", "post"], "injectedForm", "ModelisationController::injectedForm");
    $routes->match(["get", "post"], "injectedForm/(:any)", "ModelisationController::injectedForm/$1");
    $routes->match(["get", "post"], "injectedFormIframe/(:any)", "ModelisationController::injectedFormIframe/$1");
    $routes->match(["get", "post"], "injectedFormIframeOnly/(:any)", "ModelisationController::injectedFormIframeOnly/$1");
    $routes->match(["get", "post"], "list_add_field/(:any)/(:any)", "ModelisationController::list_add_field/$1/$2");
    $routes->match(["get", "post"], "save/fiche/(:segment)","ModelisationController::FicheSave/$1");
    $routes->match(["get", "post"], "saveField","ModelisationController::saveField");
    $routes->match(["get", "post"], "saveInjectedForm","ModelisationController::saveInjectedForm");
});