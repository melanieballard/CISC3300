<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" type = "text/css" href="assets/styles/styleLight.css" id="colorTheme"> 

        <title>Reccomendations</title>
    </head>
    <body class="container-fluid h-100 d-flex flex-column p-0">

        <header id="headerContainer"></header>

        <div class="container-fluid mt-5">
            <div class="row text-center justify-content-center">
                <div id="header" class = "col-md-8 text-center mt-5 box">
                    <h1>New Playlist</h1>
                </div>
            <div id="trackList" class="col-md-8 text-center mt-3 box">

            </div>
            <div class="col-12 col-md-6 text-center justify-content-center mt-3">
                <a href='https://open.spotify.com' class="btn btn-primary" role="button" aria-pressed="true" id="openSpotify">View In Spotify</a>
                <a href='/success' class="btn btn-secondary" role="button" aria-pressed="true" id="openSpotify">Generate Again</a>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="assets/js/navbar.js"></script>
        <script src="assets/js/newPlaylist.js"></script>

    </body>
</html>