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
        $userData = [
            'UserID' => $_POST['UserID'] ? $_POST['UserID'] : false,
            'Email' => $_POST['Email'] ? $_POST['Email'] : false,
            'SpotifyURI' => $_POST['SpotifyURI'] ? $_POST['SpotifyURI'] : false,
        ];

        //TODO 5-b: save a post
        $userModel = new User();
        $userModel->saveUser( 
            [
            'UserID' => $userData['UserID'],
            'Email' => $userData['description'],
            'SpotifyURI' => $userData['SpotifyURI'],
            ]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function viewUsers() {
        include '../../public/assets/views/spotify/userInfo.php';
    }

}