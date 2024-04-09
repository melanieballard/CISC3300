<?php

namespace app\core;

use app\controllers\MainController;
use app\controllers\DataController;

class Router
{
    public $routeList;
    function __construct($routes)
    {
        $this->routeList = $routes;
    }

    public function serveRoute() {
        $uri = $_SERVER['REQUEST_URI'];
        $uriParse = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $method =  $_SERVER['REQUEST_METHOD'];

        if (strpos($uri, '/callback') === 0) {
            $this->handleSpotifyCallback();
        }else{
            if ($uriParse[0]) {
            $route = $this->routeList[$uriParse[0]];
            if ($route) {
                $controller = $route['controller'];
                $action = $route[$method];
                $controller = new $controller();
                $controller->$action();
            } else {
                $homepageController = new MainController();
                $homepageController->notFound();
            }
        } else {
            $homepageController = new MainController();
            $homepageController->homepage();
        }
        }
    }

    public function handleSpotifyCallback(){
        $dataController = new DataController();
        $dataController->callback();
    }
}