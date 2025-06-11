<?php
session_start();
include("db.php");
include("functions.php");

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = $1 LIMIT 1";
        $result = pg_query_params($con, $query, array($username));

        if ($result && pg_num_rows($result) > 0) {
            $user_data = pg_fetch_assoc($result);

            if (password_verify($password, $user_data['password_hash'])) {
                $_SESSION['user_id'] = $user_data['id'];
                header("Location: index.php");
                exit;
            } else {
                $login_error = "Wrong password.";
            }
        } else {
            $login_error = "User does not exist.";
        }
    } else {
        $login_error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">
    <form class="form-signin" method="post">       
        <h2 class="form-signin-heading text-center mb-4">Please login</h2>

        <?php if (!empty($login_error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>

        <input type="text" class="form-control mb-3" name="username" placeholder="Uživatelské jméno" required autofocus>
        <input type="password" class="form-control mb-3" name="password" placeholder="Heslo" required>      
        <button class="btn btn-lg btn-primary w-100" type="submit">Login</button>   
    </form>
</div>

</body>
</html>
