<?php

namespace app\models;

class Post
{
    public static $posts = [];

    //public static function savePost($newPost){
      // self::$posts[] = $newPost;
    //}

    public static function savePost($_POST) {
        // Retrieve form data
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;

        if (!$name || !$description) {
            http_response_code(400);
            echo "Error: Name and description are required.";
            return;
        }

        // Sanitize data if needed
        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);

        $date = date('His');

        // Store data in class variable
        $newPost = [
            'id' => $date,
            'name' => $name,
            'description' => $description
        ];

        self::$posts[] = $newPost;
    }


    public static function getPosts() {
        return self::$posts;
    }

}