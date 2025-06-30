<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Nepřihlášený uživatel.");
}

foreach ($_POST['user_id'] as $id => $user_id) {
    if (isset($_POST['delete'][$id])) {
        pg_query_params($con, "DELETE FROM calendar_event_master WHERE event_id = $1", [$id]);
        continue;
    }

    $event_name = $_POST['event_name'][$id];
    $date = $_POST['shift_date'][$id];
    $start_time = $_POST['start_time'][$id];
    $end_time = $_POST['end_time'][$id];

    $start = date("Y-m-d H:i:s", strtotime("$date $start_time"));
    $end = date("Y-m-d H:i:s", strtotime("$date $end_time"));

    pg_query_params($con, "
        UPDATE calendar_event_master
        SET event_name = $1, event_start_date = $2, event_end_date = $3, user_id = $4
        WHERE event_id = $5
    ", [$event_name, $start, $end, $user_id, $id]);
}

header("Location: edit_shift.php");
exit;
