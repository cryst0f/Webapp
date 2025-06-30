 <?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Nepřihlášen.");
}

$created_by = $_SESSION['user_id'];

// Ověříme, že všechna pole jsou polem a mají stejnou délku
if (
    !is_array($_POST['user_id']) ||
    !is_array($_POST['event_name']) ||
    !is_array($_POST['shift_date']) ||
    !is_array($_POST['start_time']) ||
    !is_array($_POST['end_time'])
) {
    die("Neplatný vstup dat.");
}

$count = count($_POST['user_id']);
$successCount = 0;

for ($i = 0; $i < $count; $i++) {
    $user_id = $_POST['user_id'][$i];
    $event_name = trim($_POST['event_name'][$i] ?? '');
    $shift_date = $_POST['shift_date'][$i];
    $start_time = $_POST['start_time'][$i];
    $end_time = $_POST['end_time'][$i];

    if ($event_name === '') continue;

    $start = date("Y-m-d H:i:s", strtotime("$shift_date $start_time"));
    $end = date("Y-m-d H:i:s", strtotime("$shift_date $end_time"));

    $res = pg_query_params($con, "
        INSERT INTO calendar_event_master (event_name, event_start_date, event_end_date, user_id)
        VALUES ($1, $2, $3, $4)
    ", [$event_name, $start, $end, $user_id]);

    if ($res) {
        $successCount++;
    }
}

echo "Amount of saved shifts: $successCount / $count";
?>