<?php

namespace app\core;

Trait Model
{
    use Database;

    public function findAll()
    {
        $query = "select * from $this->table";
        return $this->query($query);
    }

    public function save($data)
    {
        $query = "INSERT INTO $this->table (UserID, Email, SpotifyURI) VALUES (:UserID, :Email, :SpotifyURI)";
        return $this->query($query);
    }



}