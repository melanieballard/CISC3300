<?php

namespace app\models;
use app\core\Model;

$_ENV = parse_ini_file(filename: '../.env');

class Data{

    use Model;
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