<?php

namespace app\controllers;
use app\core\Controller;
use app\models\User;

class UserController extends Controller
{
    public function index()
    {
        $userModel = new User();
        //the load method will start at the public/assets/views directory
        //you can view the set up in the app/core/Controller class
        $template = $this->twig->load('users/users.twig');
        $homepageData = [
            'users' => $userModel->getAllUsers(),
        ];
        echo $template->render($homepageData);
    }

}