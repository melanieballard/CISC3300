<?php

namespace app\models;

use app\core\Database;

class Post
{
    use Database;

    public function getPostByID($id) {
        $sql = "SELECT * FROM posts WHERE id = :id";
        return $this->queryWithParams($sql, ['id' => $id]);
    }
   
    public function getAllPosts(){
        $sql = "SELECT * FROM posts";
        return $this->fetchAll($sql);
    }

    public function savePost($postData){
        $sql = "INSERT INTO posts (title, description) VALUES (:title, :description)";
        return $this->queryWithParams($sql, $postData);

    }
}