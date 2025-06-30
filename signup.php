<?php
session_start();

include("db.php");    
include("functions.php");

$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id = $_POST['role_id'] ?? '';

    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($email) && !empty($password) && !empty($role_id)) {
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = 'INSERT INTO users (first_name, last_name, username, email, password_hash, role_id) VALUES ($1, $2, $3, $4, $5, $6)';
        
        $result = pg_query_params($con, $query, [
            $first_name,
            $last_name,
            $username,
            $email,
            $password_hash,
            $role_id
        ]);
        
        if ($result) {
            header("Location: login.php");
            exit;
        } else {
            $signup_error = "User registration error. Možná duplicitní uživatel nebo jiná chyba.";
        }
    } else {
        $signup_error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Registrace uživatele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="wrapper">
      <form class="form-signin" method="post" novalidate>
          <h2 class="form-signin-heading text-center mb-4">Registration</h2>

          <?php if (!empty($signup_error)): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($signup_error); ?></div>
          <?php endif; ?>

          <input type="text" class="form-control mb-2" name="first_name" placeholder="First name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
          <input type="text" class="form-control mb-2" name="last_name" placeholder="Last name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
          <input type="text" class="form-control mb-2" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
          <input type="email" class="form-control mb-2" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
          <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>

          <select name="role_id" class="form-control mb-3" required>
            <option value="" disabled selected>Chose role</option>
            <option value="1" <?php if(($_POST['role_id'] ?? '') == '1') echo 'selected'; ?>>Admin</option>
            <option value="2" <?php if(($_POST['role_id'] ?? '') == '2') echo 'selected'; ?>>User</option>
            <option value="3" <?php if(($_POST['role_id'] ?? '') == '3') echo 'selected'; ?>>Manager</option>
            <option value="4" <?php if(($_POST['role_id'] ?? '') == '4') echo 'selected'; ?>>CEO</option>
          </select>

          <button class="btn btn-lg btn-primary w-100" type="submit">Register</button>
      </form>
    </div>
</div>

</body>
</html>
