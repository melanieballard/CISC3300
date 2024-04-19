<?php

namespace app\controllers;
use app\core\Controller;
use app\models\User;

class UserController extends Controller
{
    public function logInUser() {
        include '../public/assets/views/spotify/userInfo.php'; //show user login page
    }

    public function userShowData(){
        include '../public/assets/views/spotify/showPlaylists.php'; //show user playlists
    }

    public function newPlaylist(){
        include '../public/assets/views/spotify/recPlaylist.php'; //show reccomended playlist
    }
}