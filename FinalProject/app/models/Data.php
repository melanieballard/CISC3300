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

    function make_post_request($url, $data, $headers) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,          
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,          
            CURLOPT_POSTFIELDS => $data,  
            CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
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

        $sql = "UPDATE access_tokens SET userID = :userID WHERE token = :token";
        $params = [':userID' => $user_id, ':token' => $token];
        $this->query($sql, $params);

        // URL to get user's playlists
        $playlists_url = $base_url . 'users/' . $user_id . '/playlists';

        // Set up headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        );

        // Make the GET request to retrieve playlists
        $playlists_response = $this->make_get_request($playlists_url, $headers);


        //insert values into sql
        $sql_insert = json_decode($playlists_response, true);

        $playlists = [];
        foreach ($sql_insert['items'] as $playlist) {
            $playlistName = $playlist['name'];
            $playlistId = $playlist['id'];
            $playlists[] = ['name' => $playlistName, 'id' => $playlistId];
        }

        $query = "SELECT id FROM access_tokens WHERE userID = :id";
        $parameters = [':id' => $user_id];
        $return = $this->query($query, $parameters);
        $owner = $return[0]->id;

        $sql = "INSERT INTO playlists (id, name, owner) VALUES (:id, :name, :owner)";
        foreach ($playlists as $playlist) {
            $params = [':id' => $playlist['id'], ':name' => $playlist['name'], ':owner' => $owner];
            $this->query($sql, $params);
        }

        // Encode the playlists data to JSON
        echo $playlists_response;
        return $playlists_response;

    }

    public function recommendations($playlist_id, $token){
        $playlist_url = "https://api.spotify.com/v1/playlists/{$playlist_id}/tracks";
        $recommendations_url = 'https://api.spotify.com/v1/recommendations';
    
        $playlist_headers = [
            'Authorization: Bearer ' . $token,
        ];
    
        // Fetch tracks from the playlist
        $playlist_data = $this->make_get_request($playlist_url, $playlist_headers);
        $playlist = json_decode($playlist_data, true);
    
        $recommended_tracks = [];
        if (isset($playlist['items']) && is_array($playlist['items'])) {
            // Collect track IDs from the playlist
            $track_ids = [];
            foreach ($playlist['items'] as $item) {
                $track_ids[] = $item['track']['id'];
            }
    
            // Group track IDs into batches
            $batch_size = 5; // Example batch size, adjust as needed
            $track_batches = array_chunk($track_ids, $batch_size);
    
            // Make batch requests for recommendations
            foreach ($track_batches as $batch) {
                // Construct batch request payload
                $batch_requests = [];
                foreach ($batch as $track_id) {
                    $batch_requests[] = [
                        'seed_tracks' => $track_id,
                        'limit' => 1,
                    ];
                }
    
                // Make batch request to Spotify API
                $batch_request_headers = [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                ];
                $ch = curl_init($recommendations_url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['requests' => $batch_requests]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $batch_request_headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $batch_response = curl_exec($ch);
                curl_close($ch);
    
                // Handle batch response
                $batch_recommendations = json_decode($batch_response, true);
                foreach ($batch_recommendations as $recommendation) {
                    if (isset($recommendation['tracks'][0])) {
                        $recommended_tracks[] = $recommendation['tracks'][0];
                    } else {
                        echo "No recommendations found for track ID: {$recommendation['seed_tracks'][0]}";
                    }
                }
            }
        } else {
            echo "Items does not exist in the playlist data.";
        }
    
        return $recommended_tracks;
    }

    public function createPlaylist($givenPlaylist, $tracks, $token){

        $sql = "SELECT userID FROM access_tokens WHERE token = :token";
        $params = [':token' => $token];
        $result = $this->query($sql, $params);
        $userID = $result[0]->userID;

        $baseURL = "https://api.spotify.com/v1/users/{$userID}/playlists";

        $sql = "SELECT name FROM playlists WHERE id = :id";
        $params = [":id" => $givenPlaylist];
        $result = $this->query($sql, $params);
        $playlistName = $result[0]->name;

        $name = "Reccomendations from " . $playlistName;
        $description = "A new playlist based on the songs from " . $playlistName;

        $playlist_data = json_encode([
            'name' => $name,
            'description' => $description,
            'public' => false
        ]);


        $playlist_headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $playlistData = $this->make_post_request($baseURL, $playlist_data, $playlist_headers);
        $playlistData = json_decode($playlistData, true);
        $playlistId = $playlistData['id'];
        $newPlaylistName = $playlistData['name'];
        $addTracksUrl = "https://api.spotify.com/v1/playlists/{$playlistId}/tracks";

        $trackURIs = [];
        foreach($tracks as $track){
            $trackURIs[] = $track['uri'];
        }

        $tracksData = json_encode([
            'uris' => $trackURIs 
        ]);

        $this->make_post_request($addTracksUrl, $tracksData, $playlist_headers);

        
        $query = "SELECT id FROM access_tokens WHERE token = :token";
        $parameters = [':token' => $token];
        $return = $this->query($query, $parameters);
        $owner = $return[0]->id;
        
        $sql = "INSERT INTO playlists (id, name, owner) VALUES (:id, :name, :owner)";
        $params = [':id' => $playlistId, ':name' => $newPlaylistName, ':owner' => $owner];
        $this->query($sql, $params);
        
        exit();

    }

}