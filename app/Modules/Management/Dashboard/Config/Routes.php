<?php
namespace Dashboard\Config;

// Create a new instance of our RouteCollection class.
$routes = \Config\Services::routes();
/* Generator Model */
$routes->group('dashboard', ['namespace' => 'Dashboard\Controllers','filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->get('/', 'Dashboard::index');
    $routes->get('index', 'Dashboard::index');

    $routes->match(["get", "post"], "index/(:any)", "Dashboard::index/$1");
    $routes->match(["get", "post"], "index/(:any)/(:any)", "Dashboard::index/$1/$2");
    $routes->match(["get", "post"], "index/(:any)/(:any)/(:any)", "Dashboard::index/$1/$2/$3");



    $routes->match(["get", "post"], "load_table/(:any)", "Dashboard::load_table/$1");
    $routes->match(["get", "post"], "load_table/(:any)/(:any)", "Dashboard::load_table/$1/$2");

    $routes->match(["get", "post"], "load_table_data_ajax/(:any)", "Dashboard::load_table_data_ajax/$1");


    $routes->match(["get", "post"], "form_insert_onglet", "Dashboard::form_insert_onglet");
    $routes->match(["get", "post"], "maj_requete_orig", "Dashboard::maj_requete_orig");
    $routes->match(["get", "post"], "delete_table", "Dashboard::delete_table");
    $routes->match(["get", "post"], "delete_onglet", "Dashboard::delete_onglet");
    $routes->match(["get", "post"], "dupliquer_table", "Dashboard::dupliquer_table");
    $routes->match(["get", "post"], "form_insert", "Dashboard::form_insert");
    $routes->match(["get", "post"], "form_update", "Dashboard::form_update");

    $routes->match(["get", "post"], "form_update_onglet", "Dashboard::form_update_onglet");


    $routes->match(["get", "post"], "get_field/(:any)", "Dashboard::get_field/$1");
    $routes->match(["get", "post"], "get_filtre/(:any)", "Dashboard::get_filtre/$1");

    $routes->match(["get", "post"], "update_color_background", "Dashboard::update_color_background");
    $routes->match(["get", "post"], "update_color_font", "Dashboard::update_color_font");


    $routes->match(["get", "post"], "update_len", "Dashboard::update_len");
    $routes->match(["get", "post"], "update_order", "Dashboard::update_order");
    $routes->match(["get", "post"], "update_sortable", "Dashboard::update_sortable");
    $routes->match(["get", "post"], "update_sortable_onglet", "Dashboard::update_sortable_onglet");

    $routes->match(["get", "post"], "update_sortable_field/(:any)", "Dashboard::update_sortable_field/$1");


    $routes->match(["get", "post"], "ajouter_onglet/(:any)", "Dashboard::ajouter_onglet/$1");
    $routes->match(["get", "post"], "ajouter_onglet/(:any)/(:any)", "Dashboard::ajouter_onglet/$1/$2");

    $routes->match(["get", "post"], "modifier_onglet/(:any)/(:any)", "Dashboard::modifier_onglet/$1/$2");


    $routes->match(["get", "post"], "ajouter/(:any)/(:any)", "Dashboard::ajouter/$1/$2");

    $routes->match(["get", "post"], "deplacer/(:any)/(:any)", "Dashboard::deplacer/$1/$2");

    

});


