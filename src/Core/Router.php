<?php

namespace MediaExpert\Backend\Core;

use MediaExpert\Backend\Exceptions\AbortException;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller, $function)
    {
        $config = require BASE_PATH . ('config.php');
        $this->routes[] = [
            'uri' => str_replace("//", "/", $config["default_route"] . $uri),
            'controller' => $controller,
            'method' => $method,
            'function' => $function
        ];
        return $this;
    }

    public function get($uri, $controller, $function)
    {
        return $this->add('GET', $uri, $controller, $function);
    }

    public function post($uri, $controller, $function)
    {
        return $this->add('POST', $uri, $controller, $function);
    }

    public function delete($uri, $controller, $function)
    {
        return $this->add('DELETE', $uri, $controller, $function);
    }

    public function patch($uri, $controller, $function)
    {
        return $this->add('PATCH', $uri, $controller, $function);
    }

    public function put($uri, $controller, $function)
    {
        return $this->add('PUT', $uri, $controller, $function);
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route)
        {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method))
            {
                $_SESSION['function'] = $route['function'];
                $_SESSION['controller'] = $route['controller'];
                return require BASE_PATH . "src/Http/controllers/RunController.php";
            }
        }
        throw new AbortException();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
