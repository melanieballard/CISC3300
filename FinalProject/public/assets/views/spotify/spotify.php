<?php
// Set up credentials
$client_id = '97b1cf6460bb440ab574626c9717fae5';
$client_secret = '41f939ea867345fca753b73a860cd725';
$redirect_uri = 'http://localhost:8888/callback/';
$scopes = 'user-read-private user-read-email'; // Adjust scope based on your requirements

// Step 1: Authenticate user
if (!isset($_GET['code'])) {
    // Redirect user to Spotify to authorize the app
    $authorize_url = 'https://accounts.spotify.com/authorize?' . http_build_query([
        'client_id' => $client_id,
        'response_type' => 'code',
        'redirect_uri' => $redirect_uri,
        'scope' => $scopes
    ]);
    header('Location: ' . $authorize_url);
    die();
}

// Step 2: Get access token
$authorization_code = $_GET['code'];
$token_url = 'https://accounts.spotify.com/api/token';
$token_request_body = [
    'grant_type' => 'authorization_code',
    'code' => $authorization_code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
];

$token_response = http_post($token_url, $token_request_body);
$access_token = $token_response['access_token'];
$refresh_token = $token_response['refresh_token'];

// Step 3: Make API request
$api_url = 'https://api.spotify.com/v1/me';
$api_response = http_get($api_url, [
    'Authorization: Bearer ' . $access_token
]);

$user_info = json_decode($api_response, true);

// Step 4: Handle response
if ($user_info && isset($user_info['display_name'])) {
    echo 'Welcome, ' . $user_info['display_name'];
} else {
    echo 'Error retrieving user info.';
}

// Helper functions
function http_post($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function http_get($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}