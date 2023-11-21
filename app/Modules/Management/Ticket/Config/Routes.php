<?php

namespace Ticket\Config;

$routes = \Config\Services::routes();



$routes->group('ticket', ['namespace' => 'Ticket\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    
    $routes->match(['get', 'post'], '/', 'Ticket::listTicket');
    $routes->match(['get', 'post'], 'index', 'Ticket::listTicket');

    $routes->match(['get', 'post'], 'listTicket', 'Ticket::listTicket');
    $routes->match(['get', 'post'], 'listTicket/(:any)', 'Ticket::listTicket/$1');
    $routes->match(['get', 'post'], 'listTicket/(:any)/(:any)', 'Ticket::listTicket/$1/$2');

    $routes->match(['get', 'post'], 'decoup_pdf_dompdf', 'Ticket::decoup_pdf_dompdf');
    $routes->match(['get', 'post'], 'decoup_pdf_mpdf', 'Ticket::decoup_pdf_mpdf');
    $routes->match(['get', 'post'], 'decoup_pdf_imagick', 'Ticket::decoup_pdf_imagick');

    $routes->match(['get', 'post'], 'upload_file', 'Ticket::upload_file');

    $routes->match(['get', 'post'], 'list_document', 'Ticket::listTicket');


    $routes->match(['get', 'post'], 'fiche/(:any)', 'Ticket::fiche/$1');

    $routes->match(['get', 'post'], 'setTypeTicket/(:any)', 'Ticket::setTypeTicket/$1');
    $routes->match(['get', 'post'], 'setCommentaire', 'Ticket::setCommentaire');
    $routes->match(['get', 'post'], 'setNumCode', 'Ticket::setNumCode');

    $routes->match(['get', 'post'], 'liste_demande/(:any)', 'Ticket::liste_demande/$1');

    $routes->match(['get', 'post'], 'associe_demande/(:any)', 'Ticket::associe_demande/$1');


    $routes->match(['get', 'post'], 'setStatut/(:any)', 'Ticket::setStatut/$1');







});


