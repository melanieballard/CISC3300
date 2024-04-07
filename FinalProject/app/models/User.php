<?php

namespace app\models;
use app\core\Model;

class User

{
    use Model;

    protected $table = 'Users';

    public function getAllUsers() {
        return $this->findAll();
    }

    public function saveUser($data){
        return $this->save($data);
    }
}