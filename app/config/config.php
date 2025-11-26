<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL configuration
define('BASE_URL', 'http://localhost:8082/news-gate/public/index.php');

// Autoload classes
spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/../models/' . $class_name . '.php',
        __DIR__ . '/../controllers/' . $class_name . '.php',
        __DIR__ . '/' . $class_name . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
