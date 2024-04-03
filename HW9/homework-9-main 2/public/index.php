<?php
require_once "../app/models/Post.php";
require_once "../app/controllers/PostController.php";

//set our env variables
$env = parse_ini_file('../.env');
require '../app/core/config.php';

use app\controllers\PostController;

$uri = strtok($_SERVER["REQUEST_URI"], '?');

$uriArray = explode("/", $uri);
//0 = ""
//1 = posts
//2 = 1


//get all or a single post
if ($uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
    $postController = new PostController();
    $postController->getPosts($id);
}

//save post
if ($uri === '/posts' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController = new PostController();
    $postController->savePost();
}

//update post
if ($uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postController = new PostController();
    $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
    $postController->updatePost($id);
}

//delete post
if ($uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $postController = new PostController();
    $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
    $postController->deletePost($id);
}

//views
if ($uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController = new PostController();
    $postController->postsView();
}

if ($uri === '/posts-add-view' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController = new PostController();
    $postController->postsAddView();
}

if ($uriArray[1] === 'posts-update-view' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController = new PostController();
    $postController->postsUpdateView();
}

if ($uriArray[1] === 'posts-delete-view' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController = new PostController();
    $postController->postsDeleteView();
}

include '../public/assets/views/notFound.html';

?>


