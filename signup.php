<?php
session_start();

include("db.php");    
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Načti data z POST
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id = trim($_POST['role_id'] ?? '');
    
    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($email) && !empty($password)) {
        
        // Hashování hesla v PHP (doporučeno)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Připrav parametrizovaný dotaz (bezpečnost)
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
            echo "Chyba při registraci uživatele.";
        }
    } else {
        echo "Vyplň prosím všechna pole.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Signup</title>
    </head>
    <body>

    <style type="text/css">
        #text{

            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }

        #button{

            padding: 10px;
            width: 100px;
            color: white;
            background-color: Lightblue;
            border: none;
        }

        #box{

            background-color: grey;
            margin: auto;
            width: 300px;
            padding: 20px;
        }

    </style>

        <div id="box">
            <form method= "post">
                <div style="font-sizeL 20px; magin: 10px;color: white;">Signup</div>
                <input id = "text" type="text" name="first_name"><br><br>
                <input id = "text" type="text" name="last_name"><br><br>
                <input id = "text" type="text" name="username"><br><br>
                <input id = "text" type="text" name="email"><br><br>
                <input id = "text" type="password" name="password"><br><br>
                <input id = "text" type="text" name="role_id"><br><br>
                <input id = "button"type="submit" value="Signup"><br><br>
                
            </form>
        </div>
    </body>
</html>