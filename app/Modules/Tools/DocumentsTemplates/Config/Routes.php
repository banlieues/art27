<?php

namespace DocumentsTemplates\Config;

$routes = \Config\Services::routes();

$routes->group('documentstemplates', ['namespace' => 'DocumentsTemplates\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->get('/', 'DocumentsTemplates::index');
    $routes->get('index', 'DocumentsTemplates::index');
    
    $routes->match(['get', 'post'], 'add', 'DocumentsTemplates::add');
    $routes->post('activate', 'DocumentsTemplates::activate');
   // $routes->get('details', 'DocumentsTemplates::details');
    $routes->match(['get', 'post'], 'details', 'DocumentsTemplates::details');
    $routes->get('edit', 'DocumentsTemplates::edit');
    $routes->post('save', 'DocumentsTemplates::save');
    // $routes->match(['get', 'post'], 'sendmail', 'DocumentsTemplates::sendmail');
    $routes->post('sendmail', 'DocumentsTemplates::sendmail');
    // $routes->get('duplicate', 'DocumentsTemplates::duplicate');
    $routes->match(['get', 'post'], 'duplicate', 'DocumentsTemplates::duplicate');
    // $routes->get('delete', 'DocumentsTemplates::delete');
    $routes->match(['get', 'post'], 'delete', 'DocumentsTemplates::delete');

    // For DomPdf Part
    $routes->get('dompdf', 'DocumentsTemplates::dompdf');

    // For PhpWord Part
    $routes->get('phpword', 'DocumentsTemplates::phpword');

    //for modal test mail
    $routes->get("testmail/(:any)", "DocumentsTemplates::testmail/$1");
    $routes->match(['get', 'post'], 'testmail/(:any)', 'DocumentsTemplates::testmail/$1');

});
