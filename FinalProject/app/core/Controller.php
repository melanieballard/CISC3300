<?php

namespace app\core;

class Controller
{

    public function view($path, $includeBundle = false)
    {
        if($includeBundle) {
            echo vite('main.js');
        }
        include $path;

    }


}