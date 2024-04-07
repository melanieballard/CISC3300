<!DOCTYPE html>
<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type = "text/css" href="assets/styles/mainpageStyle.css">
        <script src="js/src/script.js" type="module"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>User Information</title>
    </head>
    <body>
      <h1>Display your Spotify profile data</h1>

      <section id="profile">
      <h2>Logged in as <span id="displayName"></span></h2>
      <span id="avatar"></span>
      <ul>
          <li>User ID: <span id="id"></span></li>
          <li>Email: <span id="email"></span></li>
          <li>Spotify URI: <a id="uri" href="#"></a></li>
          <li>Link: <a id="url" href="#"></a></li>
          <li>Profile Image: <span id="imgUrl"></span></li>
      </ul>
      </section>
    </body>
</html>