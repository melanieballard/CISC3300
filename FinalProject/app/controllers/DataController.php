<?php


namespace app\controllers;
use app\core\Controller;
use app\models\Data;

$_ENV = parse_ini_file(filename: '../.env');


class DataController extends Controller{
    public function login() {
        session_start();

        $clientId = $_ENV["CID"];
        $redirectUri = 'http://localhost:8888/callback';
        $scopes = 'user-read-private user-read-email'; // Add required scopes
        $state = bin2hex(random_bytes(16)); // Generate a unique state parameter

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
        session_start();

        $clientId = $_ENV["CID"];
        $clientSecret = $_ENV["CS"];
        $redirectUri = 'http://localhost:8888/callback';

        $code = $_GET['code'];
        $state = $_GET['state'];

        if (!isset($_SESSION['spotify_state']) || $_SESSION['spotify_state'] !== $state) {
            echo "Invalid state parameter";
            exit();
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
        $_SESSION['access_token'] = $accessToken;

        $db->query("INSERT INTO access_tokens (user_id, token) VALUES ('$userId', '$accessToken')");
    }

    public function getPlaylists(){
        $newData = new Data();

        $userPlaylists = $newData->playlists($userToken);

        echo "User's Playlists:\n";
        foreach ($userPlaylists['items'] as $playlist) {
            echo $playlist['name'] . "\n";
        }

    }

}
