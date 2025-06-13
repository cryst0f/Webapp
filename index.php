<?php
session_start();
include("db.php");
include("functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Interní systém</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <a href="index.php">Home</a>
    <a href="#">Profile</a>
    <a href="#">Storage</a>
    <a href="#">Hours worked</a>
    <a href="calendar.php">Calendar</a>
    <a href="shift_planner.php">Shift planner</a>
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
