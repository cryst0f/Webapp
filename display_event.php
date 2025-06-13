<?php
session_start();
require 'db.php';

// Kontrola přihlášení
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => false,
        'msg' => 'User not logged in'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$display_query = "SELECT event_id, event_name, event_start_date, event_end_date FROM calendar_event_master WHERE user_id = $1 ORDER BY event_start_date ASC";
$results = pg_query_params($con, $display_query, [$user_id]);

if ($results === false) {
    $data = [
        'status' => false,
        'msg' => 'Database query error',
    ];
} else {
    $count = pg_num_rows($results);
    if ($count > 0) {
        $data_arr = [];
        while ($data_row = pg_fetch_assoc($results)) {
            $data_arr[] = [
                'id' => (int)$data_row['event_id'],  // FullCalendar očekává klíč 'id'
                'title' => $data_row['event_name'],
                // Zachovej čas v ISO8601 (přes "Y-m-d\TH:i:s")
                'start' => date("Y-m-d\TH:i:s", strtotime($data_row['event_start_date'])),
                'end' => date("Y-m-d\TH:i:s", strtotime($data_row['event_end_date'])),
                'color' => '#'.substr(uniqid(), -6), // unikátní barva - můžeš upravit dle potřeby
                'test'
            ];
        }
        $data = [
            'status' => true,
            'msg' => 'Successfully retrieved events!',
            'data' => $data_arr
        ];
    } else {
        $data = [
            'status' => false,
            'msg' => 'No events found!'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($data);