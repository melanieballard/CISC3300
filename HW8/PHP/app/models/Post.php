<?php

namespace app\models;

class Post
{
    public static $posts = []; //array of posts across all class instances

    //save post
    public static function savePost($id, $newPost){
        $unserialize = unserialize($newPost);
        self::$posts[$id] = $unserialize;
    }

    //return posts
    public static function getPosts() {
        return self::$posts;
    }

}