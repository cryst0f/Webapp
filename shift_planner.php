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
  <title>Shift planner</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Menu</h2>
    <a href="index.php">Home</a>
    <a href="#">Profile</a>
    <a href="#">Storage</a>
    <a href="#">Hours worked</a>
    <a href="calendar.php">Calendar</a>
  <?php if (in_array($_SESSION['role_id'], [1, 2, 3])): ?>
    <a href="shift_planner.php">Shift planner</a>
  <?php endif; ?>
    <a href="#">Messages</a>
    <a href="signup.php">User registration</a>
    <a href="logout.php">Log out</a>
</div>

<!-- Main content -->
<div class="main">
  <h1>Plánování směn</h1>
  <form id="shiftForm" method="post" action="save_shift.php">
    <label>Vyber uživatele:</label>
    <select name="user_id" required>
      <?php
        $res = pg_query($con, "SELECT id, first_name, last_name FROM users WHERE role_id = 4");
        while ($row = pg_fetch_assoc($res)) {
            echo "<option value='{$row['id']}'>{$row['first_name']} {$row['last_name']}</option>";
        }
      ?>
    </select>

    <label>Název směny:</label>
    <input type="text" name="event_name" required>

    <label>Datum směny:</label>
    <input type="date" name="shift_date" required>

    <label>Začátek:</label>
    <input type="time" name="start_time" required>

    <label>Konec:</label>
    <input type="time" name="end_time" required>

    <br><br>
    <button type="submit">Přidat směnu</button>
  </form>
</div>

</body>
</html>
