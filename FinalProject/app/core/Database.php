<?php

namespace app\core;

Trait Database
{

    private function connect()
    {
        $dsn = "mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8mb4";
        $con = new \PDO($dsn, DBUSER, DBPASS);
        return $con;
    }

    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
        if($check)
        {
            $result = $stm->fetchAll(\PDO::FETCH_OBJ);
            if(is_array($result) && count($result))
            {
                return $result;
            }
        }

        return false;
    }

}


