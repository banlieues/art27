<?php

namespace Company\Config;

$routes = \Config\Services::routes();

$routes->get('company/external/cron', '\Company\Controllers\External::cron');

$routes->group('company', ['namespace' => 'Company\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'Company::index');
    $routes->match(['get', 'post'], 'companies', 'Company::CompanyList');
    $routes->match(['get', 'post'], 'companies/export/csv', 'Company::CompaniesExportCsv');
    $routes->match(['get', 'post'], 'companies/get', 'Company::CompaniesGet');
    $routes->match(['get', 'post'], 'company/delete/(:num)', 'Company::CompanyDelete/$1');
    $routes->match(['get', 'post'], 'company/delete/modal/(:num)', 'Company::CompanyDeleteModal/$1');
    $routes->match(['get', 'post'], 'company/view', 'Company::CompanyView');
    $routes->match(['get', 'post'], 'company/view/(:num)', 'Company::CompanyView/$1');
    $routes->match(['get', 'post'], 'company/view/(:num)/comment', 'Company::CompanyModalComment/$1');
    $routes->match(['get', 'post'], 'deposit/delete/(:num)', 'Deposit::DepositDelete/$1');
    $routes->match(['get', 'post'], 'deposit/delete/modal/(:num)', 'Deposit::DepositDeleteModal/$1');
    $routes->match(['get', 'post'], 'deposit/info/modal/(:num)', 'Deposit::DepositInfoModal/$1');
    $routes->match(['get', 'post'], 'deposit/view/(:num)/comment', 'Deposit::DepositModalComment/$1');
    $routes->match(['get', 'post'], 'deposit/to/company/(:num)', 'Deposit::DepositToCompany/$1');
    $routes->match(['get', 'post'], 'deposit/to/company/modal/(:num)', 'Deposit::DepositToCompanyModal/$1');
    $routes->match(['get', 'post'], 'deposit/to/company/update/(:num)', 'Deposit::DepositToCompanyUpdate/$1');
    $routes->match(['get', 'post'], 'deposit/to/company/update/modal', 'Deposit::DepositToCompanyUpdateModal');
    $routes->match(['get', 'post'], 'deposits', 'Deposit::DepositList');
    $routes->match(['get', 'post'], 'deposits/get', 'Deposit::deposits_get');
    $routes->match(['get', 'post'], 'index', 'Company::index');
    $routes->match(['get', 'post'], 'rule', 'Company::rule');
    $routes->match(['get', 'post'], 'set/worker/(:segment)/(:num)', 'Deposit::DepositSetWorker/$1/$2');
});


