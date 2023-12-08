<?php

namespace DocumentUpload\Config;

$routes = \Config\Services::routes();

$routes->group('documentUpload', ['namespace' => 'DocumentUpload\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    
    $routes->match(['get', 'post'],'upload_file', 'DocumentUpload::upload_file');

});

$routes->group('document', ['namespace' => 'DocumentUpload\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    
    $routes->match(['get', 'post'], '/', 'DocumentUpload::listDocument');
    $routes->match(['get', 'post'], 'index', 'DocumentUpload::listDocument');
    $routes->match(['get', 'post'], 'list_document', 'DocumentUpload::listDocument');

    $routes->match(['get', 'post'], 'listDocument/(:any)', 'DocumentUpload::listDocument/$1');
    $routes->match(['get', 'post'], 'listDocument/(:any)/(:any)', 'DocumentUpload::listDocument/$1/$2');

    $routes->match(['get', 'post'], 'listes_documents_entity/(:any)', 'DocumentUpload::listes_documents_entity/$1');
    $routes->match(['get', 'post'], 'listes_documents_entity/(:any)/(:any)', 'DocumentUpload::listes_documents_entity/$1/$2');

    $routes->match(['get', 'post'], 'fiche/(:any)', 'DocumentUpload::fiche/$1');

    $routes->match(['get', 'post'], 'setTypeDocument/(:any)', 'DocumentUpload::setTypeDocument/$1');
    $routes->match(['get', 'post'], 'setCommentaire', 'DocumentUpload::setCommentaire');

    $routes->match(['get', 'post'], 'liste_demande/(:any)', 'DocumentUpload::liste_demande/$1');

    $routes->match(['get', 'post'], 'associe_demande/(:any)', 'DocumentUpload::associe_demande/$1');


    




});


