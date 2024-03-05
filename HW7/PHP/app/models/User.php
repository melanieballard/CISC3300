<?php

namespace app\models;

class User
{
    public function getAllUsers() {
        return [
            [
                'id' => '1',
                'name' => 'pinecone'
            ],
            [
                'id' => '2',
                'name' => 'nathan'
            ]
        ];
    }
}