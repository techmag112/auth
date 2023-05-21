<?php

namespace Tm\Auth\Core;

use \Tm\Auth\Controllers\MainController;
use \Tm\Auth\Controllers\LoginController;
use \Tm\Auth\Controllers\ErrorController;

class Router {

    private array $routes = [
                '/' => [MainController::class],
                '/login' => [LoginController::class],
                '/logout' => [LoginController::class, 'logoutAction'],
                '/register' => [LoginController::class, 'regAction'],
                '/update' => [LoginController::class, 'updateAction'],
                '/changepass' => [LoginController::class, 'changepassAction'],
                '/oauth' => [LoginController::class, 'VKAction'],
    ];

    public function run() {

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        if (false !== $pos = strpos($uri, '?')) {
             $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $action = $this->routes[$uri][1] ?? 'mainAction';
        $controller = new $this->routes[$uri][0] ?? new ErrorController();
        $controller->$action(mb_strtolower($httpMethod));

    }

}