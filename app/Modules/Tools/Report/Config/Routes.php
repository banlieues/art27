<?php

namespace Report\Config;

$routes = \Config\Services::routes();

$routes->group('report', ['namespace' => 'Report\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Report::index');
    $routes->match(['get', 'post'], 'block/info/collapse/(:num)/(:num)', 'Report::block_info_collapse/$1/$2');
    $routes->match(['get', 'post'], 'block/search', 'Report::block_search');
    $routes->match(['get', 'post'], 'block/search/result', 'Report::block_search_result');
    $routes->match(['get', 'post'], 'block/search/modal/(:num)', 'Report::block_search_modal/$1');
    $routes->match(['get', 'post'], 'delete/(:num)', 'Report::report_delete/$1');
    $routes->match(['get', 'post'], 'delete/modal/(:num)', 'Report::report_delete_modal/$1');
    $routes->match(['get', 'post'], 'download/(:num)', 'Report::report_download/$1');
    $routes->match(['get', 'post'], 'duplicate/(:num)', 'Report::report_duplicate/$1');
    $routes->match(['get', 'post'], 'duplicate/modal/(:num)', 'Report::report_duplicate_modal/$1');
    $routes->match(['get', 'post'], 'index', 'Report::index');
    $routes->match(['get', 'post'], 'parent/blocks/get/(:num)', 'Report::report_parent_blocks_get/$1');
    $routes->match(['get', 'post'], 'parent/thems/get/(:num)', 'Report::report_parent_thems_get/$1');
    $routes->match(['get', 'post'], 'person/linked/get', 'Report::report_person_linked_get');
    $routes->match(['get', 'post'], 'publication/new', 'Report::report_new/publication');
    // $routes->match(['get', 'post'], 'publication/view/(:num)', 'Report::report_view/publication/$1');
    $routes->match(['get', 'post'], 'publications', 'Report::report_list/publication');
    $routes->match(['get', 'post'], 'publications/get', 'Report::reports_get/publications');
    $routes->match(['get', 'post'], 'request/linked/get', 'Report::report_request_linked_get');
    $routes->match(['get', 'post'], 'schema/new', 'Report::report_new/schema');
    // $routes->match(['get', 'post'], 'schema/view/(:num)', 'Report::report_view/schema/$1');
    $routes->match(['get', 'post'], 'schemas', 'Report::report_list/schema');
    $routes->match(['get', 'post'], 'schemas/get', 'Report::reports_get/schema');
    $routes->match(['get', 'post'], 'template/new', 'Report::report_new/template');
    // $routes->match(['get', 'post'], 'template/view/(:num)', 'Report::report_view/template:$1');
    $routes->match(['get', 'post'], 'templates', 'Report::report_list/template');
    $routes->match(['get', 'post'], 'templates/get', 'Report::reports_get/template');
    $routes->match(['get', 'post'], 'view/(:num)', 'Report::report_view/$1');
});

$routes->group('block', ['namespace' => 'Report\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'file/preview/get/(:num)', 'Block::file_preview_get/$1');
});
