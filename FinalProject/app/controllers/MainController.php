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

    public function colorMode()
    {
        include '../public/assets/views/main/colorMode.php'; //light and dark mode
    }

    public function aboutMe()
    {
        include '../public/assets/views/aboutMe/aboutMe.php'; //about me page
    }
    public function contactMe()
    {
        include '../public/assets/views/aboutMe/contactMe.php'; //contact form page
    }

    public function resume()
    {
        include '../public/assets/views/aboutMe/resume.php'; //resume page
    }


}