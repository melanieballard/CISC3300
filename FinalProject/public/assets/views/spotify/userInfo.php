<!DOCTYPE html>
<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/styles/mainpageStyle.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="assets/js/login.js"></script>
        <title>Log In</title>
    </head>
    <body class = "container-fluid h-100 d-flex justify-content-center align-items-center p-0">
        <div class="w-100 d-flex justify-content-center">
            <form id="usernameForm" class="text-center">
                <h1>Submit Username</h1>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>
                <button type="submit" id="go">Log In</button>
            </form>
        </div>
    </body>
</html>