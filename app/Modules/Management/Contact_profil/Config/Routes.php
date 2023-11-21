<?php

namespace Contact_profil\Config;

$routes = \Config\Services::routes();

$routes->group('contact_profil', ['namespace' => 'Contact_profil\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
  

    $routes->match(['get', 'post'],'save_modelisation', 'Contact_profil::save_modelisation');
    $routes->match(['get', 'post'],'modelisationcontact_profil', 'Contact_profil::modelisationContact_profil');
    $routes->match(['get', 'post'],'modelisationcontact_profil/(:any)', 'Contact_profil::modelisationContact_profil/$1');
    $routes->match(['get', 'post'],'modelisationcontact_profil/(:any)/(:any)', 'Contact_profil::modelisationContact_profil/$1/$2');

  

});


