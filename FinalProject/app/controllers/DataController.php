<?php

namespace app\controllers;
use app\core\Controller;
use app\models\Data;

$_ENV = parse_ini_file(filename: '../.env');

class DataController extends Controller {

    public function login(){
        $userLogin = new Data();
        $userToken = $userLogin->authenticate();
        return $userToken;
    }

    public function getPlaylists(){
        $userToken = $this->login();
        $newData = new Data();

        $userPlaylists = $newData->playlists($userToken);

        echo "User's Playlists:\n";
        foreach ($userPlaylists['items'] as $playlist) {
            echo $playlist['name'] . "\n";
        }

    }

}