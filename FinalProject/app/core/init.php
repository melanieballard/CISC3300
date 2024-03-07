<?php
define('APP_ROOT', dirname(__FILE__, 3));

spl_autoload_register(function ($classname) {
    require $filename = APP_ROOT . '/' . str_replace('\\', '/', $classname) . ".php";
});
