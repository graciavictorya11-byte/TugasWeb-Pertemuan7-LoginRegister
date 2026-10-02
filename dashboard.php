<?php

session_start();

// Proteksi halaman
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container dashboard">

    <h1>🏠 Dashboard</h1>

    <h2>
        Selamat Datang!
    </h2>

    <p>
        Nama:
        <strong>
            <?= htmlspecialchars($_SESSION['username']) ?>
        </strong>
    </p>

    <p>
        Email:
        <strong>
            <?= htmlspecialchars($_SESSION['email']) ?>
        </strong>
    </p>

    <p>
        Selamat datang di halaman dashboard
        Sistem Login/Register PHP.
    </p>

    <a
        class="logout"
        href="logout.php">
        🚪 Logout
    </a>

</div>

</body>

</html>