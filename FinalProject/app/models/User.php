<?php

namespace app\models;
use app\core\Model;

class User

{
    use Model;

    protected $table = 'users';

    public function getAllUsers() {
        return $this->findAll();
    }
}