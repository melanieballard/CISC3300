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

        // Encode the playlists data to JSON
        echo $playlists_response;
        return $playlists_response;

    }

    public function reccomendations($playlist_id, $token){

        $playlist_url = "https://api.spotify.com/v1/playlists/{$playlist_id}/tracks";
        $recommendations_url = 'https://api.spotify.com/v1/recommendations?limit=1&market=US';

        $playlist_headers = [
            'Authorization: Bearer ' . $token,
        ];

        $playlist_data = $this->make_get_request($playlist_url, $playlist_headers);
        $playlist = json_decode($playlist_data, true);
        
        $recommended_tracks = [];
        foreach ($playlist['items'] as $item) {
            $track_id = $item['track']['id'];
            $recommendations_params = [
                'seed_tracks' => $track_id,
            ];
            $recommendations_options = [
                CURLOPT_URL => $recommendations_url . '?' . http_build_query($recommendations_params),
                CURLOPT_HTTPHEADER => $playlist_headers,
                CURLOPT_RETURNTRANSFER => true,
            ];
        
            $recommendations_curl = curl_init();
            curl_setopt_array($recommendations_curl, $recommendations_options);
            $recommendations_response = curl_exec($recommendations_curl);
            $recommendations_data = json_decode($recommendations_response, true);
            $recommended_tracks[] = $recommendations_data['tracks'];
        }

        return $recommended_tracks;

    }

    public function createPlaylist($givenPlaylist, $tracks){

        
        
    }

}