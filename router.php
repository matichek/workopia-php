<?php

$routes = require basePath('routes.php');

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