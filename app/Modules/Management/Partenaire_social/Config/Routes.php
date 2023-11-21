<?php

namespace Partenaire_social\Config;

$routes = \Config\Services::routes();

$routes->group('partenaire_social', ['namespace' => 'Partenaire_social\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Partenaire_social::listPartenaire_social');
    $routes->match(['get', 'post'], 'index', 'Partenaire_social::listPartenaire_social');
    $routes->match(['get', 'post'], 'list_partenaire_social', 'Partenaire_social::listPartenaire_social');

    $routes->match(['get', 'post'], 'save_new', 'Partenaire_social::save_new');
    $routes->match(['get', 'post'], 'save_associe_demande', 'Partenaire_social::save_associe_demande');


    $routes->match(['get', 'post'], 'fiche/(:num)', 'Partenaire_social::fiche/$1');
    $routes->match(['get', 'post'], 'associe_demande/(:num)', 'Partenaire_social::associe_demande/$1');

    $routes->match(['get', 'post'],'save_modelisation', 'Partenaire_social::save_modelisation');
    $routes->match(['get', 'post'],'modelisationpartenaire_social', 'Partenaire_social::modelisationPartenaire_social');
    $routes->match(['get', 'post'],'modelisationpartenaire_social/(:any)', 'Partenaire_social::modelisationPartenaire_social/$1');
    $routes->match(['get', 'post'],'modelisationpartenaire_social/(:any)/(:any)', 'Partenaire_social::modelisationPartenaire_social/$1/$2');

    $routes->match(['get', 'post'],'modif', 'Partenaire_social::modif');
    $routes->match(['get', 'post'],'modif/(:any)', 'Partenaire_social::modif/$1');

    $routes->match(['get', 'post'],'save', 'Partenaire_social::save');
    $routes->match(['get', 'post'],'save/(:any)', 'Partenaire_social::save/$1');

    $routes->match(['get', 'post'], 'new', 'Partenaire_social::new');

    $routes->match(['get', 'post'], 'verif_doublon_adresse', 'Partenaire_social::verif_doublon_adresse');

    $routes->match(['get', 'post'],'get_partenaire_social_data/(:any)', 'Partenaire_social::get_partenaire_social_data/$1');

    $routes->match(['get', 'post'],'search_partenaire_social_by_id_contact/(:any)/(:any)', 'Partenaire_social::search_partenaire_social_by_id_contact/$1/$2');

    $routes->match(['get', 'post'],'get_remarque_by_partenaire_social/(:any)', 'Partenaire_social::get_remarque_by_partenaire_social/$1');
    $routes->match(['get', 'post'],'get_commentaire_by_art27_partenaire_social/(:any)', 'Partenaire_social::get_commentaire_by_art27_partenaire_social/$1');

    $routes->match(['get', 'post'], 'convention_barcode/(:any)/(:any)', 'Partenaire_social::convention_barcode/$1/$2');
    $routes->match(['get', 'post'], 'convention_barcode_modif/(:any)/(:any)', 'Partenaire_social::convention_barcode_modif/$1/$2');

    $routes->match(['get', 'post'], 'save_convention', 'Partenaire_social::save_convention');

    $routes->match(['get', 'post'], 'barcode_generator', 'Partenaire_social::barcode_generator');

    $routes->match(['get', 'post'], 'barcode_list/(:any)', 'Partenaire_social::barcode_list/$1');
    $routes->match(['get', 'post'], 'barcode_list/(:any)/(:any)', 'Partenaire_social::barcode_list/$1/$2');
    $routes->match(['get', 'post'], 'barcode_list/(:any)/(:any)/(:any)', 'Partenaire_social::barcode_list/$1/$2/$3');

    $routes->match(['get', 'post'], 'setCommentaire', 'Partenaire_social::setCommentaire');
    $routes->match(['get', 'post'], 'setNumCode', 'Partenaire_social::setNumCode');
    $routes->match(['get', 'post'], 'setStatut/(:any)', 'Partenaire_social::setStatut/$1');



    $routes->match(['get', 'post'], 'test_imagick', 'Partenaire_social::test_imagick');



    
});


