<?php

namespace app\controllers;
use app\core\Controller;
use app\models\Post;
class PostController extends Controller
{
//todo make a method to return some posts, post objects should come from the post model class
//also need to make a twig template to show the posts
//an example is in app/controllers/UsersController


    public function index()
    {
        //the load method will start at the public/assets/views directory
        //you can view the set up in the app/core/Controller class
        $template = $this->twig->load('posts/posts.twig');
        $homepageData = [
            'posts' => Post::getPosts(),
        ];
        echo $template->render($homepageData);
    }

    public function create(){

        //get form data
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;

        // if null
        if (!$name || !$description) {
            http_response_code(400);
            echo "Error: Name and description are required.";
            return;
        }

        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);

        $date = date('His');

        //create new post
        $newPost = [
            'id' => $date,
            'name' => $name,
            'description' => $description,
        ];

        //save new posts
        Post::savePost($newPost);
        header('Location: /posts');

        }
    }


