<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Nepřihlášen.");
}

$created_by = $_SESSION['user_id'];
$user_id = $_POST['user_id'];
$shift_date = $_POST['shift_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$event_name = trim($_POST['event_name'] ?? '');

if ($event_name === '') {
    die("Chybí název směny.");
}


$start = date("Y-m-d H:i:s", strtotime("$shift_date $start_time"));
$end = date("Y-m-d H:i:s", strtotime("$shift_date $end_time"));

// Událost jako směna
$res = pg_query_params($con, "
    INSERT INTO calendar_event_master (event_name, event_start_date, event_end_date, user_id)
    VALUES ($1, $2, $3, $4)
", [$event_name, $start, $end, $user_id]);


if ($res) {
    echo "Směna uložena.";
} else {
    echo "Chyba při ukládání směny.";
}
?>
