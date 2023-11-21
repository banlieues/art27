<?php

namespace Messagerie\Config;

$routes = \Config\Services::routes();

$routes->group('messagerie', ['namespace' => 'Messagerie\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
   
    $routes->match(['get', 'post'], '/', 'Messagerie::index');
    $routes->match(['get', 'post'], 'index', 'Messagerie::index');

    $routes->match(['get', 'post'], 'count_non_lu', 'Messagerie::count_non_lu');
    $routes->match(['get', 'post'], 'get_non_lu', 'Messagerie::get_non_lu');
    $routes->match(['get', 'post'], 'message_view/(:any)', 'Messagerie::message_view/$1');

    $routes->match(['get', 'post'], 'get_note_of_entity/(:any)/(:any)', 'Messagerie::get_note_of_entity/$1/$2');

    $routes->match(['get', 'post'], 'form_note/(:any)/(:any)', 'Messagerie::form_note/$1/$2');

    $routes->match(['get', 'post'], 'set_note', 'Messagerie::set_note');
    $routes->match(['get', 'post'], 'set_note_no_ajax', 'Messagerie::set_note_no_ajax');


    $routes->match(['get', 'post'], 'set_lu_entity', 'Messagerie::set_lu_entity');

    

   

    
    
    
});


