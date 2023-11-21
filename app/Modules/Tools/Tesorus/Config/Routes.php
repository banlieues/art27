<?php

namespace Tesorus\Config;

$routes = \Config\Services::routes();

$routes->group('tesorus', ['namespace' => 'Tesorus\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Tesorus::index');
    $routes->match(['get', 'post'], 'cells', 'Tesorus::CellList');
    // $routes->match(['get', 'post'], 'cells/get', 'Tesorus::CellsGet');
    $routes->match(['get', 'post'], 'cell/dublon', 'Tesorus::cell_dublon');
    $routes->match(['get', 'post'], 'cell/new', 'Tesorus::cell_new');
    $routes->match(['get', 'post'], 'cell/new/modal', 'Tesorus::cell_new_modal');
    $routes->match(['get', 'post'], 'cell/update/modal/(:num)', 'Tesorus::cell_update_modal/$1');
    $routes->match(['get', 'post'], 'cell/(:num)/modal/comment', 'Tesorus::CellModalComment/$1');
    $routes->match(['get', 'post'], 'index', 'Tesorus::index');
    $routes->match(['get', 'post'], 'road/edit/(:segment)', 'Tesorus::RoadView/$1');
    $routes->match(['get', 'post'], 'road/delete/(:segment)/(:num)', 'Tesorus::RoadDelete/$1/$2');
    $routes->match(['get', 'post'], 'road/delete/modal/(:segment)/(:num)', 'Tesorus::road_delete_modal/$1/$2');
    $routes->match(['get', 'post'], '(:segment)/road/(:num)/get/ids_road', 'Tesorus::road_get_ids_road/$1/$2');
    $routes->match(['get', 'post'], 'road/new/(:segment)', 'Tesorus::road_save/$1');
    $routes->match(['get', 'post'], 'road/new/modal/(:segment)', 'Tesorus::road_new_modal/$1');
    $routes->match(['get', 'post'], 'road/update/(:segment)/(:num)', 'Tesorus::road_save/$1/$2');
    $routes->match(['get', 'post'], 'road/update/hastext/(:segment)/(:num)', 'Tesorus::RoadSaveHasText/$1/$2');
    $routes->match(['get', 'post'], 'road/update/isActive/(:segment)/(:num)/(:num)', 'Tesorus::RoadSaveIsActive/$1/$2/$3');
    $routes->match(['get', 'post'], 'road/update/modal/(:segment)/(:num)', 'Tesorus::road_update_modal/$1/$2');
    $routes->match(['get', 'post'], 'road/update/rank/(:segment)', 'Tesorus::road_update_rank/$1');
    $routes->match(['get', 'post'], 'road/view/modal/(:segment)/(:segment)', 'Tesorus::road_view_modal/$1/$2');
    $routes->match(['get', 'post'], 'roads', 'Tesorus::RoadList');
    $routes->match(['get', 'post'], 'path/by/road/get/(:segment)/(:num)', 'Tesorus::get_path_by_id_road/$1/$2');
});

