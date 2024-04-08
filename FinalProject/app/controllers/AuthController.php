<?php

namespace app\controllers;
use app\core\Controller;

$_ENV = parse_ini_file(filename: '../.env');

class AuthController extends Controller {


    public function login(){
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
        
        // Use the access token to make authenticated requests to the Spotify API
        echo "Access token: $accessToken";
    }
}