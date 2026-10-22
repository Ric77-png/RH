<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/', 'Jobs::index');
$routes->get('jobs/(:num)', 'Jobs::show/$1');
$routes->post('jobs/(:num)/apply', 'Jobs::apply/$1');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->post('logout', 'AuthController::logout');
$routes->post('register', 'AuthController::register');
$routes->post('login/attempt', 'AuthController::attempt');
$routes->get('dashboard', 'Dashboard::index');

$routes->group('api', static function ($r) {
    $r->post('login', 'AuthController::login');
    $r->post('register', 'AuthController::register');
    $r->post('logout', 'AuthController::logout');
    $r->get('rh/needs', 'Rh\RhController::index');
    $r->get('rh/needs/(:segment)', 'Rh\RhController::getDetails/$1');
});

// Routes RH à activer après la création du filtre d'authentification.