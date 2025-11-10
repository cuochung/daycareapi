<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);

$routes->get('/', 'News::index');
// // $routes->get('/general/(:any)', 'General::index');
// $routes->get('/news', 'News::index');
// $routes->add('/news/(:alphanum)', 'News::create/$1');
// $routes->add('/news/update/(:num)', 'News::update/$1');
// $routes->add('/news/delete/(:num)', 'News::delete/$1');
