<?php

require '../helpers.php';

$routes = [
    '/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listings/create.php',
    '404' => 'controllers/error/404.php'
];

$uri = $_SERVER['REQUEST_URI'];

if (array_key_exists($uri, $routes)) {
    $path = basePath($routes[$uri]);
    if (file_exists($path)) {
        require $path;
    } else {
        echo "Error: File not found - " . $path;
    }
} else {
    $path = basePath($routes['404']);
    if (file_exists($path)) {
        require $path;
    } else {
        echo "Error: 404 File not found - " . $path;
    }
}