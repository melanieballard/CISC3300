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

    public function recommendations($playlist_id, $token) {
        $playlist_url = "https://api.spotify.com/v1/playlists/{$playlist_id}/tracks";
        $recommendations_url = 'https://api.spotify.com/v1/recommendations';
    
        $playlist_headers = [
            'Authorization: Bearer ' . $token,
        ];
    
        $playlist_data = $this->make_get_request($playlist_url, $playlist_headers);
        $playlist = json_decode($playlist_data, true);
    
        $recommended_tracks = [];
        if (isset($playlist['items']) && is_array($playlist['items'])) {
            $track_ids = [];
            foreach ($playlist['items'] as $item) {
                $track_ids[] = $item['track']['id'];
                if (count($track_ids) >= 5) { // Batch requests with up to 5 seed tracks
                    $recommended_tracks = array_merge($recommended_tracks, $this->fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers));
                    $track_ids = []; // Reset the seed tracks array after each batch
                }
            }
            if (!empty($track_ids)) { // Handle any remaining tracks
                $recommended_tracks = array_merge($recommended_tracks, $this->fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers));
            }
        } else {
            echo "Items does not exist.";
        }
    
        return $recommended_tracks;
    }
    
    private function fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers) {
        $recommendations_params = [
            'seed_tracks' => implode(',', $track_ids),
            'limit' => 5 // Adjust limit if necessary
        ];
    
        $recommendations_query = http_build_query($recommendations_params);
        $recommendations_url_with_params = $recommendations_url . '?' . $recommendations_query;
    
        $recommendations_options = [
            CURLOPT_URL => $recommendations_url_with_params,
            CURLOPT_HTTPHEADER => $playlist_headers,
            CURLOPT_RETURNTRANSFER => true,
        ];
    
        $recommendations_curl = curl_init();
        curl_setopt_array($recommendations_curl, $recommendations_options);
        $recommendations_response = curl_exec($recommendations_curl);
        curl_close($recommendations_curl);
        
        $recommendations_data = json_decode($recommendations_response, true);
        $fetched_tracks = [];
        if (isset($recommendations_data['tracks']) && is_array($recommendations_data['tracks'])) {
            foreach ($recommendations_data['tracks'] as $track) {
                $fetched_tracks[] = $track;
            }
        } else {
            echo "Tracks do not exist.";
        }
        return $fetched_tracks;
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
        
    }

}