<?php

namespace app\models;
use app\core\Model;
use app\core\Database;

$_ENV = parse_ini_file(filename: '../.env');

class Data{

    use Model;
    use Database;

    //curl function for get request for api
    function make_get_request($url, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    //curl function for post request for api
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

    //save token to mysql
    public function saveToken($token, $username){
        return $this->query("INSERT INTO access_tokens (username, token) VALUES ('$username','$token')");
    }

    //return token from sql
    public function getToken($id){
        $sql = "SELECT token FROM access_tokens WHERE username = :id";
        $params = [':id' => $id];
        return $this->query($sql, $params);
    }


    //function to return user playlists
    public function playlists($token){

        // base url for api
        $base_url = 'https://api.spotify.com/v1/';

        // get user ID
        $profile_url = $base_url . 'me';

        //headers for request
        $headers = array(
            'Authorization: Bearer ' . $token,
        );
        
        //retrieve profile
        $profile_response = $this->make_get_request($profile_url, $headers);
        
        $profile_data = json_decode($profile_response, true);

        //get user id from response
        if (isset($profile_data['id'])) {
            $user_id = $profile_data['id'];
        } else {
            echo 'Error: User ID not found in response<br>';
        }

        //insert user id into sql
        $sql = "UPDATE access_tokens SET userID = :userID WHERE token = :token";
        $params = [':userID' => $user_id, ':token' => $token];
        $this->query($sql, $params);

        //url to get user playlists
        $playlists_url = $base_url . 'users/' . $user_id . '/playlists';

        //headers
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        );

        //retrieve playlists
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

        echo $playlists_response;
        return $playlists_response;

    }

    public function recommendations($playlist_id, $token) {

        //base urls
        $playlist_url = "https://api.spotify.com/v1/playlists/{$playlist_id}/tracks";
        $recommendations_url = 'https://api.spotify.com/v1/recommendations';
    
        $playlist_headers = [
            'Authorization: Bearer ' . $token,
        ];
    
        //get playlist data
        $playlist_data = $this->make_get_request($playlist_url, $playlist_headers);
        $playlist = json_decode($playlist_data, true);
    
        //generate reccomended tracks batch requests
        $recommended_tracks = [];
        if (isset($playlist['items']) && is_array($playlist['items'])) {
            $track_ids = [];
            foreach ($playlist['items'] as $item) {
                $track_ids[] = $item['track']['id'];
                if (count($track_ids) >= 5) { //batch requests with up to 5 seed tracks
                    $recommended_tracks = array_merge($recommended_tracks, $this->fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers));
                    $track_ids = []; //reset the seed tracks array after each batch
                }
            }
            if (!empty($track_ids)) { //handle any remaining tracks
                $recommended_tracks = array_merge($recommended_tracks, $this->fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers));
            }
        } else {
            echo "Items does not exist.";
        }
    
        return $recommended_tracks;
    }
    
    //get reccomended tracks with api
    private function fetch_recommendations($track_ids, $token, $recommendations_url, $playlist_headers) {

        //parameters for request
        $recommendations_params = [
            'seed_tracks' => implode(',', $track_ids),
            'limit' => 5
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

        //store fetched tracks into array 
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

        //get user id from sql
        $sql = "SELECT userID FROM access_tokens WHERE token = :token";
        $params = [':token' => $token];
        $result = $this->query($sql, $params);
        $userID = $result[0]->userID;

        //url with user id for post request
        $baseURL = "https://api.spotify.com/v1/users/{$userID}/playlists";

        //get name of base playlist
        $sql = "SELECT name FROM playlists WHERE id = :id";
        $params = [":id" => $givenPlaylist];
        $result = $this->query($sql, $params);
        $playlistName = $result[0]->name;

        //generate new playlist details
        $name = "Reccomendations from " . $playlistName;
        $description = "A new playlist based on the songs from " . $playlistName;

        //data for new playlist
        $playlist_data = json_encode([
            'name' => $name,
            'description' => $description,
            'public' => false
        ]);

        $playlist_headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        //make new playlist
        $playlistData = $this->make_post_request($baseURL, $playlist_data, $playlist_headers);
        $playlistData = json_decode($playlistData, true);

        //get new playlist data and access tracks
        $playlistId = $playlistData['id'];
        $newPlaylistName = $playlistData['name'];
        $addTracksUrl = "https://api.spotify.com/v1/playlists/{$playlistId}/tracks";

        //store track uris from given reccomendation tracks
        $trackURIs = [];
        foreach($tracks as $track){
            $trackURIs[] = $track['uri'];
        }

        $tracksData = json_encode([
            'uris' => $trackURIs 
        ]);

        //add tracks to playlist
        $this->make_post_request($addTracksUrl, $tracksData, $playlist_headers);

        //get primary key id of user
        $query = "SELECT id FROM access_tokens WHERE token = :token";
        $parameters = [':token' => $token];
        $return = $this->query($query, $parameters);
        $owner = $return[0]->id;
        
        //insert the new playlist into sql
        $sql = "INSERT INTO playlists (id, name, owner) VALUES (:id, :name, :owner)";
        $params = [':id' => $playlistId, ':name' => $newPlaylistName, ':owner' => $owner];
        $this->query($sql, $params);
        
        //get data on new playlist tracks
        $playlist_headers = [
            'Authorization: Bearer ' . $token,
        ];
    
        $playlist_data = $this->make_get_request($addTracksUrl, $playlist_headers);
        $playlist = json_decode($playlist_data, true);

        //save artist and track name for each new song for display purposes
        $newPlaylistInfo = [];
        foreach($playlist['items'] as $item){
            if (isset($item['track'])) {
                $trackName = $item['track']['name'] ?? 'Unknown Track';
                $artistName = isset($item['track']['artists'][0]['name']) ? $item['track']['artists'][0]['name'] : 'Unknown Artist';
    
                //store in array
                $newPlaylistInfo[] = [
                    'track' => $trackName,
                    'artist' => $artistName
                ];
            }
        }

        return $newPlaylistInfo; //return info on tracks and artists in new playlist 

    }

}