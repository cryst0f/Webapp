<?php
session_start();
include("db.php");
include("functions.php");
session_abort();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Redirect</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <script>
    let countdown = 3;
    function updateCountdown() {
      document.getElementById('countdown').innerText = countdown;
      countdown--;
      if (countdown < 0) {
        window.location.href = "http://localhost/myapp/login.php";
      } else {
        setTimeout(updateCountdown, 1000);
      }
    }

    window.onload = function () {
      updateCountdown();
    }
  </script>
</head>
<body class="text-center mt-5">
  <div class="container">
    <h1>You are getting redirected</h1>
    <h2>to login page in <span id="countdown">3</span></h2>
  </div>
</body>
</html>
