<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Load Router
require_once __DIR__ . '/../app/Router.php';

// Create router instance
$router = new Router();

// Frontend routes
$router->add('/', 'HomeController', 'index');
$router->add('/login', 'AuthController', 'showLogin');
$router->add('/register', 'AuthController', 'showRegister');
$router->add('/logout', 'AuthController', 'logout');
$router->add('/news', 'NewsListController', 'index');
$router->add('/news/detail', 'NewsDetailController', 'detail');

// Admin routes
$router->add('/admin', 'AdminAuthController', 'dashboard');
$router->add('/admin/login', 'AdminAuthController', 'showLogin');
$router->add('/admin/logout', 'AdminAuthController', 'logout');
$router->add('/admin/dashboard', 'AdminAuthController', 'dashboard');

// Category routes (Admin only)
$router->add('/admin/categories', 'CategoryController', 'index');
$router->add('/admin/categories/create', 'CategoryController', 'create');
$router->add('/admin/categories/edit', 'CategoryController', 'edit');
$router->add('/admin/categories/delete', 'CategoryController', 'delete');

// User routes (Admin only)
$router->add('/admin/users', 'UserController', 'index');
$router->add('/admin/users/create', 'UserController', 'create');
$router->add('/admin/users/edit', 'UserController', 'edit');
$router->add('/admin/users/delete', 'UserController', 'delete');

// News routes (Admin/Editor)
$router->add('/admin/news', 'NewsController', 'index');
$router->add('/admin/news/create', 'NewsController', 'create');
$router->add('/admin/news/edit', 'NewsController', 'edit');
$router->add('/admin/news/delete', 'NewsController', 'delete');

// Handle POST request for login (user)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'login') {
    $authController = new AuthController();
    $authController->login();
    exit;
}

// Handle POST request for register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'register') {
    $authController = new AuthController();
    $authController->register();
    exit;
}

// Handle POST request for admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/login') {
    $adminAuthController = new AdminAuthController();
    $adminAuthController->login();
    exit;
}

// Handle POST request for category store
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/categories/store') {
    $categoryController = new CategoryController();
    $categoryController->store();
    exit;
}

// Handle POST request for category update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/categories/update') {
    $categoryController = new CategoryController();
    $categoryController->update();
    exit;
}

// Handle POST request for user store
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/users/store') {
    $userController = new UserController();
    $userController->store();
    exit;
}

// Handle POST request for user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/users/update') {
    $userController = new UserController();
    $userController->update();
    exit;
}

// Handle POST request for news store
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/news/store') {
    $newsController = new NewsController();
    $newsController->store();
    exit;
}

// Handle POST request for news update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['route']) && $_GET['route'] === 'admin/news/update') {
    $newsController = new NewsController();
    $newsController->update();
    exit;
}

// Dispatch the route
$router->dispatch();
