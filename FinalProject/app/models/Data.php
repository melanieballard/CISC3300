<?php

namespace app\models;
use app\core\Model;

$_ENV = parse_ini_file(filename: '../.env');

class Data{

    use Model;

    public function authenticate(){

        $clientId = $_ENV["CID"];
        $clientSecret = $_ENV["CS"];
        
        // Define the endpoint for obtaining an access token
        $tokenUrl = 'https://accounts.spotify.com/api/token';
        
        // Prepare POST data for obtaining an access token
        $postData = http_build_query([
            'grant_type' => 'client_credentials',
        ]);
        
        // Prepare headers for the POST request
        $headers = [
            'Authorization: Basic ' . base64_encode("$clientId:$clientSecret"),
            'Content-Type: application/x-www-form-urlencoded',
        ];
        
        // Create stream context for making the POST request
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $postData,
            ],
        ]);
        
        // Make the POST request to obtain an access token
        $response = file_get_contents($tokenUrl, false, $context);
        
        // Handle the response
        if ($response === false) {
            // Error handling
            exit("Failed to retrieve access token.");
        }
        
        // Parse the JSON response
        $tokenData = json_decode($response, true);
        
        // Access token obtained from Spotify
        $accessToken = $tokenData['access_token'];  
        return $accessToken; 

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