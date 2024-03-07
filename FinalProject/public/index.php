<?php

require_once '../app/core/init.php';
require_once '../app/core/routes.php';
$env = parse_ini_file('../.env');
require '../app/core/config.php';

use app\core\Router;

require_once __DIR__ . '/helpers.php';

$router = new Router($routes);
$router->serveRoute();
?>

