<?php

namespace Mailing\Config;

$routes = \Config\Services::routes();

$routes->group('mailing', ['namespace' => 'Mailing\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Mailing::EmailList');
    $routes->match(['get', 'post'], 'email', 'Mailing::Email');
    $routes->match(['get', 'post'], 'email/sended', 'Mailing::EmailSended');
    $routes->match(['get', 'post'], 'emails', 'Mailing::EmailList');
    $routes->match(['get', 'post'], 'import/template', 'Import::TableTemplate');
    $routes->match(['get', 'post'], 'send/test', 'Mailing::MailingSendTest');
    $routes->match(['get', 'post'], 'send/test/modal/(:num)', 'Mailing::MailingSendTestModal/$1');
    $routes->match(['get', 'post'], 'template/delete/(:num)', 'Template::template_delete/$1');
    $routes->match(['get', 'post'], 'template/delete/modal/(:num)', 'Template::template_delete_modal/$1');
    $routes->match(['get', 'post'], 'template/get/(:num)', 'Template::TemplateGet/$1');
    $routes->match(['get', 'post'], 'template/send/test/notification/new', 'Template::EmailSendTestNotificationNew');
    $routes->match(['get', 'post'], 'template/send/test/notification', 'Template::EmailSendTestNotification');
    $routes->match(['get', 'post'], 'template/send/test/notification/assign', 'Template::EmailSendTestNotificationAssign');
    $routes->match(['get', 'post'], 'template/view', 'Template::TemplateView');
    $routes->match(['get', 'post'], 'template/view/(:num)', 'Template::TemplateView/$1');
    $routes->match(['get', 'post'], 'templates', 'Template::TemplateList');
    $routes->match(['get', 'post'], 'templates/(:any)', 'Template::Templates/$1');
    $routes->match(['get', 'post'], 'variable/import', 'Variable::variable_import');
    $routes->match(['get', 'post'], 'variable/delete/(:num)', 'Variable::variable_delete/$1');
    $routes->match(['get', 'post'], 'variable/delete/modal/(:num)', 'Variable::variable_delete_modal/$1');
    $routes->match(['get', 'post'], 'variable/new', 'Variable::variable_new');
    $routes->match(['get', 'post'], 'variable/new/modal', 'Variable::variable_new_modal');
    $routes->match(['get', 'post'], 'variables', 'Variable::VariableList');
    $routes->match(['get', 'post'], 'variables/get', 'Variable::variables_get');
});
