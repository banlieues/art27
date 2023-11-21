<?php

namespace Contact\Config;

$routes = \Config\Services::routes();

$routes->group('contact', ['namespace' => 'Contact\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Contact::listContact');
    $routes->match(['get', 'post'], 'index', 'Contact::listContact');
    $routes->match(['get', 'post'], 'list_contact', 'Contact::listContact');


    $routes->match(['get', 'post'], 'fiche/(:any)', 'Contact::fiche/$1');

    $routes->match(['get', 'post'], 'new', 'Contact::new');
    $routes->match(['get', 'post'], 'save_new', 'Contact::save_new');
    $routes->match(['get', 'post'], 'save_associe_demande', 'Contact::save_associe_demande');


    

    $routes->match(['get', 'post'], 'associe_demande/(:num)/(:num)', 'Contact::associe_demande/$1/$2');


    $routes->match(['get', 'post'],'save_modelisation', 'Contact::save_modelisation');
    $routes->match(['get', 'post'],'modelisationcontact', 'Contact::modelisationContact');
    $routes->match(['get', 'post'],'modelisationcontact/(:any)', 'Contact::modelisationContact/$1');
    $routes->match(['get', 'post'],'modelisationcontact/(:any)/(:any)', 'Contact::modelisationContact/$1/$2');

    $routes->match(['get', 'post'],'formContact', 'Contact::formContact');
    $routes->match(['get', 'post'],'formContact/(:any)', 'Contact::formContact/$1');
    $routes->match(['get', 'post'],'formContact/(:any)/(:any)', 'Contact::formContact/$1/$2');

    $routes->match(['get', 'post'],'result_search_link', 'Contact::result_search_link');
    

    $routes->match(['get', 'post'],'save/(:any)', 'Contact::save/$1');

    $routes->match(['get', 'post'], 'save_insert', 'Contact::save_insert');

    $routes->match(['get', 'post'], 'return_fiche/(:any)', 'Contact::return_fiche/$1');

    $routes->match(['get', 'post'], 'delete_relation_contact_profil', 'Contact::delete_relation_contact_profil');

    $routes->match(['get', 'post'], 'contact_profil_add/(:any)', 'Contact::contact_profil_add/$1');


});

$routes->group('demandeur', ['namespace' => 'Contact\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Contact::listDemandeur');
    $routes->match(['get', 'post'], 'index', 'Contact::listDemandeur');
    $routes->match(['get', 'post'], 'list', 'Contact::listDemandeur');




});


