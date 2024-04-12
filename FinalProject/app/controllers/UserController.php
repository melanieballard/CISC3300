<?php

namespace app\controllers;
use app\core\Controller;
use app\models\User;

class UserController extends Controller
{
    public function getUsers()
    {
        $userModel = new User();
        header("Content-Type: application/json");
        $users = $userModel->getAllUsers();
        echo json_encode($users);
        exit();
    }

    public function saveUser() {
    }

    public function logInUser() {
        include '../public/assets/views/spotify/userInfo.php';
    }

    public function userShowData(){
        include '../public/assets/views/spotify/showPlaylists.php';
    }

}