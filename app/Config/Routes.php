<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('profile', 'Auth::profile');
$routes->get('profile_view', 'Auth::profile_view');
$routes->get('ministry', 'Ministry::index');
$routes->get('prayers', 'Prayer::index');
$routes->get('attend', 'Attendance::dashboard');
$routes->get('attend_logout', 'Attendance::logout');
$routes->get('privacy', 'Auth::privacy');
$routes->get('first-timer/(:segment)', 'Attendance::timer/$1');
$routes->get('member/(:segment)', 'Attendance::member/$1');
$routes->get('social/facebook', 'SocialAuthController::facebook');
$routes->get('social/facebook/callback', 'SocialAuthController::facebookCallback');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
