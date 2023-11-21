<?php

namespace Calculator\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();

/* Generator Model */
$routes->group('calculator', ['namespace' => 'Calculator\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    // $routes->match(["get", "post"], '(:num)', 'Client::DevisList/$1');
    // $routes->match(["get", "post"], 'demande/(:num)/devis/new', 'Client::DevisMesurage/$1');
    // $routes->match(["get", "post"], 'demande/(:num)/devis/(:num)/estimate', 'Client::DevisEstimate/$1/$2');
    // $routes->match(["get", "post"], 'demande/(:num)/devis/(:num)/mesurage', 'Client::DevisMesurage/$1/$2');
    // $routes->match(["get", "post"], 'demande/(:num)/devis/(:num)/print', 'Client::DevisPrint/$1/$2');
    // $routes->match(["get", "post"], 'demande/(:num)/devis/(:num)/print/view', 'Client::DevisPrintView/$1/$2');

    $routes->match(["get", "post"], '/', 'Admin::RoadList');
    $routes->match(["get", "post"], 'devis', 'Client::DevisList');
    $routes->match(["get", "post"], 'devis/(:num)', 'Client::DevisView/$1');
    $routes->match(["get", "post"], 'devis/(:num)/mesurage', 'Client::DevisMesurage/$1');
    $routes->match(["get", "post"], 'devis/(:num)/print', 'Client::DevisPrint/$1');
    // $routes->match(["get", "post"], 'estimation/import', 'Admin::EstimationImport');
    // $routes->match(["get", "post"], 'devis/group/(:num)', 'Client::DevisGroup/$1');
    $routes->match(["get", "post"], 'devis/groups', 'Client::DevisGroups');
    $routes->match(["get", "post"], 'devis/nav/new', 'Client::DevisNavNew');
    $routes->match(["get", "post"], 'devis/work', 'Client::DevisWork');
    $routes->match(["get", "post"], 'devis/work/modal', 'Client::DevisWorkModal');
    $routes->match(["get", "post"], 'devis/work/modal', 'Client::DevisWorkModal');
    // $routes->match(["get", "post"], 'estimation/update/isactive/(:num)/(:num)', 'Admin::EstimationUpdateIsActive/$1/$2');
    // $routes->match(["get", "post"], 'estimation/update/rank/(:num)', 'Admin::EstimationUpdateRank/$1');
    // $routes->match(["get", "post"], 'estimations', 'Admin::EstimationList');
    $routes->match(["get", "post"], 'export/sql', 'Admin::ExportSql');
    $routes->match(["get", "post"], 'group/(:num)', 'Group::GroupView/$1');
    $routes->match(["get", "post"], 'group/(:num)/delete', 'Group::GroupDelete/$1');
    $routes->match(["get", "post"], 'group/(:num)/road/(:num)/delete', 'Group::GroupViewRoadDelete/$1/$2');
    $routes->match(["get", "post"], 'group/(:num)/roads/new', 'Group::GroupViewRoadsNew/$1');
    $routes->match(["get", "post"], 'group/(:num)/roads/unlink', 'Group::GroupViewRoadsUnlink/$1');
    $routes->match(['get', 'post'], 'group/(:num)/get/path', 'Group::GroupGetPath/$1');
    $routes->match(['get', 'post'], 'group/(:num)/update/active/(:num)', 'Group::GroupUpdateActive/$1/$2');
    $routes->match(['get', 'post'], 'group/new', 'Group::GroupNew');
    $routes->match(['get', 'post'], 'group/new/modal', 'Group::GroupNewModal');
    $routes->match(['get', 'post'], 'group/update/rank', 'Group::GroupUpdateRank');
    $routes->match(['get', 'post'], 'group/tag/modal', 'Group::GroupTagModal');
    $routes->match(["get", "post"], 'groups', 'Group::GroupList');
    // $routes->match(["get", "post"], 'groups/export/csv', 'Group::GroupsExportCsv');
    $routes->match(["get", "post"], 'import', 'Import::Import');
    $routes->match(["get", "post"], 'price/(:num)/delete', 'Admin::PriceDelete/$1');
    $routes->match(["get", "post"], 'prices/export', 'Admin::PricesExport');
    $routes->match(["get", "post"], 'road/(:num)', 'Admin::RoadView/$1');
    $routes->match(["get", "post"], 'road/(:num)/delete', 'Admin::RoadDelete/$1');
    $routes->match(["get", "post"], 'roads', 'Admin::RoadList');
    $routes->match(["get", "post"], 'roads/edit', 'Admin::RoadsEdit');
    $routes->match(["get", "post"], 'roads/export', 'Admin::RoadsExport');
    $routes->match(["get", "post"], 'tesorus', 'Admin::RoadsTesorus');
    $routes->match(["get", "post"], 'work/(:num)/delete', 'Client::WorkDelete/$1');
});


