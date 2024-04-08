<?php

namespace app\controllers;
use app\core\Controller;

class AuthController extends Controller {
    public function login() {
        $clientId = .clientID;
        $redirectUri = 'http://localhost:8888/callback';
        $scopes = 'user-read-private user-read-email'; 
        $state = bin2hex(random_bytes(16)); 
        
        $_SESSION['spotify_state'] = $state; // Store the state in session
        
        $authorizeUrl = 'https://accounts.spotify.com/authorize?' . http_build_query([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'scope' => $scopes,
            'state' => $state
        ]);

        header('Location: ' . $authorizeUrl);
        exit();
    }

    public function callback() {
        $clientID = .clientID;
        $clientSecret = .clientSecret;
        $redirectUri = 'http://localhost:8888/callback';
    
        $code = $_GET['code'];
        $state = $_GET['state'];
    
        // Verify state to prevent CSRF attacks
        if (!isset($_SESSION['spotify_state']) || $_SESSION['spotify_state'] !== $state) {
            // Handle error
        }
    
        // Exchange authorization code for access token
        $tokenUrl = 'https://accounts.spotify.com/api/token';
        $postData = http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "Content-Length: " . strlen($postData) . "\r\n",
                'content' => $postData
            ]
        ]);
        $accessTokenResponse = file_get_contents($tokenUrl, false, $context);
    
        $accessToken = json_decode($accessTokenResponse, true)['access_token'];
    
        // Store access token in session or database
        $_SESSION['access_token'] = $accessToken;
    
        // Redirect to a page after successful login
        header('Location: /');
        exit();
    }
}