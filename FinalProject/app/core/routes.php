<?php

use app\controllers\UserController;

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
    ]
];