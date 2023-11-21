<?php
/**
 * This is Base Module Routes 
**/

namespace Base\Config;

$routes = \Config\Services::routes();

$routes->set404Override('\Components\Controllers\Redirect::error404');
$routes->get('forbidden', '\Components\Controllers\Redirect::forbidden403');

$routes->group('', ['filter' => 'IdentificationCheck'], function($routes)
{
    $routes->match(['get', 'post'], '/', '\Custom\Controllers\Root::index');
});