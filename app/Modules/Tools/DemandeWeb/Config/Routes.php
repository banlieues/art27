<?php

namespace DemandeWeb\Config;

$routes = \Config\Services::routes();

$routes->group('demande/web', ['namespace' => 'DemandeWeb\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'Deposit::DepositList');
    $routes->match(['get', 'post'], 'cron', 'External::cron');
    $routes->match(['get', 'post'], 'deposit', 'Deposit::DepositList');
    $routes->match(['get', 'post'], 'deposit/(:num)', 'Deposit::DepositView/$1');
    $routes->match(['get', 'post'], 'deposit/delete/(:num)', 'Deposit::DepositDelete/$1');
    $routes->match(['get', 'post'], 'deposit/delete/modal/(:num)', 'Deposit::deposit_delete_modal/$1');
    $routes->match(['get', 'post'], 'deposit/info/modal/(:num)', 'Deposit::DepositInfoModal/$1');
    $routes->match(['get', 'post'], 'deposits', 'Deposit::DepositList');
    $routes->match(['get', 'post'], 'deposits/get', 'Deposit::DepositsGet');
    $routes->match(['get', 'post'], 'dublons/building/get/(:num)/(:num)/(:num)', 'Deposit::DublonsBuildingGet/$1/$2/$3');
    $routes->match(['get', 'post'], 'dublons/demande/get/(:num)/(:num)/(:num)/(:num)', 'Deposit::DublonsDemandeGet/$1/$2/$3/$4');
    $routes->match(['get', 'post'], 'dublons/profil/get/(:num)/(:num)', 'Deposit::DublonsProfilGet/$1/$2');
    $routes->match(['get', 'post'], 'deposit/to/demande', 'Demande::DemandeImport');
    // $routes->match(['get', 'post'], 'profil/building/by/demande/get/(:num)', 'Demande::BuildingsProfilsGetByDemande/$1');
    $routes->match(['get', 'post'], 'set/worker/(:segment)/(:num)', 'Deposit::DepositSetWorker/$1/$2');
});
