<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/', 'Jobs::index');
$routes->get('jobs/(:num)', 'Jobs::show/$1');
$routes->post('jobs/(:num)/apply', 'Jobs::apply/$1');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'role']);

$routes->group('manager', ['filter' => 'role:manager', 'namespace' => 'App\Controllers\Manager'], static function ($r) {
    $r->get('needs', 'Needs::index');
    $r->get('needs/new', 'Needs::new');
    $r->post('needs', 'Needs::create');
});