<?php

namespace app\models;

class Post
{
    protected $posts = [
        [
            'id' => '1',
            'name' => 'yes',
            'description' => 'example1',
        ],
        [
            'id' => '2',
            'name' => 'no',
            'description' => 'example2',
        ]
    ];

    public function savePost($newPost){
        $this->posts[] = $newPost;
    }

    public function getPosts() {
        return $this->posts;
    }

  
}