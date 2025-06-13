<?php
function check_login($con) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];

        $query = "SELECT * FROM users WHERE id = $1 LIMIT 1";
        $result = pg_query_params($con, $query, array($id));

        if ($result && pg_num_rows($result) > 0) {
            $user_data = pg_fetch_assoc($result);
            // >>> Ulož role_id do session <<<
            $_SESSION['role_id'] = $user_data['role_id'];
            return $user_data;
        }
    }

    header("Location: login.php");
    die;
}

?>