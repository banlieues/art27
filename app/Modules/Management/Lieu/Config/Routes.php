<?php
/**
 * This is Lieu Module Routes 
**/

namespace Lieu\Config;

$routes = \Config\Services::routes();


$routes->group('lieu', ['namespace' => 'Lieu\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'],'/', 'Lieu::listLieu');
    $routes->match(['get', 'post'],'index', 'Lieu::listLieu');

    $routes->match(['get', 'post'],'listlieu', 'Lieu::listLieu');

    $routes->match(['get', 'post'],'save', 'Lieu::save');



    $routes->match(['get', 'post'],'viewlieu/(:any)', 'Lieu::viewLieu/$1');
    

    $routes->match(['get', 'post'],'formlieu', 'Lieu::formLieu');
    $routes->match(['get', 'post'],'formlieu/(:any)', 'Lieu::formLieu/$1');
    $routes->match(['get', 'post'],'formlieu/(:any)/(:any)', 'Lieu::formLieu/$1/$2');

    $routes->match(['get', 'post'],'save_modelisation', 'Lieu::save_modelisation');
    $routes->match(['get', 'post'],'modelisationlieu', 'Lieu::modelisationLieu');
    $routes->match(['get', 'post'],'modelisationlieu/(:any)', 'Lieu::modelisationLieu/$1');
    $routes->match(['get', 'post'],'modelisationlieu/(:any)/(:any)', 'Lieu::modelisationLieu/$1/$2');

    

});


