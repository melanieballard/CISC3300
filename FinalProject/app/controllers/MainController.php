<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller
{

    public function homepage()
    {
        include '../public/assets/views/main/mainpage.php'; //homepage view
    }

    public function notFound()
    {
        include '../public/assets/views/main/notFound.php'; //not found view
    }

}