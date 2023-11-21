<?php
namespace Import\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('import', ['namespace' => 'Import\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'Import::index');
    $routes->get('index', 'Import::index');
    $routes->match(["get", "post"], "index", "Import::index");

    $routes->get('execute', 'Import::execute');
    $routes->match(["get", "post"], "execute", "Import::execute");

    $routes->get("table_importation/(:any)","Import::table_importation/$1");

    $routes->get("retirer_importation/(:any)/(:any)","Import::retirer_importation/$1/$2");

    $routes->get('value_select', 'Import::value_select');
    $routes->match(["get", "post"], "value_select", "Import::value_select");

    // $routes->get('homegrade/to/h4', 'HomegradeToH4::ImportByTamo');

    $routes->get('homegrade/to/import_jer', 'HomegradeToH4::Import_jer');

    $routes->get('homegrade/to/rapport_total', 'HomegradeToH4::rapport_total');

    $routes->get('homegrade/to/nettoye_champ_contact', 'HomegradeToH4::nettoye_champ_contact');

    $routes->get('homegrade/to/set_table_h', 'HomegradeToH4::set_table_h');

    $routes->get('homegrade/to/modify_index', 'HomegradeToH4::modify_index');

    $routes->get('homegrade/to/create_index_demande_caracteristique', 'HomegradeToH4::create_index_demande_caracteristique');
    
    
    $routes->get('homegrade/to/create_index', 'HomegradeToH4::create_index');

    $routes->get('homegrade/to/traduire_query', 'HomegradeToH4::traduire_query');

    $routes->get('homegrade/to/mdp_encodage', 'HomegradeToH4::mdp_encodage');


    
  
  
});