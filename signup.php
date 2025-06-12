<?php
session_start();

include("db.php");    
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id = trim($_POST['role_id'] ?? '');
    
    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($email) && !empty($password)) {
        
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
            echo "User registration error.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

  <div class="sidebar">
    <h2>Menu</h2>
    <a href="index.php">Home</a>
    <a href="#">Profile</a>
    <a href="#">Storage</a>
    <a href="#">Hours worked</a>
    <a href="calendar.php">Calendar</a>
    <a href="#">Messages</a>
    <a href="signup.php">User registration</a>
    <a href="logout.php">Log out</a>
  </div>

  <div class="main">
    <div class="wrapper">
      <form class="form-signin" method="post">
          <h2 class="form-signin-heading text-center mb-4">Signup</h2>

          <?php if (!empty($signup_error)): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($signup_error); ?></div>
          <?php endif; ?>

          <input type="text" class="form-control mb-2" name="first_name" placeholder="First Name" required>
          <input type="text" class="form-control mb-2" name="last_name" placeholder="Last Name" required>
          <input type="text" class="form-control mb-2" name="username" placeholder="Username" required>
          <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
          <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
          <input type="text" class="form-control mb-3" name="role_id" placeholder="Role ID" required>

          <button class="btn btn-lg btn-primary w-100" type="submit">Sign Up</button>
      </form>
    </div>
  </div>

</body>
</html>
