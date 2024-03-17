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

        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;

        //null check
        if (!$name || !$description) {
            http_response_code(400);
            echo "Error: Name and description are required.";
            return;
        }

        //xss protection
        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);

        $date = date('His'); //gen id

        // Store data as array
        $newPost = [
            'name' => $name,
            'description' => $description
        ];

        //save new posts to file
        Post::savePost($date, $newPost);
        header('Location: /posts');
        exit();

        }
    }


