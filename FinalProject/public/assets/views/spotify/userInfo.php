<!DOCTYPE html>
<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="assets/styles/mainpageStyle.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="assets/js/login.js"></script>
        <title>Log In</title>
    </head>
    <body class = "container-fluid h-100 d-grid place-items-center">
        <div class = "container">
            <h2>Submit Username</h2>
            <form id="usernameForm" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <button type="submit" id="go">Log In</button>
            </form>
        </div>
    </body>
</html>