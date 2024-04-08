<?php

use app\controllers\UserController;
use app\controllers\AuthController;

$routes = [
    'users' => [
        'controller' => UserController::class,
        'GET' => 'getUsers',
        'POST' => 'saveUser'
    ],
    'view-users' => [
        'controller' => UserController::class,
        'GET' => 'getUsers',
    ],
    'spotify' => [
        'controller' => UserController::class,
        'GET' => 'viewUsers',
    ],
    'login' => [
        'controller' => AuthController::class,
        'GET' => 'login',
    ]
];