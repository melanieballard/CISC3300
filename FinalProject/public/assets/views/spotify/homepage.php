<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css">
        <link rel="stylesheet" type = "text/css" href="assets/styles/styleLight.css" id="colorTheme"> 

        <title>Playlist Generator</title>

    </head>
    <body class="container-fluid h-100 d-flex flex-column p-0">

        <header id="headerContainer"></header>

        <div class="container-fluid mt-5 mb-2">
            <div class="row justify-content-center">
                <div id="header" class = "col-md-8 text-center mt-5 box">
                    <h1>Playlist Generator</h1>
                    <h4>Use Your Spotify Data to Generate Reccomended Songs</h4>
                    <p>Click the play button below to begin!</p>
                </div>
            </div>
                <div class="row justify-content-center mt-1">
                    <div class="d-flex justify-content-center col-2 col-md-1">
                        <img src="assets/styles/images/play.png" class="img-fluid" alt="Play button" id="startGen" style="max-width: 100%; height: auto;">
                    </div>  
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

        <script src="/assets/js/navbar.js"></script>
        <script src="/assets/js/tooltip.js"></script>

    </body>
</html>