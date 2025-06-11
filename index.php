<?php
session_start();
include("db.php");
include("functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Interní systém</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <a href="#">Profile</a>
    <a href="#">Storage</a>
    <a href="#">Hours worked</a>
    <a href="#">Calendar</a>
    <a href="#">Messages</a>
    <a href="signup.php">User registration</a>
    <a href="logout.php">Log out</a>
  </div>

  <div class="main">
    <h1>Vítej, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>
    <p>Toto je hlavní přehled interního systému.</p>
  </div>
</body>
</html>
