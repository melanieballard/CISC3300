<?php

namespace app\models;

class Post
{
    public static $posts = []; //array of posts across all class instances

    //save post
    public static function savePost($newPost){
        self::$posts[] = $newPost;
    }

    //return posts
    public static function getPosts() {
        return self::$posts;
    }

}