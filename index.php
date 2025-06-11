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
    <a href="#">Profil</a>
    <a href="#">Úložiště</a>
    <a href="#">Odpracovaný čas</a>
    <a href="#">Kalendář</a>
    <a href="#">Zprávy</a>
    <a href="#">Registrace uživatele</a>
    <a href="logout.php">Odhlásit se</a>
  </div>

  <div class="main">
    <h1>Vítej, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>
    <p>Toto je hlavní přehled interního systému.</p>
  </div>
</body>
</html>
