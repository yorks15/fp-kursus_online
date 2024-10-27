<?php
    session_start();
    if(isset($_POST['logout'])) {
        session_unset(); /* clear data */
        session_destroy(); /* hancurkan data */

        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboardt</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <?php include "layout/header.html" ?>

    <h3> selamat datang <?= $_SESSION["username"] ?> </h3>

    <!-- Logout -->
    <form action="dashboard.php" method="POST">
        <button type="submit" name="logout">logout</button>
    </form>

    <?php include "layout/footer.html" ?>
</body>
</html>