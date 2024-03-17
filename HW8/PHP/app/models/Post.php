<?php

namespace app\models;

class Post
{
    private static $file = 'posts.txt'; //file of posts across all class instances

    //save post
    public static function savePost($id, $newPost){
        $posts = []; //array of posts
        if (file_exists(self::$file)) {
            $posts = unserialize(file_get_contents(self::$file)); //set file contents to array of posts
        }

        //add new post to array
        $posts[] = $newPost;

        //serialize new contents with new post and store in file
        file_put_contents(self::$file, serialize($posts));
    }

    //return posts
    public static function getPosts() {
        if (file_exists(self::$file)) {
            return unserialize(file_get_contents(self::$file)); //return unserialized arrays
        }
        return [];
    }

}