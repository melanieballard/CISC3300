<?php

use app\controllers\UserController;
use app\controllers\DataController;

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
        'GET' => 'logInUser',
    ],
    'postUsername' => [
        'controller' => DataController::class,
        'POST' => 'handleUsername'
    ],
    'login' => [
        'controller' => DataController::class,
        'GET' => 'login',
    ],
    'playlists' => [
        'controller' => DataController::class,
        'GET' => 'getPlaylists'
    ],
    'success' => [
        'controller' => UserController::class,
        'GET' => 'userShowData'
    ],
    'reccomended' => [
        'controller' => UserController::class,
        'GET' => 'newPlaylist'
    ]
];