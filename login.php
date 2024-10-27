<?php
    include "service/database.php";
    session_start();

    $login_message = "";

    if(isset($_SESSION["is_login"])) {
        header("location: dashboard.php");
    } 

    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash_password = hash("sha256", $password); 

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$hash_password'";
        $result = $db->query($sql);

        if($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;

            header("location: dashboard.php");

        } else {
            $login_message = "Akun tidak ditemukan, silahkan register";
        }
        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <?php include "layout/header.html"; ?>
    
    <div class="login-container">
        <h3>Masuk</h3>
        <i><?= $login_message ?></i>
        <form action="login.php" method="POST" class="login-form">
            <label>Username</label>
            <input type="text" placeholder="Username" name="username" required />

            <label>Password</label>
            <input type="password" placeholder="Password" name="password" required />
            
            <div class="form-options">
                <input type="checkbox" name="remember_me" id="remember_me">
                <label for="remember_me">Ingat Saya</label>
                <a href="#">Lupa Password?</a>
            </div>
            
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
