<?php

namespace Components\Config;

$routes = \Config\Services::routes();

// Base64
$routes->group('base64', ['namespace' => 'Components\Controllers','filter' => 'IdentificationCheck'], function($routes)
{
    // File display
    $routes->match(["get", "post"], "/", "Base64::index");
    $routes->match(["get", "post"], "/index", "Base64::index");

    $routes->match(["get", "post"], "mass64", "Base64::mass64");
    $routes->match(["get", "post"], "post", "Base64::post");

});

// Cron
$routes->group('cron', ['namespace' => 'Components\Controllers'], function($routes)
{
    $routes->match(["get", "post"], "min/15", "Cron::Minutes15");
});

// Debug
$routes->group('debug', ['namespace' => 'Components\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'demande', 'Debug::Demande');
    $routes->match(['get', 'post'], 'demande/historique', 'Debug::DemandeHistorique');
    $routes->match(['get', 'post'], 'email', 'Debug::Email');
    $routes->match(['get', 'post'], 'enquete', 'Debug::Enquete');
});

// File
$routes->group('file', ['namespace' => 'Components\Controllers','filter' => 'IdentificationCheck'], function($routes)
{
    $routes->match(["get", "post"], "display/(:num)/(:segment)", "File::FileDisplay/$1");
    $routes->match(["get", "post"], "export/csv", "File::ExportCsv");
    $routes->match(["get", "post"], "export/(:segment)/(:segment)/(:segment)/(:segment)", "File::Export/$1/$2/$3/$4");
    $routes->match(["get", "post"], "read/(:segment)", "File::FileRead/$1");
    $routes->match(["get", "post"], "summernote/upload/image", "File::summernoteUploadImage");
    // $routes->match(["get", "post"], "upload/(:segment)/(:segment)", "File::upload/$1/$2");
});

// Iframe
$routes->group('iframe', ['namespace' => 'Components\Controllers'], function($routes)
{
    $routes->match(["get", "post"], "/", "Iframe::index");
    $routes->match(["get", "post"], "index", "Iframe::index");
});

// Migrate
$routes->match(["get", "post"], "migrate", "\Components\Controllers\Migrate::index");

// Redirect
$routes->group('redirect', ['namespace' => 'Layout\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'to/(:any)', 'Redirect::to/$1');
    // $routes->match(['get', 'post'], 'dashboard', 'Redirect::index/dashboard');
});

// Reminder
$routes->match(["get", "post"], "reminder/access/(:segment)/(:num)", "\Components\Controllers\ReminderController::AccessGetByToken/$1/$2");











