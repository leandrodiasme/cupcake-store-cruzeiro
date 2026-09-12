<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Wizard de Instalação White-Label
$routes->get('install', 'Install::index');
$routes->post('install/process', 'Install::process');

// Catálogo da Loja e APIs de IHC / Checkout
$routes->get('/', 'Home::index');
$routes->post('api/track-click', 'Api::trackClick');
$routes->post('api/create-order', 'Api::createOrder');

// Autenticação Administrativa
$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login/process', 'Admin\Auth::processLogin');
$routes->get('admin/logout', 'Admin\Auth::logout');

// Área Restrita do Painel Administrativo
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Categorias
    $routes->get('categories', 'Admin\Categories::index');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    // Produtos
    $routes->get('products', 'Admin\Products::index');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

    // Configurações da Loja
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');
});
