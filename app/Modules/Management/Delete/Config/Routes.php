<?php
namespace Delete\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('delete', ['namespace' => 'Delete\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
   
    $routes->match(["get", "post"], "deleteFieldIndex", "Delete::deleteFieldIndex");
    $routes->match(["get", "post"], "deletePartenaire", "Delete::deletePartenaire");
    $routes->match(["get", "post"], "deleteQuery", "Delete::deleteQuery");
    $routes->match(["get", "post"], "deleteInjectedForm", "Delete::deleteInjectedForm");

    $routes->match(["get", "post"], "deleteEmail", "Delete::deleteEmail");

    $routes->match(["get", "post"], "deleteDocument", "Delete::deleteDocument");

    $routes->match(["get", "post"], "partenaire_social_convention", "Delete::partenaire_social_convention");

});


