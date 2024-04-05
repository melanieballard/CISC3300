<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller
{

    public function homepage()
    {
        include '../public/assets/views/main/homepage.php';
        //$this->view('../public/assets/views/main/homepage.php', true);
    }

    public function notFound()
    {
    }

}