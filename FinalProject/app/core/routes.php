<?php

use app\controllers\UserController;
use app\controllers\DataController;

$routes = [
    'playlist-generator' => [
        'controller' => UserController::class,
        'GET' => 'spotifyMainpage'
    ],
    'spotify' => [ //show login page
        'controller' => UserController::class,
        'GET' => 'logInUser'
    ],
    'postUsername' => [ //handle username
        'controller' => DataController::class,
        'POST' => 'handleUsername'
    ],
    'login' => [ //login user
        'controller' => DataController::class,
        'GET' => 'login'
    ],
    'playlists' => [ //get user playlists
        'controller' => DataController::class,
        'GET' => 'getPlaylists'
    ],
    'success' => [ //show user playlists
        'controller' => UserController::class,
        'GET' => 'userShowData'
    ],
    'newPlaylist' => [ //show new playlist
        'controller' => UserController::class,
        'GET' => 'newPlaylist'
    ]
];