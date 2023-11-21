<?php

namespace Mail\Config;

$routes = \Config\Services::routes();

$routes->group('mail', ['namespace' => 'Mail\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'Mail::index');
    $routes->match(['get', 'post'], 'documentation', 'Mail::documentation');
    $routes->match(['get', 'post'], 'example/email_archive/(:num)', 'Example::email_archive/$1');
    $routes->match(['get', 'post'], 'example/email_delete/(:num)', 'Example::email_delete/$1');
    $routes->match(['get', 'post'], 'example/email/save', 'Example::EmailSave');
    // $routes->match(['get', 'post'], 'example/email/save', 'Example::email_save');
    $routes->match(['get', 'post'], 'example/forward', 'Example::email_view/forward');
    $routes->match(['get', 'post'], 'example/email_delete_modal/(:num)', 'Example::email_delete_modal/$1');
    $routes->match(['get', 'post'], 'example/email_edit_modal/(:num)', 'Example::email_edit_modal/$1');
    $routes->match(['get', 'post'], 'example/email_info_modal/(:num)', 'Example::email_info_modal/$1');
    $routes->match(['get', 'post'], 'example/email_archive_modal/(:num)', 'Example::email_archive_modal/$1');
    $routes->match(['get', 'post'], 'example/new', 'Example::email_view/new');
    $routes->match(['get', 'post'], 'example/output_data/(:num)', 'Example::output_data/$1');
    $routes->match(['get', 'post'], 'example/reply', 'Example::email_view/reply');
    $routes->match(['get', 'post'], 'example/view/(:alpha)', 'Example::email_view/$1');
    $routes->match(['get', 'post'], 'index', 'Mail::index');
    $routes->match(['get', 'post'], 'list', 'Mail::email_list');
    $routes->match(['get', 'post'], 'save', 'Mail::EmailSave');
    $routes->match(['get', 'post'], 'send', 'Mail::EmailSend');
    $routes->match(['get', 'post'], 'template/(:num)', 'Mail::template_get/$1');
});
