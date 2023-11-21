<?php

namespace Barcode\Config;

$routes = \Config\Services::routes();

$routes->group('barcode-controller', ['namespace' => 'Barcode\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // on fait appel au controleur au lieu du view directement. le controleur va retourner le view
    $routes->match(['get', 'post'], 'index', 'BarcodeController::index');
    $routes->match(['get', 'post'], '/', 'BarcodeController::index');
    $routes->match(['get', 'post'], 'generate', 'BarcodeController::generate');

    $routes->match(['get', 'post'], 'partenaire_social', 'BarcodeController::partenaire_social');
    $routes->match(['get', 'post'], 'test_pdf', 'BarcodeController::test_pdf');


    // $routes->match(['get', 'post'],'search_partenaire_social_by_id_contact/(:any)/(:any)', 'Partenaire_social::search_partenaire_social_by_id_contact/$1/$2');



    
});


