<?php
/**
 * This is Administrator Module Routes 
**/

namespace Administrator\Config;

$routes = \Config\Services::routes();

$routes->group('administrator', ['namespace' => 'Administrator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->get('/', 'Administrator::index');
    $routes->get('index', 'Administrator::index');
    // $routes->get('cropper', 'Administrator::cropper');
    // $routes->post('cropper', 'Administrator::cropper');
    // $routes->get('paths', 'Administrator::paths');
    $routes->get('settings', 'Administrator::settings');
    // $routes->get('edit', 'Administrator::edit');

    // $routes->get('details', 'Administrator::details');
    // $routes->get('memberslist', 'Administrator::memberslist');
    // $routes->get('profileslist', 'Administrator::profileslist');
    // $routes->get('imageslist', 'Administrator::imageslist');
});

// $routes->group('cropper', ['namespace' => 'Administrator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
// {
//     // Add all routes need protected by this filter
//     $routes->get('/', 'Cropper::index');
//     $routes->get('index', 'Cropper::index');
//     // $routes->get('settings', 'Cropper::settings');
//     // $routes->post('settings', 'Cropper::settings');
//     // $routes->get('add', 'Cropper::add');
//     // $routes->get('edit', 'Cropper::edit');
//     // $routes->get('delete', 'Cropper::delete');
//     $routes->match(['get', 'post'], 'settings', 'Cropper::settings');

// });

$routes->group('login', ['namespace' => 'Administrator\Controllers'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Identification::login');
    $routes->match(['get', 'post'], 'index', 'Identification::login');
   
});

$routes->group('identification', ['namespace' => 'Administrator\Controllers'], function($routes)
{
    $routes->match(['get', 'post'], '/', 'Identification::login');
    $routes->match(['get', 'post'], 'index', 'Identification::login');
    $routes->match(['get', 'post'], 'forgot', 'Identification::forgot');
    $routes->match(['get', 'post'], 'login', 'Identification::login');
    $routes->match(['get', 'post'], 'logout', 'Identification::logout');
    $routes->match(['get', 'post'], 'logout_distance', 'Identification::logout_distance');

    $routes->match(['get', 'post'], 'register', 'Identification::register');
    $routes->match(['get', 'post'], 'reset', 'Identification::reset');
    $routes->match(['get', 'post'], 'reset_procedure', 'Identification::reset_procedure');
    $routes->match(['get', 'post'], 'reverify', 'Identification::reverify');
    $routes->match(['get', 'post'], 'verify', 'Identification::verify');
});

$routes->group('member', ['namespace' => 'Administrator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'User::UserList');
    $routes->match(['get', 'post'], 'activate', 'Member::activate');
    // $routes->match(['get', 'post'], 'add', 'Member::add');
    $routes->match(['get', 'post'], 'delete', 'Member::delete');
    $routes->match(['get', 'post'], 'details', 'Member::details');
    $routes->match(['get', 'post'], 'edit', 'Member::edit');
    $routes->match(['get', 'post'], 'export', 'Member::export');
    $routes->match(['get', 'post'], 'index', 'Member::index');
    $routes->match(['get', 'post'], 'save', 'Member::save');
    $routes->match(['get', 'post'], 'valid', 'Member::valid');
});

$routes->group('user', ['namespace' => 'Administrator\Controllers', 'filter' => 'UserRolesCheck:1'], function($routes)
{
    $routes->match(['get', 'post'], 'list', 'User::UserList');
});

$routes->group('user', ['namespace' => 'Administrator\Controllers', 'filter' => 'IdentificationCheck'], function($routes)
{
    // Add all routes need protected by this filter
    $routes->match(['get', 'post'], '/', 'User::index');
    $routes->match(['get', 'post'], 'add', 'User::UserNew');
    $routes->match(["get", "post"], "autorisation", "User::UserAutorisation");
    // $routes->match(["get", "post"], "autorisation/edit", "Autorisation::edit");
    $routes->match(['get', 'post'], 'avatar', 'User::UserAvatarSave');
    // $routes->match(['get', 'post'], 'avatar', 'User::AvatarView');
    // $routes->match(['get', 'post'], 'avatar/cropper', 'User::AvatarCropper');
    $routes->match(['get', 'post'], 'confidentiality', 'User::confidentiality');
    // $routes->match(['get', 'post'], 'contact/unlink/(:num)/(:num)', 'User::contact_unlink/$1/$2');
    // $routes->match(['get', 'post'], 'contact/view/(:num)', 'User::contact_view/$1');
    // $routes->match(['get', 'post'], 'contacts/link', 'User::contacts_link');
    // $routes->match(['get', 'post'], 'contacts/link/(:num)', 'User::contacts_link/$1');
    // $routes->match(['get', 'post'], 'contacts/link/set', 'User::contacts_link_set');
    // $routes->match(['get', 'post'], 'contacts/list', 'User::contacts_list');
    // $routes->match(['get', 'post'], 'edit', 'User::edit');
    // $routes->match(['get', 'post'], 'form_registration', 'User::form_registration');
    // $routes->match(['get', 'post'], 'form_registration/(:any)', 'User::form_registration/$1');
    // $routes->match(['get', 'post'], 'form_registration/(:any)/(:any)', 'User::form_registration/$1/$2');
    // $routes->match(['get', 'post'], 'form_registration/(:any)/(:any)/(:any)', 'User::form_registration/$1/$2/$3');
    // $routes->match(['get', 'post'], 'form_registration/(:any)/(:any)/(:any)/(:any)', 'User::form_registration/$1/$2/$3/$4');

    // $routes->match(['get', 'post'], 'getDocumentsInscriptions/(:any)', 'User::getDocumentsInscriptions/$1');
    $routes->match(['get', 'post'], 'getFile/(:any)', 'User::getFile/$1');
    $routes->match(['get', 'post'], 'password', 'User::password');
    // $routes->match(['get', 'post'], 'password/index/(:num)', 'User::password/$1');
    $routes->match(['get', 'post'], 'profile', 'User::UserProfile');
    // $routes->match(['get', 'post'], 'profile/edit', 'User::ProfileEdit');
    // $routes->match(['get', 'post'], 'profile/save', 'User::ProfileSave');
    $routes->match(['get', 'post'], 'save_registration', 'User::save_registration');
    // $routes->match(['get', 'post'], 'save_updateContact', 'User::save_updateContact');
    $routes->match(['get', 'post'], 'seeFile/(:any)', 'User::seeFile/$1');
    // $routes->match(['get', 'post'], 'updateContact/(:any)', 'User::updateContact/$1');
});

// $routes->group('user/avatar', ['namespace' => 'Administrator\Controllers', 'filter' => 'IdentificationCheck'], function($routes)
// {
//     // Add all routes need protected by this filter
//     // $routes->match(['get', 'post'], 'index', 'Avatar::index');
//     // $routes->match(['get', 'post'], 'index/(:num)', 'Avatar::index/$1');
//     // $routes->match(['get', 'post'], 'cropper', 'Avatar::cropper');
//     // $routes->match(['get', 'post'], 'cropper/(:num)', 'Avatar::cropper/$1');
//    // $routes->post('cropper/(:any)', 'Avatar::cropper/$1');

// });

// $routes->group('user/profile', ['namespace' => 'Administrator\Controllers', 'filter' => 'IdentificationCheck'], function($routes)
// {
// });

