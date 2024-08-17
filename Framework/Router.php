<?php

namespace Framework;

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

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {

                // extract  controller and method

                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // instantiate controller and call method

                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();

                return;

            
            }
        }

        $this->error();


    }

    /** 
     * Load error page
     * @param int $httpCode
     * @return void
    */

    public function error($httpCode = 404) {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit();
    }

}