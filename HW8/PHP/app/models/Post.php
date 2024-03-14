<?php

namespace app\models;

class Post
{

    public function getPosts() {
        return [
            [
                'id' => '1',
                'content' => 'yes'
            ],
            [
                'id' => '2',
                'content' => 'no'
            ]
        ];
    }
}