<?php

namespace Enquete\Config;

$routes = \Config\Services::routes();

// External link
$routes->group('enquete/external', ['namespace' => 'Enquete\Controllers'], function($routes) {
    $routes->match(['get', 'post'], 'form/(:segment)/(:num)/(:num)/(:segment)', 'External::AnswerForm/$1/$2/$3/$4');
    $routes->match(['get', 'post'], 'save/(:segment)/(:num)/(:num)/(:segment)', 'External::AnswerSave/$1/$2/$3/$4');
});

$routes->group('enquete', ['namespace' => 'Enquete\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'Answer::SendList');
    $routes->match(['get', 'post'], 'answer/(:num)/modal', 'Answer::AnswerViewModal/$1');
    $routes->match(['get', 'post'], 'answer/view/(:num)/suggestions', 'Answer::AnswerViewModalSuggestions/$1');
    $routes->match(['get', 'post'], 'sends', 'Answer::SendList');
    $routes->match(['get', 'post'], 'sends/(:num)', 'Answer::SendList/$1');
    $routes->match(['get', 'post'], 'answers', 'Answer::AnswerList');
    $routes->match(['get', 'post'], 'answers/(:num)', 'Answer::AnswerList/$1');
    $routes->match(['get', 'post'], 'answers/by/question/(:num)/(:alpha)', 'Filter::AnswersByQuestion/$1/$2');
    // $routes->match(['get', 'post'], 'answers/paginate/get', 'Answer::answers_paginate_get');
    $routes->match(['get', 'post'], 'chart/(:num)/param', 'Graph::ChartParamGet/$1');
    $routes->match(['get', 'post'], 'demande/close/test', 'Answer::DemandeCloseTest');
    $routes->match(['get', 'post'], 'filter/modal/(:segment)', 'Filter::FilterModal/$1');
    $routes->match(['get', 'post'], 'filter/modal/(:segment)/(:segment)', 'Filter::FilterModal/$1/$2');
    $routes->match(['get', 'post'], 'filter/get', 'Filter::filter_get');
    $routes->match(['get', 'post'], 'filter/set', 'Filter::filter_set');
    $routes->match(['get', 'post'], 'form/update/(:num)', 'Enquete::form_update/$1');
    $routes->match(['get', 'post'], 'enquete/(:num)/modal', 'Enquete::EnqueteViewModal/$1');
    $routes->match(['get', 'post'], 'enquete/(:num)/iframe/(:segment)', 'Enquete::EnqueteIframe/$1/$2');
    $routes->match(['get', 'post'], 'enquetes', 'Enquete::EnqueteList');
    $routes->match(['get', 'post'], 'charts', 'Graph::ChartList');
    $routes->match(['get', 'post'], 'charts/(:num)', 'Graph::ChartList/$1');
    $routes->match(['get', 'post'], 'graph/modal/(:alpha)/(:num)', 'Graph::ChartModal/$1/$2');
    $routes->match(['get', 'post'], 'question/(:num)/delete', 'Enquete::QuestionDelete/$1');
    $routes->match(['get', 'post'], 'question/(:num)/modal', 'Enquete::QuestionModal/$1');
    $routes->match(['get', 'post'], 'question/(:num)/update', 'Enquete::QuestionUpdate/$1');
    $routes->match(['get', 'post'], 'question/new', 'Enquete::QuestionNew');
    $routes->match(['get', 'post'], 'question/new/modal', 'Enquete::QuestionModal');
    $routes->match(['get', 'post'], 'questions', 'Enquete::QuestionList');
    // $routes->match(['get', 'post'], 'summary', 'Answer::summary');
    // $routes->match(['get', 'post'], 'summary/(:alpha)', 'Answer::summary/$1');
    // $routes->match(['get', 'post'], 'summary/paginate/get', 'Answer::summary_paginate_get');
    $routes->match(['get', 'post'], 'trend', 'Graph::TrendView');
    $routes->match(['get', 'post'], 'trend/(:segment)', 'Graph::TrendView/$1');
    $routes->match(['get', 'post'], 'trend/(:segment)/(:num)', 'Graph::TrendView/$1/$2');
    $routes->match(['get', 'post'], 'trend/param/get/(:alpha)', 'Graph::trend_param_get/$1');
    $routes->match(['get', 'post'], 'trend/param/get/(:alpha)/(:alpha)', 'Graph::trend_param_get/$1/$2');
    $routes->match(['get', 'post'], 'trends', 'Graph::TrendView');
    // $routes->match(['get', 'post'], 'trends', 'Graph::TrendList');
    // $routes->match(['get', 'post'], 'trends/(:num)', 'Graph::TrendList/$1');
});