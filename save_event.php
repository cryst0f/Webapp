<?php
session_start();
require 'db.php';

// Zkontroluj, zda je uživatel přihlášený
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => false, 'msg' => 'User not logged in']);
    exit;
}

$event_name = $_POST['event_name'] ?? '';
$event_start_date = $_POST['event_start_date'] ?? '';
$event_end_date = $_POST['event_end_date'] ?? '';
$user_id = $_SESSION['user_id'];

// Pokud FullCalendar posílá čas, nech datum + čas (TIMESTAMP), jinak jen datum (Y-m-d)
$event_start_date = date("Y-m-d H:i:s", strtotime($event_start_date));
$event_end_date = date("Y-m-d H:i:s", strtotime($event_end_date));

$insert_query = "
    INSERT INTO calendar_event_master (event_name, event_start_date, event_end_date, user_id) 
    VALUES ($1, $2, $3, $4)
";

$result = pg_query_params($con, $insert_query, [$event_name, $event_start_date, $event_end_date, $user_id]);

if ($result) {
    $data = array(
        'status' => true,
        'msg' => 'Event added successfully!'
    );
} else {
    $data = array(
        'status' => false,
        'msg' => 'Sorry, Event not added.'
    );
}

echo json_encode($data);