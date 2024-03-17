<?php

namespace app\models;

class Post
{
    private static $file = 'posts.txt'; //array of posts across all class instances

    //save post
    public static function savePost($id, $newPost){
        $posts = [];
        if (file_exists(self::$file)) {
            $posts = unserialize(file_get_contents(self::$file));
        }

        // Add new post
        $posts[] = $newPost;

        // Serialize and save posts to file
        file_put_contents(self::$file, serialize($posts));
    }

    //return posts
    public static function getPosts() {
        if (file_exists(self::$file)) {
            return unserialize(file_get_contents(self::$file));
        }
        return [];
    }

}