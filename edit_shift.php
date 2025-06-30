<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Editace směn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .main { padding: 20px; }
    table { width: 100%; }
    select, input { width: 100%; }
    .btn-small { padding: 4px 8px; font-size: 0.8rem; }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
  <h1>Shift editor</h1>

  <form method="post" action="shift_update.php">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>User</th>
          <th>Shift type</th>
          <th>Date</th>
          <th>Start</th>
          <th>End</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = pg_query($con, "
          SELECT cem.event_id AS id, cem.event_name, cem.event_start_date, cem.event_end_date, u.id AS user_id, u.first_name, u.last_name
          FROM calendar_event_master cem
          JOIN users u ON u.id = cem.user_id
          ORDER BY cem.event_start_date DESC
        ");

        $users = pg_query($con, "SELECT id, first_name, last_name FROM users");

        $userOptions = [];
        while ($u = pg_fetch_assoc($users)) {
            $userOptions[$u['id']] = htmlspecialchars($u['first_name'].' '.$u['last_name']);
        }

        while ($row = pg_fetch_assoc($result)) {
            $id = $row['id'];
            $selected_user = $row['user_id'];
            $event_name = $row['event_name'];
            $date = date('Y-m-d', strtotime($row['event_start_date']));
            $start_time = date('H:i', strtotime($row['event_start_date']));
            $end_time = date('H:i', strtotime($row['event_end_date']));

            echo "<tr>";
            echo "<td><select name='user_id[$id]' class='form-select'>";
            foreach ($userOptions as $uid => $uname) {
                $sel = $uid == $selected_user ? "selected" : "";
                echo "<option value='$uid' $sel>$uname</option>";
            }
            echo "</select></td>";

            echo "<td>
                    <select name='event_name[$id]' class='form-select'>
                      <option value='Morning' ".($event_name === 'Morning' ? 'selected' : '').">Morning</option>
                      <option value='Afternoon' ".($event_name === 'Afternoon' ? 'selected' : '').">Afternoon</option>
                    </select>
                  </td>";

            echo "<td><input type='date' name='shift_date[$id]' class='form-control' value='$date' required></td>";
            echo "<td><input type='time' name='start_time[$id]' class='form-control' value='$start_time' required></td>";
            echo "<td><input type='time' name='end_time[$id]' class='form-control' value='$end_time' required></td>";

            echo "<td>
                    <button type='submit' name='delete[$id]' value='1' class='btn btn-danger btn-small'>Delete shift</button>
                  </td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <button type="submit" name="update" value="1" class="btn btn-success">Save changes</button>
  </form>
</div>

<script>
  document.querySelectorAll('.submenu-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      const submenu = this.nextElementSibling;
      if (submenu.style.display === 'block') {
        submenu.style.display = 'none';
        this.textContent = "Shift planner ▼";
      } else {
        submenu.style.display = 'block';
        this.textContent = "Shift planner ▲";
      }
    });
  });
</script>

</body>
</html>
