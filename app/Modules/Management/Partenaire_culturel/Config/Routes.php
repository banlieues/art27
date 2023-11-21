<?php

namespace Partenaire_culturel\Config;

$routes = \Config\Services::routes();

$routes->group('partenaire_culturel', ['namespace' => 'Partenaire_culturel\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Partenaire_culturel::listPartenaire_culturel');
    $routes->match(['get', 'post'], 'index', 'Partenaire_culturel::listPartenaire_culturel');
    $routes->match(['get', 'post'], 'list_partenaire_culturel', 'Partenaire_culturel::listPartenaire_culturel');

    $routes->match(['get', 'post'], 'save_new', 'Partenaire_culturel::save_new');
    $routes->match(['get', 'post'], 'save_associe_demande', 'Partenaire_culturel::save_associe_demande');


    $routes->match(['get', 'post'], 'fiche/(:num)', 'Partenaire_culturel::fiche/$1');
    $routes->match(['get', 'post'], 'associe_demande/(:num)', 'Partenaire_culturel::associe_demande/$1');

    $routes->match(['get', 'post'],'save_modelisation', 'Partenaire_culturel::save_modelisation');
    $routes->match(['get', 'post'],'modelisationpartenaire_culturel', 'Partenaire_culturel::modelisationPartenaire_culturel');
    $routes->match(['get', 'post'],'modelisationpartenaire_culturel/(:any)', 'Partenaire_culturel::modelisationPartenaire_culturel/$1');
    $routes->match(['get', 'post'],'modelisationpartenaire_culturel/(:any)/(:any)', 'Partenaire_culturel::modelisationPartenaire_culturel/$1/$2');

    $routes->match(['get', 'post'],'formPartenaire_culturel', 'Partenaire_culturel::formPartenaire_culturel');
    $routes->match(['get', 'post'],'formPartenaire_culturel/(:any)', 'Partenaire_culturel::formPartenaire_culturel/$1');
    $routes->match(['get', 'post'],'formPartenaire_culturel/(:any)/(:any)', 'Partenaire_culturel::formPartenaire_culturel/$1/$2');

    $routes->match(['get', 'post'],'save', 'Partenaire_culturel::save');
    $routes->match(['get', 'post'],'save/(:any)', 'Partenaire_culturel::save/$1');

    $routes->match(['get', 'post'], 'new', 'Partenaire_culturel::new');

    $routes->match(['get', 'post'], 'verif_doublon_adresse', 'Partenaire_culturel::verif_doublon_adresse');

    $routes->match(['get', 'post'],'get_partenaire_culturel_data/(:any)', 'Partenaire_culturel::get_partenaire_culturel_data/$1');

    $routes->match(['get', 'post'],'search_partenaire_culturel_by_id_contact/(:any)/(:any)', 'Partenaire_culturel::search_partenaire_culturel_by_id_contact/$1/$2');

    $routes->match(['get', 'post'],'get_remarque_by_partenaire_culturel/(:any)', 'Partenaire_culturel::get_remarque_by_partenaire_culturel/$1');
    $routes->match(['get', 'post'],'get_commentaire_by_art27_partenaire_culturel/(:any)', 'Partenaire_culturel::get_commentaire_by_art27_partenaire_culturel/$1');


    $routes->match(['get', 'post'],'gestion_ticket/(:any)/(:any)', 'Partenaire_culturel::gestion_ticket/$1/$2');

    $routes->match(['get', 'post'],'list_ticket/(:any)/(:any)', 'Partenaire_culturel::list_ticket/$1/$2');



});


