<?php


namespace app\controllers;
use app\core\Controller;
use app\models\Data;

$_ENV = parse_ini_file(filename: '../.env');

session_start();
class DataController extends Controller{

    public function handleUsername(){

        if(isset($_POST['username'])){
            $username = $_POST['username'];
            $_SESSION["username"] = $username;
        }else{
            echo 'Error: Username not submitted';
            exit();
        }

    }

    public function login() {

        $clientId = $_ENV["CID"];
        $redirectUri = 'http://localhost:8888/callback';
        $scopes = 'user-read-private user-read-email playlist-read-private playlist-modify-private playlist-modify-public'; // Add required scopes
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

        $username = $_SESSION['username'];

        $saveToken = new Data();
        $saveToken->saveToken($accessToken, $username);

        header('Location: /success');
        exit();

    }

    public function getPlaylists(){

        $newData = new Data();

        $username = $_SESSION['username'];
        $userToken = $newData->getToken($username);
        if (!empty($userToken) && isset($userToken[0]->token)) {
            // Access the token property of the object in the array
            $token = $userToken[0]->token;
            $newData->playlists($token);
        } else {
            echo "Token not found";
        }
    }


    public function getReccomendedSongs(){

        $newData = new Data();

        $playlist_id = $_POST['playlistId'];

        $username = $_SESSION['username'];
        $userToken = $newData->getToken($username);
        $token = $userToken[0]->token;

        $reccomendations = $newData->recommendations($playlist_id, $token);

        $newPlaylist = $newData->createPlaylist($playlist_id, $reccomendations, $token);
        echo json_encode($newPlaylist);
        
    }
}
