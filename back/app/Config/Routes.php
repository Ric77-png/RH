<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/', 'Jobs::index');
$routes->get('jobs/(:num)', 'Jobs::show/$1');
$routes->post('jobs/(:num)/apply', 'Jobs::apply/$1');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index');

$routes->group('api', static function ($r) {
    $r->get('rh/needs', 'Rh\RhController::index');
    $r->get('rh/needs/(:segment)', 'Rh\RhController::getDetails/$1');
});

// Routes RH à activer après la création du filtre d'authentification.