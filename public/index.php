<?php
/**
 * This code snippet is the entry point of the application.
 * It includes necessary files, initializes the router, and handles the incoming request.
 *
 * @file index.php
 * @package worktopia
 */

// Include helper functions
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';
// Include necessary classes

use Framework\Router;


// Initialize the router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get the current URI and request method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);