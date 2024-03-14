<?php

namespace app\models;

class Post
{
    protected static $posts = [];

    public static function savePost($newPost){
       self::$posts[] = $newPost;
    }

    public static function getPosts() {
        return self::$posts;
    }

}