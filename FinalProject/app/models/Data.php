<?php

namespace app\models;
use app\core\Model;
use app\core\Database;

$_ENV = parse_ini_file(filename: '../.env');

class Data{

    use Model;
    use Database;

    public function saveToken($token){

        if(isset($_POST['username'])){
            $username = $_POST['username'];
            return $this->query("INSERT INTO access_tokens (username, token) VALUES ('$username','$token')");
        }
        else{
            echo 'Error: Username not submitted';
            exit();
        }
    }

    public function getToken(){
        return $this->query("SELECT * FROM access_tokens WHERE id = :id");
    }

    public function playlists($token){
        $playlistUrl = 'https://api.spotify.com/v1/me/playlists';

        // Prepare headers for the GET request
        $headers = [
            'Authorization: Bearer ' . $token,
        ];

        // Create stream context for making the GET request
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
            ],
        ]);

        // Make the GET request to retrieve user's playlists
        $playlistResponse = file_get_contents($playlistUrl, false, $context);

        // Handle the response
        if ($playlistResponse === false) {
            // Error handling
            exit("Failed to retrieve user's playlists.");
        }

        // Parse the JSON response
        $playlists = json_decode($playlistResponse, true);

        // Output user's playlists
        return $playlists;

    }

}