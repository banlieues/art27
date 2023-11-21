<?php

namespace DepositBox\Config;

$routes = \Config\Services::routes();

$routes->group('depositbox', ['namespace' => 'DepositBox\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->get('/', 'DepositBox::index');
    $routes->get('index', 'DepositBox::index');
    // $routes->get('add', 'DepositBox::add');
    $routes->match(['get', 'post'], 'add', 'DepositBox::add');
    $routes->post('activate', 'DepositBox::activate');
    $routes->get('details', 'DepositBox::details');
    $routes->get('edit', 'DepositBox::edit');
    $routes->post('save', 'DepositBox::save');
    // $routes->get('delete', 'DepositBox::delete');
    $routes->match(['get', 'post'], 'delete', 'DepositBox::delete');
});
