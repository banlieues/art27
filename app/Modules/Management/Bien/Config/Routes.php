<?php

namespace Bien\Config;

$routes = \Config\Services::routes();

$routes->group('bien', ['namespace' => 'Bien\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Bien::listBien');
    $routes->match(['get', 'post'], 'index', 'Bien::listBien');
    $routes->match(['get', 'post'], 'list_bien', 'Bien::listBien');

    $routes->match(['get', 'post'], 'save_new', 'Bien::save_new');
    $routes->match(['get', 'post'], 'save_associe_demande', 'Bien::save_associe_demande');


    $routes->match(['get', 'post'], 'fiche/(:num)', 'Bien::fiche/$1');
    $routes->match(['get', 'post'], 'associe_demande/(:num)', 'Bien::associe_demande/$1');

    $routes->match(['get', 'post'],'save_modelisation', 'Bien::save_modelisation');
    $routes->match(['get', 'post'],'modelisationbien', 'Bien::modelisationBien');
    $routes->match(['get', 'post'],'modelisationbien/(:any)', 'Bien::modelisationBien/$1');
    $routes->match(['get', 'post'],'modelisationbien/(:any)/(:any)', 'Bien::modelisationBien/$1/$2');

    $routes->match(['get', 'post'],'formBien', 'Bien::formBien');
    $routes->match(['get', 'post'],'formBien/(:any)', 'Bien::formBien/$1');
    $routes->match(['get', 'post'],'formBien/(:any)/(:any)', 'Bien::formBien/$1/$2');

    $routes->match(['get', 'post'],'save/(:any)', 'Bien::save/$1');

    $routes->match(['get', 'post'], 'new', 'Bien::new');

    $routes->match(['get', 'post'], 'verif_doublon_adresse', 'Bien::verif_doublon_adresse');

    $routes->match(['get', 'post'],'get_bien_data/(:any)', 'Bien::get_bien_data/$1');

    $routes->match(['get', 'post'],'search_bien_by_id_contact/(:any)/(:any)', 'Bien::search_bien_by_id_contact/$1/$2');

    
    
});


