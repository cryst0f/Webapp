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
  <style>
    .main { padding: 20px; }
    table { width: 100%; }
    select, input { width: 100%; }
    button#addShiftBtn { margin-top: 10px; }
  </style>
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

  <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

  <form id="shiftForm" method="post">
    <table id="shiftsTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Uživatel</th>
          <th>Směna</th>
          <th>Datum směny</th>
          <th>Začátek</th>
          <th>Konec</th>
          <th>Akce</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select name="user_id[]" required>
              <?php
                $res = pg_query($con, "SELECT id, first_name, last_name FROM users WHERE role_id = 4");
                while ($row = pg_fetch_assoc($res)) {
                    echo "<option value='{$row['id']}'>".htmlspecialchars($row['first_name'])." ".htmlspecialchars($row['last_name'])."</option>";
                }
              ?>
            </select>
          </td>
          <td>
            <select name="event_name[]" class="shift_type" required>
              <option value="" disabled selected>Vyber směnu</option>
              <option value="Ranní">Ranní</option>
              <option value="Odpolední">Odpolední</option>
            </select>
          </td>
          <td><input type="date" name="shift_date[]" required></td>
          <td><input type="time" name="start_time[]" class="start_time" required></td>
          <td><input type="time" name="end_time[]" class="end_time" required></td>
          <td><button type="button" class="removeRowBtn btn btn-danger btn-sm">-</button></td>
        </tr>
      </tbody>
    </table>

    <button type="button" id="addShiftBtn" class="btn btn-primary">+ Přidat směnu</button>
    <br><br>
    <button type="submit" class="btn btn-success">Uložit všechny směny</button>
  </form>
</div>

<script>
  // Automatické nastavení časů podle typu směny
  function setShiftTimes(row) {
    const shiftSelect = row.querySelector('.shift_type');
    const startInput = row.querySelector('.start_time');
    const endInput = row.querySelector('.end_time');

    shiftSelect.addEventListener('change', function() {
      if (this.value === 'Ranní') {
        startInput.value = '06:00';
        endInput.value = '14:00';
      } else if (this.value === 'Odpolední') {
        startInput.value = '14:00';
        endInput.value = '22:00';
      } else {
        startInput.value = '';
        endInput.value = '';
      }
    });
  }

  const firstRow = document.querySelector('#shiftsTable tbody tr');
  setShiftTimes(firstRow);

  document.getElementById('addShiftBtn').addEventListener('click', () => {
    const tableBody = document.querySelector('#shiftsTable tbody');
    const newRow = firstRow.cloneNode(true);

    newRow.querySelectorAll('select, input').forEach(input => {
      input.value = '';
      if(input.tagName === 'SELECT') {
        input.selectedIndex = 0;
      }
    });

    setShiftTimes(newRow);
    tableBody.appendChild(newRow);
  });

  document.querySelector('#shiftsTable').addEventListener('click', function(e) {
    if(e.target.classList.contains('removeRowBtn')) {
      const rows = document.querySelectorAll('#shiftsTable tbody tr');
      if(rows.length > 1) {
        e.target.closest('tr').remove();
      } else {
        alert('Musí být alespoň jedna směna.');
      }
    }
  });

  // AJAX odeslání formuláře
  document.getElementById('shiftForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('save_shift.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      const alertBox = document.getElementById('successAlert');
      alertBox.textContent = data;
      alertBox.classList.remove('d-none');

      // Automatický refresh po 2 sekundách
      setTimeout(() => {
        location.reload();
      }, 2000);
    })
    .catch(error => {
      alert("Nastala chyba při ukládání směn.");
      console.error("Chyba:", error);
    });
  });
</script>

</body>
</html>
