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

    public function navbar()
    {
        include '../public/assets/views/main/navbar.php'; //navbar header
    }

    public function aboutMe()
    {
        include '../public/assets/views/aboutMe/aboutMe.php'; //navbar header
    }
    public function contactMe()
    {
        include '../public/assets/views/aboutMe/contactMe.php'; //navbar header
    }

    public function resume()
    {
        include '../public/assets/views/aboutMe/resume.php'; //navbar header
    }


}