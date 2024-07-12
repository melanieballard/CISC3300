<!DOCTYPE html>
<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" type = "text/css" href="assets/styles/styleLight.css" id="colorTheme"> 

        <title>Log In</title>
    </head>
    <body class="container-fluid h-100 d-flex flex-column p-0">

        <header id="headerContainer"></header>

        <div class="container-fluid mt-5 mb-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 mt-5 text-center text-md-start justify-content-center justify-content-md-start">
                    <h1>Begin Generating</h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 justify-content-center justify-content-md-start">
                    <form id="usernameForm">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                            <small id="usernameHelp" class="form-text text-muted">Max 250 characters</small>
                        </div>
                        <button type="submit" class="btn btn-primary" id="go">Log In</button>
                        <a href="/playlist-generator" class="btn btn-secondary" role="button" aria-pressed="true" id="back">Back</a>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="/assets/js/navbar.js"></script>
        <script src="assets/js/login.js"></script>

    </body>
</html>