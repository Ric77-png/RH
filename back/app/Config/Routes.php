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

$routes->options('api/manager/needs', static function () {
    return service('response')->setStatusCode(204);
});
$routes->group('api', static function ($r) {
    $r->get('rh/needs', 'Rh\RhController::index');
    $r->get('rh/needs/annonces', 'Rh\RhController::getAnnonce');
    $r->get('rh/needs/(:segment)', 'Rh\RhController::getDetails/$1');
    $r->get('manager/departements', 'Manager\DepartementController::index');
    $r->get('manager/needs', 'Manager\BesoinController::index');
    $r->post('manager/needs', 'Manager\BesoinController::store');
    $r->post('manager/departements', 'Manager\DepartementController::index');
    $r->get('manager/needs/statut/(:segment)', 'Manager\BesoinController::byStatut/$1');

});

// Routes RH à activer après la création du filtre d'authentification.