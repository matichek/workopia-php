<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    /**
     * Add new / Register a route
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */

    public function registerRoute($method, $uri, $action)
    {

        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Add a GET route
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function get($uri, $controller)
    {

        $this->registerRoute('GET', $uri, $controller);

    }

    /**
     * Add a POST route
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function post($uri, $controller)
    {

        $this->registerRoute('POST', $uri, $controller);

    }

    /**
     * Add a DELETE route
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function delete($uri, $controller)
    {

        $this->registerRoute('DELETE', $uri, $controller);

    }

    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function route($uri)
    {


        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {

            // Split the current URI into segments

            $uriSegments = explode('/', trim($uri, '/'));

            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            if (count($uriSegments) !== count($routeSegments) && strtoupper($route['method']) !== $requestMethod) {

                $params = [];
                $match = true;

                for ($i = 0; $i < count($routeSegments); $i++) {

                    // if uri don't match and ther is no parameter in the route
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    // check for the param and add to $params array
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];

                    }

                }

                if ($match) {

                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);

                    return;
                }
            }



        }

        $errorController = new ErrorController();
        $errorController->notFound();


    }


}