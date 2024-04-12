<?php

namespace app\models;
use app\core\Model;
use app\core\Database;

$_ENV = parse_ini_file(filename: '../.env');

class Data{

    use Model;
    use Database;

    function make_get_request($url, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function saveToken($token, $username){
        return $this->query("INSERT INTO access_tokens (username, token) VALUES ('$username','$token')");
    }

    public function getToken($id){
        $sql = "SELECT token FROM access_tokens WHERE username = :id";
        $params = [':id' => $id];
        return $this->query($sql, $params);
    }

    public function playlists($token){

        // Base URL for Spotify API
        $base_url = 'https://api.spotify.com/v1/';

        // Get user's Spotify ID
        $profile_url = $base_url . 'me';

        // Set up headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
        );
        
        // Make the GET request to retrieve user's profile
        $profile_response = $this->make_get_request($profile_url, $headers);
        
        $profile_data = json_decode($profile_response, true);

        // Check if the response contains the user ID
        if (isset($profile_data['id'])) {
            $user_id = $profile_data['id'];
            echo 'User ID: ' . $user_id . '<br>';
        } else {
            echo 'Error: User ID not found in response<br>';
        }

        // URL to get user's playlists
        $playlists_url = $base_url . 'users/' . $user_id . '/playlists';

        // Set up headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        );

        // Make the GET request to retrieve playlists
        $playlists_response = $this->make_get_request($playlists_url, $headers);

        $playlists_data = json_decode($playlists_response, true);

        // Encode the playlists data to JSON
        $json_data = json_encode($playlists_data);

        $file_path = "spotify_playlists.json";
        file_put_contents($file_path, $json_data);
    }

}