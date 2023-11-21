<?php

namespace Demande\Config;

$routes = \Config\Services::routes();

$routes->group('demande', ['namespace' => 'Demande\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Demande::list_demande');
    $routes->match(['get', 'post'], 'index', 'Demande::list_demande');

    $routes->get('list_demande', 'Demande::list_demande');

    $routes->match(['get', 'post'], 'fiche/(:num)', 'Demande::fiche/$1');
    $routes->match(['get', 'post'], 'fiche/(:num)/(:num)', 'Demande::fiche/$1/$2');
    $routes->match(['get', 'post'], 'fiche/(:num)/(:num)/(:num)', 'Demande::fiche/$1/$2/$3');
    $routes->match(['get', 'post'], 'fiche/(:num)/(:num)/(:num)/(:num)', 'Demande::fiche/$1/$2/$3/$4');
    $routes->match(['get', 'post'], 'fiche/(:num)/(:num)/(:num)/(:num)/(:num)', 'Demande::fiche/$1/$2/$3/$4/$5');

    $routes->match(['get', 'post'], 'new/(:any)', 'Demande::new/$1');
    $routes->match(['get', 'post'], 'new/(:any)/(:any)', 'Demande::new/$1/$2');
    $routes->match(['get', 'post'], 'new/(:any)/(:any)/(:any)', 'Demande::new/$1/$2/$3');
    $routes->match(['get', 'post'], 'new/(:any)/(:any)/(:any)/(:any)', 'Demande::new/$1/$2/$3/$4');
    $routes->match(['get', 'post'], 'new/(:any)/(:any)/(:any)/(:any)/(:any)', 'Demande::new/$1/$2/$3/$4/$5');

    $routes->match(['get', 'post'],'save_modelisation', 'Demande::save_modelisation');
    $routes->match(['get', 'post'],'modelisationdemande', 'Demande::modelisationDemande');
    $routes->match(['get', 'post'],'modelisationdemande/(:any)', 'Demande::modelisationDemande/$1');
    $routes->match(['get', 'post'],'modelisationdemande/(:any)/(:any)', 'Demande::modelisationDemande/$1/$2');

    $routes->match(['get', 'post'],'save/(:any)', 'Demande::save/$1');

    $routes->match(['get', 'post'],'set_permanence', 'Demande::set_permanence');

    $routes->match(['get', 'post'], 'delete_bien/(:any)/(:any)', 'Demande::delete_bien/$1/$2');
    $routes->match(['get', 'post'], 'delete_contact/(:any)/(:any)', 'Demande::delete_contact/$1/$2');

    $routes->match(['get', 'post'], 'liste_fil/(:any)', 'Demande::liste_fil/$1');
    $routes->match(['get', 'post'], 'liste_document/(:any)', 'Demande::liste_document/$1');
    $routes->match(['get', 'post'], 'liste_document_join/(:any)', 'Demande::liste_document_join/$1');
    $routes->match(['get', 'post'], 'liste_document_join/(:any)/(:any)', 'Demande::liste_document_join/$1/$2');
    $routes->match(['get', 'post'], 'liste_document_join/(:any)/(:any)/(:any)', 'Demande::liste_document_join/$1/$2/$3');


    $routes->match(['get', 'post'], 'liste_rdv/(:any)', 'Demande::liste_rdv/$1');
    $routes->match(['get', 'post'], 'liste_tache/(:any)', 'Demande::liste_tache/$1');

    $routes->match(['get', 'post'], 'new_web/(:any)', 'Demande::new_web/$1');

    $routes->match(['get', 'post'], 'form_get_demandeurs_possibles/(:any)', 'Demande::form_get_demandeurs_possibles/$1');

    $routes->match(['get', 'post'], 'liste_document_ajouter_demande/(:any)', 'Demande::liste_document_ajouter_demande/$1');

    $routes->match(['get', 'post'], 'liste_document_gerer_demande/(:any)', 'Demande::liste_document_gerer_demande/$1');


    $routes->match(['get', 'post'], 'set_ajouter_document_demande/(:any)/(:any)', 'Demande::set_ajouter_document_demande/$1/$2');

    $routes->match(['get', 'post'],'new_web', 'Demande::new_web');

    $routes->match(['get', 'post'], 'attach_message/(:any)/(:any)', 'Demande::attach_message/$1/$2');

    $routes->match(['get', 'post'], 'associe_message_demande/(:any)', 'Demande::associe_message_demande/$1');

    $routes->match(['get', 'post'], 'change_statut_demande/(:any)', 'Demande::change_statut_demande/$1');


    

});



