<?php
/**
 * This code snippet is the entry point of the application.
 * It includes necessary files, initializes the router, and handles the incoming request.
 *
 * @file index.php
 * @package worktopia
 */

// Include helper functions
require '../helpers.php';

// Include necessary classes
require basePath('Database.php');
require basePath('Router.php');

// Initialize the router
$router = new Router();

// Load the routes
$routes = require basePath('routes.php');

// Get the current URI and request method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);